<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Player;
use App\Models\StoryNode;
use App\Models\Enemy;
use App\Models\StoryBattle;
use App\Models\Enchantment;
use App\Models\PlayerEnchantments;
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

    public function test_sequential_battle_shows_victory_message()
    {
        $user = User::factory()->create();
        $storyNode = StoryNode::forceCreate(['id' => 999, 'title' => '999', 'history' => 'Batalha Dupla', 'battle' => 1]);
        $enemy1 = Enemy::forceCreate(['id' => 10, 'name' => 'Goblin 1', 'ability' => 1, 'energy' => 2]);
        $enemy2 = Enemy::forceCreate(['id' => 11, 'name' => 'Goblin 2', 'ability' => 6, 'energy' => 6]);

        StoryBattle::create([
            'story_node_id' => 999,
            'enemy_id' => 10,
            'fight_order' => 1,
            'win_go_to' => null
        ]);
        StoryBattle::create([
            'story_node_id' => 999,
            'enemy_id' => 11,
            'fight_order' => 2,
            'win_go_to' => 1000
        ]);

        $character = Player::forceCreate([
            'user_id' => $user->id,
            'class' => 'Warrior',
            'skillStart' => 20, 'skillCurrent' => 20,
            'energyStart' => 20, 'energyCurrent' => 20,
            'luckStart' => 12, 'luckCurrent' => 12,
            'enchantmentStart' => 0, 'gold' => 10,
            'currentStoryNode' => 999,
            'win' => false, 'dead' => false
        ]);

        $engine = new BattleEngine();
        $battleState = $engine->getOrInitializeBattle($character, $storyNode);

        // Derrota o Goblin 1
        $battleState->enemy_current_energy = 0;
        $battleState->save();

        $res = $engine->nextRound($character, $battleState);

        $this->assertStringContainsString('🏆 Você derrotou Goblin 1! Combate vencido!', $res['message']);
        $this->assertStringContainsString('Você agora enfrenta um novo oponente: Goblin 2!', $res['message']);
        $this->assertEquals('in_progress', $battleState->status);
        $this->assertEquals(11, $battleState->enemy_id);
    }

    public function test_sequential_battle_luck_test_shows_victory_message()
    {
        $user = User::factory()->create();
        $storyNode = StoryNode::forceCreate(['id' => 998, 'title' => '998', 'history' => 'Batalha Dupla Sorte', 'battle' => 1]);
        $enemy1 = Enemy::forceCreate(['id' => 12, 'name' => 'Orc 1', 'ability' => 1, 'energy' => 1]);
        $enemy2 = Enemy::forceCreate(['id' => 13, 'name' => 'Orc 2', 'ability' => 6, 'energy' => 6]);

        StoryBattle::create([
            'story_node_id' => 998,
            'enemy_id' => 12,
            'fight_order' => 1,
            'win_go_to' => null
        ]);
        StoryBattle::create([
            'story_node_id' => 998,
            'enemy_id' => 13,
            'fight_order' => 2,
            'win_go_to' => 1000
        ]);

        $character = Player::forceCreate([
            'user_id' => $user->id,
            'class' => 'Warrior',
            'skillStart' => 20, 'skillCurrent' => 20,
            'energyStart' => 20, 'energyCurrent' => 20,
            'luckStart' => 12, 'luckCurrent' => 12,
            'enchantmentStart' => 0, 'gold' => 10,
            'currentStoryNode' => 998,
            'win' => false, 'dead' => false
        ]);

        $engine = new BattleEngine();
        $battleState = $engine->getOrInitializeBattle($character, $storyNode);

        // Configura estado para teste de sorte com energia no limiar (dano extra mata)
        $battleState->luck_test_context = 'enemy_hit';
        $battleState->enemy_current_energy = 0;
        $battleState->save();

        $res = $engine->testLuckInBattle($character, $battleState, true);

        $this->assertStringContainsString('🏆 O inimigo foi aniquilado! Combate vencido!', $res['message']);
        $this->assertStringContainsString('Um novo adversário surge: Orc 2!', $res['message']);
        $this->assertEquals('in_progress', $battleState->status);
        $this->assertEquals(13, $battleState->enemy_id);
    }

    public function test_ajax_battle_attack_returns_json()
    {
        $user = User::factory()->create();
        $storyNode = StoryNode::forceCreate(['id' => 997, 'title' => '997', 'history' => 'Batalha AJAX', 'battle' => 1]);
        $enemy = Enemy::forceCreate(['id' => 14, 'name' => 'Lobo', 'ability' => 5, 'energy' => 4]);

        StoryBattle::create([
            'story_node_id' => 997,
            'enemy_id' => 14,
            'fight_order' => 1,
            'win_go_to' => null
        ]);

        $character = Player::forceCreate([
            'user_id' => $user->id,
            'class' => 'Warrior',
            'skillStart' => 20, 'skillCurrent' => 20,
            'energyStart' => 20, 'energyCurrent' => 20,
            'luckStart' => 12, 'luckCurrent' => 12,
            'enchantmentStart' => 0, 'gold' => 10,
            'currentStoryNode' => 997,
            'win' => false, 'dead' => false
        ]);

        $response = $this->actingAs($user)
            ->withHeader('Accept', 'application/json')
            ->post(route('battle.attack', ['id' => $character->id]));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'status',
                'round_number',
                'message',
                'luck_test_context',
                'player' => ['energy_current', 'energy_start', 'skill_current', 'luck_current', 'dead'],
                'enemy' => ['id', 'name', 'ability', 'energy_current', 'energy_max'],
            ]);
    }

    public function test_game_view_renders_alpine_battle_component()
    {
        $user = User::factory()->create();
        StoryNode::forceCreate(['id' => 998, 'title' => '998', 'history' => 'Arena de Batalha', 'battle' => 1]);
        Enemy::forceCreate(['id' => 15, 'name' => 'Goblin', 'ability' => 6, 'energy' => 5]);

        StoryBattle::create([
            'story_node_id' => 998,
            'enemy_id' => 15,
            'fight_order' => 1,
            'win_go_to' => null
        ]);

        $character = Player::forceCreate([
            'user_id' => $user->id,
            'class' => 'Warrior',
            'skillStart' => 20, 'skillCurrent' => 20,
            'energyStart' => 20, 'energyCurrent' => 20,
            'luckStart' => 12, 'luckCurrent' => 12,
            'enchantmentStart' => 10, 'gold' => 10,
            'currentStoryNode' => 998,
            'win' => false, 'dead' => false
        ]);

        Enchantment::insert(['id' => 99, 'name' => 'Teste', 'description' => 'Desc']);
        PlayerEnchantments::insert([
            'player_id' => $character->id,
            'enchantment_id' => 99,
            'used' => false
        ]);

        // Initialize the battle in the engine so state exists
        $engine = new BattleEngine();
        $engine->getOrInitializeBattle($character, $character->storyNode);

        $response = $this->actingAs($user)->get(route('game', ['id' => $character->id]));

        $response->assertStatus(200)
            ->assertSee('x-data="battleArena(', false)
            ->assertSee('ATACAR / PRÓXIMA RODADA');
    }

    public function test_node_288_sequential_battle_no_loop_and_transition_to_32()
    {
        $user = User::factory()->create();
        $storyNode = StoryNode::forceCreate(['id' => 888, 'title' => '888', 'history' => 'Luta 888', 'battle' => 1]);
        StoryNode::forceCreate(['id' => 889, 'title' => '889', 'history' => 'Vitória 889', 'battle' => 0]);
        Enemy::forceCreate(['id' => 100, 'name' => 'Macaco', 'ability' => 1, 'energy' => 2]);
        Enemy::forceCreate(['id' => 101, 'name' => 'Cachorro', 'ability' => 1, 'energy' => 2]);

        StoryBattle::create([
            'story_node_id' => 888,
            'enemy_id' => 100,
            'fight_order' => 1,
            'win_go_to' => null
        ]);
        StoryBattle::create([
            'story_node_id' => 888,
            'enemy_id' => 101,
            'fight_order' => 2,
            'win_go_to' => 889
        ]);

        $character = Player::forceCreate([
            'user_id' => $user->id,
            'class' => 'Warrior',
            'skillStart' => 20, 'skillCurrent' => 20,
            'energyStart' => 20, 'energyCurrent' => 20,
            'luckStart' => 12, 'luckCurrent' => 12,
            'enchantmentStart' => 0, 'gold' => 10,
            'currentStoryNode' => 888,
            'win' => false, 'dead' => false
        ]);

        $engine = new BattleEngine();
        $battleState = $engine->getOrInitializeBattle($character, $storyNode);
        $this->assertEquals(100, $battleState->enemy_id);

        // Derrota o primeiro inimigo
        $battleState->enemy_current_energy = 0;
        $battleState->save();
        $engine->nextRound($character, $battleState);
        $this->assertEquals('in_progress', $battleState->status);
        $this->assertEquals(101, $battleState->enemy_id);

        // Derrota o segundo inimigo
        $battleState->enemy_current_energy = 0;
        $battleState->save();
        $engine->nextRound($character, $battleState);
        $this->assertEquals('won', $battleState->status);

        $character->refresh();
        $this->assertEquals(889, $character->currentStoryNode);

        // Tentar obter ou inicializar batalha novamente no nó 888 não deve criar novo combate em loop
        $reInitialized = $engine->getOrInitializeBattle($character, $storyNode);
        $this->assertNull($reInitialized);
    }

    public function test_sequential_battle_luck_test_win_go_to_transition()
    {
        $user = User::factory()->create();
        $storyNode = StoryNode::forceCreate(['id' => 887, 'title' => '887', 'history' => 'Luta Sorte 887', 'battle' => 1]);
        StoryNode::forceCreate(['id' => 889, 'title' => '889', 'history' => 'Vitória 889', 'battle' => 0]);
        Enemy::forceCreate(['id' => 102, 'name' => 'Urso', 'ability' => 1, 'energy' => 1]);

        StoryBattle::create([
            'story_node_id' => 887,
            'enemy_id' => 102,
            'fight_order' => 1,
            'win_go_to' => 889
        ]);

        $character = Player::forceCreate([
            'user_id' => $user->id,
            'class' => 'Warrior',
            'skillStart' => 20, 'skillCurrent' => 20,
            'energyStart' => 20, 'energyCurrent' => 20,
            'luckStart' => 12, 'luckCurrent' => 12,
            'enchantmentStart' => 0, 'gold' => 10,
            'currentStoryNode' => 887,
            'win' => false, 'dead' => false
        ]);

        $engine = new BattleEngine();
        $battleState = $engine->getOrInitializeBattle($character, $storyNode);
        $battleState->luck_test_context = 'enemy_hit';
        $battleState->enemy_current_energy = 2;
        $battleState->save();

        $engine->testLuckInBattle($character, $battleState, true);

        $character->refresh();
        $battleState->refresh();
        $this->assertEquals('won', $battleState->status);
        $this->assertEquals(889, $character->currentStoryNode);
    }
}

