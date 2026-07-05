<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Player;
use App\Models\StoryNode;
use App\Models\NodeEffect;

use App\Models\Choice;
use App\Models\Enchantment;
use App\Models\PlayerEnchantments;

class NarrativeDeathTest extends TestCase
{
    use RefreshDatabase;

    public function test_story_node_with_is_death_flag_marks_character_as_dead()
    {
        $user = User::factory()->create();
        $storyNode = StoryNode::forceCreate([
            'id' => 150,
            'title' => 'Abismo Mortal',
            'history' => 'Você cai no abismo escuro sem fundo.',
            'battle' => 0,
            'is_death' => true
        ]);

        Enchantment::insert(['id' => 1, 'name' => 'Magia Teste', 'description' => 'Desc']);

        $player = Player::create([
            'user_id' => $user->id,
            'class' => 'Warrior',
            'skillStart' => 10,
            'skillCurrent' => 10,
            'energyStart' => 20,
            'energyCurrent' => 20,
            'luckStart' => 10,
            'luckCurrent' => 10,
            'enchantmentStart' => 1,
            'gold' => 5,
            'currentStoryNode' => 150,
            'win' => false,
            'dead' => false,
        ]);

        PlayerEnchantments::insert([
            'player_id' => $player->id,
            'enchantment_id' => 1,
            'used' => false
        ]);

        $response = $this->actingAs($user)->get("/game/{$player->id}");
        $response->assertStatus(200);

        $player->refresh();
        $this->assertTrue((bool) $player->dead);
    }

    public function test_node_effect_with_dead_attribute_marks_character_as_dead()
    {
        $user = User::factory()->create();
        $node1 = StoryNode::forceCreate([
            'id' => 1,
            'title' => 'Início',
            'history' => 'Começo',
            'battle' => 0
        ]);
        $node2 = StoryNode::forceCreate([
            'id' => 2,
            'title' => 'Armadilha de Veneno',
            'history' => 'Você bebe do cálice envenenado.',
            'battle' => 0
        ]);

        $choice = Choice::create([
            'from_story_node_id' => 1,
            'to_story_node_id' => 2,
            'choice_description' => 'Beber o cálice'
        ]);

        Enchantment::insert(['id' => 2, 'name' => 'Magia Teste 2', 'description' => 'Desc 2']);

        NodeEffect::create([
            'story_node_id' => 2,
            'attribute' => 'dead',
            'value' => 1,
            'message' => '💀 O líquido era um veneno mortal!'
        ]);

        $player = Player::create([
            'user_id' => $user->id,
            'class' => 'Warrior',
            'skillStart' => 10,
            'skillCurrent' => 10,
            'energyStart' => 20,
            'energyCurrent' => 20,
            'luckStart' => 10,
            'luckCurrent' => 10,
            'enchantmentStart' => 1,
            'gold' => 5,
            'currentStoryNode' => 1,
            'win' => false,
            'dead' => false,
        ]);

        PlayerEnchantments::insert([
            'player_id' => $player->id,
            'enchantment_id' => 2,
            'used' => false
        ]);

        // Simula escolha para o nó 2 (aplicando applyNodeEffects no nextChap)
        $response = $this->actingAs($user)->get("/nextChap/{$player->id}?choice_id={$choice->id}");
        $response->assertRedirect("/game/{$player->id}");

        $player->refresh();
        $this->assertTrue((bool) $player->dead);
    }
}
