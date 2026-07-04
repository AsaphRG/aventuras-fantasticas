<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\StoryBattle;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Nó 30 (Fera das Garras): Após 4 golpes sofridos pela criatura, vai para 241
        StoryBattle::where('story_node_id', 30)->where('enemy_id', 2)->update([
            'special_rules_json' => json_encode([
                'end_conditions' => [
                    [
                        'trigger' => 'enemy_hits_taken',
                        'operator' => '>=',
                        'value' => 4,
                        'action' => 'goto_node',
                        'target_node_id' => 241,
                        'message' => '💥 Ao ser atingida pela quarta vez, a criatura solta um rugido estridente, recua e abre passagem! Você avança para o nó 241.'
                    ]
                ]
            ])
        ]);

        // Nó 39 (Homem-Aranha): Ao atingir o jogador pela 1ª vez, vai para 208 (morte)
        StoryBattle::where('story_node_id', 39)->where('enemy_id', 3)->update([
            'special_rules_json' => json_encode([
                'end_conditions' => [
                    [
                        'trigger' => 'player_hits_taken',
                        'operator' => '>=',
                        'value' => 1,
                        'action' => 'goto_node',
                        'target_node_id' => 208,
                        'mark_dead' => true,
                        'message' => '🕷️ O Homem-Aranha conseguiu atingir você! Seu veneno mortal se espalha rapidamente e adormece seus nervos! Você cai derrotado e é redirecionado para o nó 208.'
                    ]
                ]
            ])
        ]);

        // Nó 330 (Homem-Aranha): Ao causar ferimento no jogador, vai para 208 (morte)
        StoryBattle::where('story_node_id', 330)->where('enemy_id', 3)->update([
            'special_rules_json' => json_encode([
                'end_conditions' => [
                    [
                        'trigger' => 'player_hits_taken',
                        'operator' => '>=',
                        'value' => 1,
                        'action' => 'goto_node',
                        'target_node_id' => 208,
                        'mark_dead' => true,
                        'message' => '🕷️ O Homem-Aranha causou ferimento em você! Seu veneno mortal faz efeito imediatamente! Você cai derrotado e é redirecionado para o nó 208.'
                    ]
                ]
            ])
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        StoryBattle::whereIn('story_node_id', [30, 39, 330])->update([
            'special_rules_json' => null
        ]);
    }
};
