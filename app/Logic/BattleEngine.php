<?php

namespace App\Logic;

use App\Models\Player;
use App\Models\StoryNode;
use App\Models\Enemy;
use App\Models\StoryBattle;
use App\Models\PlayerBattleState;
use App\Logic\Player as PlayerLogic;

class BattleEngine
{
    /**
     * Obtém uma batalha em andamento para o jogador no nó atual ou inicializa uma nova.
     */
    public function getOrInitializeBattle(Player $player, StoryNode $node): ?PlayerBattleState
    {
        // Verifica se há batalhas ativas para este jogador neste nó
        $battleState = PlayerBattleState::where('player_id', $player->id)
            ->where('story_node_id', $node->id)
            ->whereNotIn('status', ['won', 'fled'])
            ->first();

        if ($battleState) {
            return $battleState;
        }

        // Verifica se o jogador já concluiu os combates deste nó para não reabrir em loop infinito
        $alreadyFinished = PlayerBattleState::where('player_id', $player->id)
            ->where('story_node_id', $node->id)
            ->whereIn('status', ['won', 'fled'])
            ->exists();

        if ($alreadyFinished) {
            return null;
        }

        // Se não há batalha ativa, verifica se o nó possui inimigos configurados em story_battle
        $storyBattles = StoryBattle::where('story_node_id', $node->id)
            ->orderBy('fight_order', 'asc')
            ->orderBy('enemy_id', 'asc')
            ->get();

        if ($storyBattles->isEmpty()) {
            return null;
        }

        // Pega o primeiro inimigo para iniciar o combate
        $firstBattleConfig = $storyBattles->first();
        $enemy = Enemy::find($firstBattleConfig->enemy_id);

        if (!$enemy) {
            return null;
        }

        return PlayerBattleState::create([
            'player_id' => $player->id,
            'story_node_id' => $node->id,
            'enemy_id' => $enemy->id,
            'enemy_current_ability' => $enemy->ability,
            'enemy_current_energy' => $enemy->energy,
            'enemy_hits_taken' => 0,
            'player_hits_taken' => 0,
            'round_number' => 1,
            'status' => 'in_progress',
            'luck_test_context' => 'none',
            'last_round_log' => json_encode([
                'message' => "⚔️ Um combate se inicia contra {$enemy->name}! Prepare-se para lutar!",
                'round' => 0
            ])
        ]);
    }

    /**
     * Processa uma rodada de ataque entre o jogador e o inimigo atual.
     */
    public function nextRound(Player $player, PlayerBattleState $battleState): array
    {
        if ($battleState->status === 'won' || $battleState->status === 'lost' || $battleState->status === 'fled') {
            return ['status' => $battleState->status, 'message' => 'O combate já foi encerrado.'];
        }

        $config = StoryBattle::where('story_node_id', $battleState->story_node_id)
            ->where('enemy_id', $battleState->enemy_id)
            ->first();

        $playerLogic = new PlayerLogic(
            $player->skillStart,
            $player->skillCurrent,
            $player->energyStart,
            $player->energyCurrent,
            $player->luckStart,
            $player->luckCurrent,
            $player->enchantmentStart,
            $player->gold,
            $player->currentStoryNode,
            $player->id,
            $player->win,
            $player->dead
        );
        $playerLogic->loadItems($player->items);

        $playerDice1 = rand(1, 6);
        $playerDice2 = rand(1, 6);
        $playerRoll = $playerDice1 + $playerDice2;
        $playerFA = $playerRoll + $playerLogic->getEffectiveSkill();

        $enemyDice1 = rand(1, 6);
        $enemyDice2 = rand(1, 6);
        $enemyRoll = $enemyDice1 + $enemyDice2;
        $enemyFA = $enemyRoll + $battleState->enemy_current_ability;

        $enemy = Enemy::find($battleState->enemy_id);
        $enemyName = $enemy ? $enemy->name : 'Oponente';

        $status = 'in_progress';
        $luckContext = 'none';
        $msg = "";

        if ($playerFA > $enemyFA) {
            $damage = $config ? $config->custom_damage_to_enemy : 2;
            $battleState->enemy_current_energy = max(0, $battleState->enemy_current_energy - $damage);
            $battleState->enemy_hits_taken = ((int) $battleState->enemy_hits_taken) + 1;
            $status = 'waiting_luck_test';
            $luckContext = 'enemy_hit';
            $msg = "⚔️ Rodada {$battleState->round_number}: Você rolou {$playerDice1}+{$playerDice2} ({$playerRoll}) + Hab {$playerLogic->getEffectiveSkill()} = FA {$playerFA}! {$enemyName} rolou {$enemyDice1}+{$enemyDice2} ({$enemyRoll}) + Hab {$battleState->enemy_current_ability} = FA {$enemyFA}. Você atingiu {$enemyName} causando {$damage} de dano!";
        } elseif ($enemyFA > $playerFA) {
            $damage = $config ? $config->custom_damage_to_player : 2;
            $playerLogic->decreaseEnergyCurrent($damage);
            $playerLogic->syncToModel($player);
            $player->save();
            $battleState->player_hits_taken = ((int) $battleState->player_hits_taken) + 1;
            $status = 'waiting_luck_test';
            $luckContext = 'player_hit';
            $msg = "⚔️ Rodada {$battleState->round_number}: Você rolou {$playerDice1}+{$playerDice2} ({$playerRoll}) + Hab {$playerLogic->getEffectiveSkill()} = FA {$playerFA}! {$enemyName} rolou {$enemyDice1}+{$enemyDice2} ({$enemyRoll}) + Hab {$battleState->enemy_current_ability} = FA {$enemyFA}. {$enemyName} atingiu você causando {$damage} de dano!";
        } else {
            $status = 'in_progress';
            $luckContext = 'none';
            $msg = "⚔️ Rodada {$battleState->round_number}: Você rolou {$playerDice1}+{$playerDice2} ({$playerRoll}) + Hab {$playerLogic->getEffectiveSkill()} = FA {$playerFA}! {$enemyName} rolou {$enemyDice1}+{$enemyDice2} ({$enemyRoll}) + Hab {$battleState->enemy_current_ability} = FA {$enemyFA}. Empate! Os golpes foram defendidos com maestria!";
        }

        // Checa regras especiais (ex: ir para nó após N golpes sofridos)
        if ($this->checkSpecialRules($player, $battleState, $config, $msg)) {
            $battleState->round_number += 1;
            $battleState->last_round_log = json_encode([
                'message' => $msg,
                'player_fa' => $playerFA,
                'enemy_fa' => $enemyFA,
                'round' => $battleState->round_number - 1
            ]);
            $battleState->save();
            return [
                'status' => $battleState->status,
                'luck_context' => 'none',
                'message' => $msg,
                'round' => $battleState->round_number
            ];
        }

        // Checa se o inimigo morreu
        if ($battleState->enemy_current_energy <= 0) {
            // Pela regra do livro/backup: quando win_go_to é NULL, há um próximo inimigo na sequência!
            $nextEnemyBattle = null;
            if ($config && $config->win_go_to === null) {
                $nextEnemyBattle = StoryBattle::where('story_node_id', $battleState->story_node_id)
                    ->where('enemy_id', '>', $battleState->enemy_id)
                    ->orderBy('enemy_id', 'asc')
                    ->first();
            }
            if (!$nextEnemyBattle) {
                $nextEnemyBattle = StoryBattle::where('story_node_id', $battleState->story_node_id)
                    ->where('fight_order', '>', $config ? $config->fight_order : 1)
                    ->orderBy('fight_order', 'asc')
                    ->first();
            }

            if ($nextEnemyBattle) {
                $nextEnemy = Enemy::find($nextEnemyBattle->enemy_id);
                if ($nextEnemy) {
                    $battleState->enemy_id = $nextEnemy->id;
                    $battleState->enemy_current_ability = $nextEnemy->ability;
                    $battleState->enemy_current_energy = $nextEnemy->energy;
                    $status = 'in_progress';
                    $luckContext = 'none';
                    $msg .= " 🏆 Você derrotou {$enemyName}! Combate vencido! Você agora enfrenta um novo oponente: {$nextEnemy->name}!";
                }
            } else {
                $status = 'won';
                $luckContext = 'none';
                $msg .= " 🏆 Você derrotou {$enemyName}! Combate vencido!";
                if ($config && $config->win_go_to) {
                    $player->currentStoryNode = $config->win_go_to;
                    $player->save();
                }
            }
        }

        // Checa se o jogador morreu
        if ($player->energyCurrent <= 0) {
            $player->dead = true;
            $player->save();
            $status = 'lost';
            $luckContext = 'none';
            $msg .= " ☠️ Você sucumbiu aos seus ferimentos em combate!";
        }

        $battleState->round_number += 1;
        $battleState->status = $status;
        $battleState->luck_test_context = $luckContext;
        $battleState->last_round_log = json_encode([
            'message' => $msg,
            'player_fa' => $playerFA,
            'enemy_fa' => $enemyFA,
            'round' => $battleState->round_number - 1
        ]);
        $battleState->save();

        return [
            'status' => $status,
            'luck_context' => $luckContext,
            'message' => $msg,
            'round' => $battleState->round_number
        ];
    }

    /**
     * Processa o teste de sorte logo após um golpe ter sido desferido.
     */
    public function testLuckInBattle(Player $player, PlayerBattleState $battleState, bool $useLuck): array
    {
        if (!$useLuck || $battleState->luck_test_context === 'none') {
            $battleState->status = ($battleState->enemy_current_energy <= 0 && $battleState->status !== 'won') ? 'won' : 'in_progress';
            $battleState->luck_test_context = 'none';
            $battleState->save();
            return ['success' => true, 'message' => 'Você optou por não testar a sorte e o combate segue para a próxima rodada.'];
        }

        if ($player->luckCurrent <= 0) {
            return ['success' => false, 'message' => 'Você não possui pontos de Sorte suficientes!'];
        }

        $dice1 = rand(1, 6);
        $dice2 = rand(1, 6);
        $roll = $dice1 + $dice2;
        $luckSuccess = ($roll <= $player->luckCurrent);

        // Consome 1 de sorte
        $player->luckCurrent = max(0, $player->luckCurrent - 1);

        $logData = json_decode($battleState->last_round_log, true) ?: [];
        $baseMsg = $logData['message'] ?? '';
        $luckMsg = "";
        $prevLuck = $player->luckCurrent + 1;

        if ($battleState->luck_test_context === 'enemy_hit') {
            if ($luckSuccess) {
                // Sorte boa: causa +2 de dano extra (total de 4)
                $battleState->enemy_current_energy = max(0, $battleState->enemy_current_energy - 2);
                $luckMsg = " 🍀 Teste de Sorte (Rolou {$roll} <= Sorte {$prevLuck}): SUCESSO! Você desferiu um golpe fatal, causando +2 de dano extra ao oponente!";
            } else {
                // Sorte ruim: causa -1 de dano (total de apenas 1, recupera 1 de energia ao inimigo)
                $battleState->enemy_current_energy += 1;
                $luckMsg = " 💥 Teste de Sorte (Rolou {$roll} > Sorte {$prevLuck}): FALHA! Seu golpe pegou apenas de raspão! O oponente sofreu -1 de dano!";
            }

            // Verifica se morreu com o dano extra
            if ($battleState->enemy_current_energy <= 0) {
                $config = StoryBattle::where('story_node_id', $battleState->story_node_id)
                    ->where('enemy_id', $battleState->enemy_id)
                    ->first();
                $nextEnemyBattle = null;
                if ($config && $config->win_go_to === null) {
                    $nextEnemyBattle = StoryBattle::where('story_node_id', $battleState->story_node_id)
                        ->where('enemy_id', '>', $battleState->enemy_id)
                        ->orderBy('enemy_id', 'asc')
                        ->first();
                }
                if (!$nextEnemyBattle) {
                    $nextEnemyBattle = StoryBattle::where('story_node_id', $battleState->story_node_id)
                        ->where('fight_order', '>', $config ? $config->fight_order : 1)
                        ->orderBy('fight_order', 'asc')
                        ->first();
                }

                if ($nextEnemyBattle) {
                    $nextEnemy = Enemy::find($nextEnemyBattle->enemy_id);
                    if ($nextEnemy) {
                        $battleState->enemy_id = $nextEnemy->id;
                        $battleState->enemy_current_ability = $nextEnemy->ability;
                        $battleState->enemy_current_energy = $nextEnemy->energy;
                        $battleState->status = 'in_progress';
                        $luckMsg .= " 🏆 O inimigo foi aniquilado! Combate vencido! Um novo adversário surge: {$nextEnemy->name}!";
                    }
                } else {
                    $battleState->status = 'won';
                    $luckMsg .= " 🏆 O inimigo foi aniquilado! Combate vencido!";
                    if ($config && $config->win_go_to) {
                        $player->currentStoryNode = $config->win_go_to;
                        $player->save();
                    }
                }
            } else {
                $battleState->status = 'in_progress';
            }
        } elseif ($battleState->luck_test_context === 'player_hit') {
            if ($luckSuccess) {
                // Sorte boa: absorve 1 de dano (recupera 1 de energia para o jogador)
                $player->energyCurrent = min($player->energyStart, $player->energyCurrent + 1);
                if ($player->energyCurrent > 0) {
                    $player->dead = false;
                }
                $luckMsg = " 🍀 Teste de Sorte (Rolou {$roll} <= Sorte {$prevLuck}): SUCESSO! Você esquivou de parte do impacto e reduziu o dano sofrido em 1 ponto!";
            } else {
                // Sorte ruim: sofre +1 de dano extra (total de 3)
                $player->energyCurrent = max(0, $player->energyCurrent - 1);
                if ($player->energyCurrent <= 0) {
                    $player->dead = true;
                    $battleState->status = 'lost';
                }
                $luckMsg = " 💥 Teste de Sorte (Rolou {$roll} > Sorte {$prevLuck}): FALHA! O golpe atingiu uma artéria! Você sofreu +1 de dano extra!";
            }

            if ($battleState->status !== 'lost') {
                $battleState->status = 'in_progress';
            }
        }

        $config = StoryBattle::where('story_node_id', $battleState->story_node_id)
            ->where('enemy_id', $battleState->enemy_id)
            ->first();
        $this->checkSpecialRules($player, $battleState, $config, $luckMsg);

        $player->save();
        $battleState->luck_test_context = 'none';
        
        $logData['message'] = $baseMsg . $luckMsg;
        $battleState->last_round_log = json_encode($logData);
        $battleState->save();

        return [
            'success' => true,
            'message' => $baseMsg . $luckMsg,
            'luck_msg' => $luckMsg,
            'status' => $battleState->status,
            'player_energy' => $player->energyCurrent,
            'player_luck' => $player->luckCurrent,
            'enemy_energy' => $battleState->enemy_current_energy
        ];
    }

    /**
     * Tenta fugir do combate, aplicando penalidades e regras da seção.
     */
    public function fleeBattle(Player $player, PlayerBattleState $battleState): array
    {
        $config = StoryBattle::where('story_node_id', $battleState->story_node_id)
            ->where('enemy_id', $battleState->enemy_id)
            ->first();

        $canFlee = $config && ($config->can_flee || $config->turns_to_flee !== null || $config->flee_go_to !== null);
        if (!$canFlee) {
            return ['success' => false, 'message' => 'Não é possível fugir deste combate!'];
        }

        $fleeRounds = $config->turns_to_flee ?? $config->flee_after_rounds;
        if ($fleeRounds !== null && ($battleState->round_number - 1) < $fleeRounds) {
            return [
                'success' => false, 
                'message' => "Você só poderá fugir após a rodada {$fleeRounds} (Rodada Atual: " . ($battleState->round_number - 1) . ")."
            ];
        }

        // Aplica dano de fuga (se houver, padrão 2)
        $fleeDamage = $config->flee_damage ?? 2;
        if ($fleeDamage > 0) {
            $player->energyCurrent = max(0, $player->energyCurrent - $fleeDamage);
            if ($player->energyCurrent <= 0) {
                $player->dead = true;
                $player->save();
                $battleState->status = 'lost';
                $battleState->save();
                return ['success' => false, 'message' => "Você tentou fugir, mas sofreu {$fleeDamage} de dano ao virar as costas e sucumbiu aos ferimentos!"];
            }
        }

        $fleeNode = $config->flee_go_to ?? $config->flee_to_story_node_id;
        if ($fleeNode) {
            $player->currentStoryNode = $fleeNode;
        }

        $player->save();
        $battleState->status = 'fled';
        $battleState->save();

        return [
            'success' => true,
            'message' => "Você escapou da batalha" . ($fleeDamage > 0 ? " sofrendo {$fleeDamage} de dano na fuga!" : "!"),
            'to_node' => $player->currentStoryNode
        ];
    }

    /**
     * Verifica e aplica regras especiais de combate definidas no JSON da configuração.
     */
    protected function checkSpecialRules(Player $player, PlayerBattleState $battleState, ?StoryBattle $config, string &$msg): bool
    {
        if (!$config || !$config->special_rules_json) {
            return false;
        }

        $rules = json_decode($config->special_rules_json, true);
        if (!$rules) {
            return false;
        }

        $conditions = $rules['end_conditions'] ?? (isset($rules['end_condition']) ? [$rules['end_condition']] : (isset($rules['trigger']) ? [$rules] : []));

        foreach ($conditions as $rule) {
            $trigger = $rule['trigger'] ?? ($rule['type'] ?? '');
            $operator = $rule['operator'] ?? '>=';
            $targetValue = (int) ($rule['value'] ?? 0);
            $action = $rule['action'] ?? 'goto_node';

            $currentVal = 0;
            switch ($trigger) {
                case 'enemy_hits_taken':
                    $currentVal = (int) $battleState->enemy_hits_taken;
                    break;
                case 'player_hits_taken':
                    $currentVal = (int) $battleState->player_hits_taken;
                    break;
                case 'rounds_elapsed':
                case 'max_rounds':
                    $currentVal = (int) ($battleState->round_number - 1);
                    break;
                case 'enemy_hp_threshold':
                    $currentVal = (int) $battleState->enemy_current_energy;
                    break;
                case 'player_hp_threshold':
                    $currentVal = (int) $player->energyCurrent;
                    break;
                default:
                    continue 2;
            }

            $conditionMet = false;
            switch ($operator) {
                case '>=': $conditionMet = ($currentVal >= $targetValue); break;
                case '>':  $conditionMet = ($currentVal > $targetValue); break;
                case '<=': $conditionMet = ($currentVal <= $targetValue); break;
                case '<':  $conditionMet = ($currentVal < $targetValue); break;
                case '==':
                case '=':  $conditionMet = ($currentVal == $targetValue); break;
            }

            if ($conditionMet) {
                $customMsg = $rule['message'] ?? " ⚡ Regra especial ativada: o rumo da batalha mudou!";
                $msg .= " " . trim($customMsg);

                if ($action === 'goto_node' && !empty($rule['target_node_id'])) {
                    $player->currentStoryNode = $rule['target_node_id'];
                    if (!empty($rule['mark_dead'])) {
                        $player->dead = true;
                        $battleState->status = 'lost';
                    } else {
                        $battleState->status = 'won';
                    }
                    $player->save();
                } elseif ($action === 'win' || $action === 'win_battle') {
                    $battleState->status = 'won';
                } elseif ($action === 'lose' || $action === 'lose_battle') {
                    $player->dead = true;
                    $player->save();
                    $battleState->status = 'lost';
                }

                $battleState->save();
                return true;
            }
        }

        return false;
    }
}
