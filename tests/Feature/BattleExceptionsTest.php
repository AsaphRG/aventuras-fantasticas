<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Player;
use App\Models\StoryNode;
use App\Models\Enemy;
use App\Models\StoryBattle;
use App\Logic\BattleEngine;

class BattleExceptionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_node_30_exception_after_four_hits()
    {
        $user = User::factory()->create();
        $storyNode = StoryNode::forceCreate(['id' => 30, 'title' => '30', 'history' => 'Luta', 'battle' => 1]);
        StoryNode::forceCreate(['id' => 241, 'title' => '241', 'history' => 'Destino', 'battle' => 0]);
        $enemy = Enemy::forceCreate(['id' => 2, 'name' => 'Fera das Garras', 'ability' => 9, 'energy' => 14]);
        
        StoryBattle::create([
            'story_node_id' => 30,
            'enemy_id' => 2,
            'win_go_to' => 241,
            'special_rules_json' => json_encode([
                'end_conditions' => [
                    [
                        'trigger' => 'enemy_hits_taken',
                        'operator' => '>=',
                        'value' => 4,
                        'action' => 'goto_node',
                        'target_node_id' => 241,
                        'message' => '💥 Ao ser atingida pela quarta vez, vá para 241.'
                    ]
                ]
            ])
        ]);

        $character = Player::forceCreate([
            'user_id' => $user->id,
            'class' => 'Warrior',
            'skillStart' => 12, 'skillCurrent' => 12,
            'energyStart' => 20, 'energyCurrent' => 20,
            'luckStart' => 12, 'luckCurrent' => 12,
            'enchantmentStart' => 0, 'gold' => 10,
            'currentStoryNode' => 30,
            'win' => false, 'dead' => false
        ]);

        $engine = new BattleEngine();
        $battleState = $engine->getOrInitializeBattle($character, $storyNode);

        // Simulamos 3 golpes certeiros no inimigo
        $battleState->enemy_hits_taken = 3;
        $battleState->save();

        // No 4º golpe, a regra especial deve ser acionada
        $battleState->enemy_hits_taken = 4;
        $battleState->save();

        $msg = '';
        $config = StoryBattle::where('story_node_id', 30)->first();
        
        // Chamamos nextRound que invocará checkSpecialRules no final ou durante o golpe
        $engine->nextRound($character, $battleState);

        $character->refresh();
        $battleState->refresh();

        $this->assertEquals(241, $character->currentStoryNode);
        $this->assertEquals('won', $battleState->status);
    }

    public function test_node_39_exception_poison_hit()
    {
        $user = User::factory()->create();
        $storyNode = StoryNode::forceCreate(['id' => 39, 'title' => '39', 'history' => 'Luta com Homem-Aranha', 'battle' => 1]);
        StoryNode::forceCreate(['id' => 208, 'title' => '208', 'history' => 'Derrota', 'battle' => 0]);
        $enemy = Enemy::forceCreate(['id' => 3, 'name' => 'Homem-Aranha', 'ability' => 7, 'energy' => 5]);
        
        StoryBattle::create([
            'story_node_id' => 39,
            'enemy_id' => 3,
            'win_go_to' => 248,
            'special_rules_json' => json_encode([
                'end_conditions' => [
                    [
                        'trigger' => 'player_hits_taken',
                        'operator' => '>=',
                        'value' => 1,
                        'action' => 'goto_node',
                        'target_node_id' => 208,
                        'mark_dead' => true,
                        'message' => '🕷️ O Homem-Aranha causou ferimento!'
                    ]
                ]
            ])
        ]);

        $character = Player::forceCreate([
            'user_id' => $user->id,
            'class' => 'Warrior',
            'skillStart' => 1, 'skillCurrent' => 1, // Habilidade baixa para garantir que apanhe
            'energyStart' => 20, 'energyCurrent' => 20,
            'luckStart' => 12, 'luckCurrent' => 12,
            'enchantmentStart' => 0, 'gold' => 10,
            'currentStoryNode' => 39,
            'win' => false, 'dead' => false
        ]);

        $engine = new BattleEngine();
        $battleState = $engine->getOrInitializeBattle($character, $storyNode);

        // Força o inimigo a ter habilidade alta para atingir o jogador em nextRound
        $battleState->enemy_current_ability = 20;
        $battleState->save();

        $engine->nextRound($character, $battleState);

        $character->refresh();
        $battleState->refresh();

        $this->assertEquals(1, $battleState->player_hits_taken);
        $this->assertEquals(208, $character->currentStoryNode);
        $this->assertTrue((bool) $character->dead);
        $this->assertEquals('lost', $battleState->status);
    }

    public function test_node_330_exception_poison_hit()
    {
        $user = User::factory()->create();
        $storyNode = StoryNode::forceCreate(['id' => 330, 'title' => '330', 'history' => 'Luta com Homem-Aranha no Nó 330', 'battle' => 1]);
        StoryNode::forceCreate(['id' => 208, 'title' => '208', 'history' => 'Derrota', 'battle' => 0]);
        $enemy = Enemy::forceCreate(['id' => 3, 'name' => 'Homem-Aranha', 'ability' => 7, 'energy' => 5]);
        
        StoryBattle::create([
            'story_node_id' => 330,
            'enemy_id' => 3,
            'win_go_to' => 208,
            'special_rules_json' => json_encode([
                'end_conditions' => [
                    [
                        'trigger' => 'player_hits_taken',
                        'operator' => '>=',
                        'value' => 1,
                        'action' => 'goto_node',
                        'target_node_id' => 208,
                        'mark_dead' => true,
                        'message' => '🕷️ O Homem-Aranha causou ferimento em você!'
                    ]
                ]
            ])
        ]);

        $character = Player::forceCreate([
            'user_id' => $user->id,
            'class' => 'Warrior',
            'skillStart' => 1, 'skillCurrent' => 1,
            'energyStart' => 20, 'energyCurrent' => 20,
            'luckStart' => 12, 'luckCurrent' => 12,
            'enchantmentStart' => 0, 'gold' => 10,
            'currentStoryNode' => 330,
            'win' => false, 'dead' => false
        ]);

        $engine = new BattleEngine();
        $battleState = $engine->getOrInitializeBattle($character, $storyNode);

        $battleState->enemy_current_ability = 20;
        $battleState->save();

        $engine->nextRound($character, $battleState);

        $character->refresh();
        $battleState->refresh();

        $this->assertEquals(1, $battleState->player_hits_taken);
        $this->assertEquals(208, $character->currentStoryNode);
        $this->assertTrue((bool) $character->dead);
        $this->assertEquals('lost', $battleState->status);
    }
}

