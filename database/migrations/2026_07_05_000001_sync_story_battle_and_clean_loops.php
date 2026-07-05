<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\StoryBattle;
use App\Models\Enemy;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Remove nós inválidos de story_battle que foram inseridos incorretamente em testes/seeders antigos
        if (DB::table('story_battle')->exists()) {
            $validNodes = [
                16, 30, 39, 73, 101, 152, 162, 190, 205, 213, 220, 246, 262, 264, 275,
                288, 303, 307, 325, 330, 336, 337, 346, 351, 353, 360, 399
            ];
            StoryBattle::whereNotIn('story_node_id', $validNodes)->delete();
        }

        // 2. Sincroniza e garante as configurações exatas de combates sequenciais e simples do backup
        if (DB::table('story_nodes')->exists() && DB::table('enemies')->exists()) {
            $battles = [
                // Nó 16
                ['story_node_id' => 16, 'enemy_id' => 1, 'fight_order' => 1, 'turns_to_flee' => 4, 'win_go_to' => 180, 'flee_go_to' => 99],
                // Nó 30
                ['story_node_id' => 30, 'enemy_id' => 2, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => 241, 'flee_go_to' => null],
                // Nó 39
                ['story_node_id' => 39, 'enemy_id' => 3, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => 248, 'flee_go_to' => null],
                // Nó 73
                ['story_node_id' => 73, 'enemy_id' => 4, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => 112, 'flee_go_to' => null],
                // Nó 101
                ['story_node_id' => 101, 'enemy_id' => 5, 'fight_order' => 1, 'turns_to_flee' => 0, 'win_go_to' => 62, 'flee_go_to' => 64],
                // Nó 152
                ['story_node_id' => 152, 'enemy_id' => 6, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => 180, 'flee_go_to' => null],
                // Nó 162 (Batalha sequencial contra inimigos 7 e 8)
                ['story_node_id' => 162, 'enemy_id' => 7, 'fight_order' => 1, 'turns_to_flee' => 0, 'win_go_to' => null, 'flee_go_to' => 1],
                ['story_node_id' => 162, 'enemy_id' => 8, 'fight_order' => 2, 'turns_to_flee' => 0, 'win_go_to' => 32, 'flee_go_to' => 1],
                // Nó 190
                ['story_node_id' => 190, 'enemy_id' => 9, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => 147, 'flee_go_to' => null],
                // Nó 205
                ['story_node_id' => 205, 'enemy_id' => 10, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => 309, 'flee_go_to' => null],
                // Nó 213 (Batalha sequencial contra inimigos 12, 13 e 14)
                ['story_node_id' => 213, 'enemy_id' => 12, 'fight_order' => 1, 'turns_to_flee' => 0, 'win_go_to' => null, 'flee_go_to' => 209],
                ['story_node_id' => 213, 'enemy_id' => 13, 'fight_order' => 2, 'turns_to_flee' => 0, 'win_go_to' => null, 'flee_go_to' => 209],
                ['story_node_id' => 213, 'enemy_id' => 14, 'fight_order' => 3, 'turns_to_flee' => 0, 'win_go_to' => 235, 'flee_go_to' => 209],
                // Nó 220 (Batalha sequencial contra inimigos 15 e 16)
                ['story_node_id' => 220, 'enemy_id' => 15, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => null, 'flee_go_to' => null],
                ['story_node_id' => 220, 'enemy_id' => 16, 'fight_order' => 2, 'turns_to_flee' => null, 'win_go_to' => 403, 'flee_go_to' => null],
                // Nó 246
                ['story_node_id' => 246, 'enemy_id' => 17, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => 174, 'flee_go_to' => null],
                // Nó 262
                ['story_node_id' => 262, 'enemy_id' => 1, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => 180, 'flee_go_to' => null],
                // Nó 264
                ['story_node_id' => 264, 'enemy_id' => 18, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => 177, 'flee_go_to' => null],
                // Nó 275
                ['story_node_id' => 275, 'enemy_id' => 19, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => 272, 'flee_go_to' => null],
                // Nó 288 (Batalha sequencial contra inimigos 7 e 8)
                ['story_node_id' => 288, 'enemy_id' => 7, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => null, 'flee_go_to' => null],
                ['story_node_id' => 288, 'enemy_id' => 8, 'fight_order' => 2, 'turns_to_flee' => null, 'win_go_to' => 32, 'flee_go_to' => null],
                // Nó 303
                ['story_node_id' => 303, 'enemy_id' => 9, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => 147, 'flee_go_to' => null],
                // Nó 307
                ['story_node_id' => 307, 'enemy_id' => 20, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => 177, 'flee_go_to' => null],
                // Nó 325
                ['story_node_id' => 325, 'enemy_id' => 20, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => 177, 'flee_go_to' => null],
                // Nó 330
                ['story_node_id' => 330, 'enemy_id' => 3, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => 119, 'flee_go_to' => null],
                // Nó 336
                ['story_node_id' => 336, 'enemy_id' => 1, 'fight_order' => 1, 'turns_to_flee' => 4, 'win_go_to' => 180, 'flee_go_to' => 99],
                // Nó 337
                ['story_node_id' => 337, 'enemy_id' => 21, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => 400, 'flee_go_to' => null],
                // Nó 346 (Batalha sequencial contra inimigos 15 e 16)
                ['story_node_id' => 346, 'enemy_id' => 15, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => null, 'flee_go_to' => null],
                ['story_node_id' => 346, 'enemy_id' => 16, 'fight_order' => 2, 'turns_to_flee' => null, 'win_go_to' => 403, 'flee_go_to' => null],
                // Nó 351
                ['story_node_id' => 351, 'enemy_id' => 21, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => 400, 'flee_go_to' => null],
                // Nó 353
                ['story_node_id' => 353, 'enemy_id' => 21, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => 400, 'flee_go_to' => null],
                // Nó 360
                ['story_node_id' => 360, 'enemy_id' => 22, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => 229, 'flee_go_to' => null],
                // Nó 399
                ['story_node_id' => 399, 'enemy_id' => 23, 'fight_order' => 1, 'turns_to_flee' => null, 'win_go_to' => 272, 'flee_go_to' => null],
            ];

            foreach ($battles as $b) {
                if (DB::table('story_nodes')->where('id', $b['story_node_id'])->exists() &&
                    DB::table('enemies')->where('id', $b['enemy_id'])->exists()) {
                    StoryBattle::updateOrCreate(
                        ['story_node_id' => $b['story_node_id'], 'enemy_id' => $b['enemy_id']],
                        [
                            'fight_order' => $b['fight_order'],
                            'turns_to_flee' => $b['turns_to_flee'],
                            'win_go_to' => $b['win_go_to'],
                            'flee_go_to' => $b['flee_go_to'],
                        ]
                    );
                }
            }
        }

        // 3. Limpeza de estados duplicados em loop no nó 288 (ex: personagem id 5)
        if (DB::table('player_battle_states')->exists()) {
            $allStuck = DB::table('player_battle_states')
                ->where('story_node_id', 288)
                ->orderBy('id', 'asc')
                ->get()
                ->groupBy('player_id');

            foreach ($allStuck as $playerId => $records) {
                if ($records->count() > 1) {
                    $firstId = $records->first()->id;
                    DB::table('player_battle_states')
                        ->where('player_id', $playerId)
                        ->where('story_node_id', 288)
                        ->where('id', '!=', $firstId)
                        ->delete();
                }
            }

            // 4. Se algum jogador derrotou o Macaco-Cachorro (inimigo 7) no nó 288 e ficou preso como 'won',
            // transita seu estado para enfrentar o Cachorro-Macaco (inimigo 8)
            $enemy8 = Enemy::find(8);
            if ($enemy8) {
                DB::table('player_battle_states')
                    ->where('story_node_id', 288)
                    ->where('enemy_id', 7)
                    ->where('status', 'won')
                    ->update([
                        'enemy_id' => $enemy8->id,
                        'enemy_current_ability' => $enemy8->ability,
                        'enemy_current_energy' => $enemy8->energy,
                        'round_number' => 1,
                        'status' => 'in_progress',
                        'last_round_log' => json_encode([
                            'message' => '🏆 Você derrotou Macaco-Cachorro! Combate vencido! Você agora enfrenta um novo oponente: Cachorro-Macaco!',
                            'round' => 0
                        ])
                    ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não reversível por ser correção de integridade de dados
    }
};
