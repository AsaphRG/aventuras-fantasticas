<?php

namespace App\Console\Commands;

use App\Models\Player;
use Illuminate\Console\Command;

class SyncPlayerFlagsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:sync-player-flags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sincroniza as flags (player_flag) para personagens já criados com base nas magias não utilizadas e itens existentes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando a sincronização de flags (player_flag) dos jogadores...');

        $players = Player::all();
        $count = 0;

        foreach ($players as $player) {
            $player->syncFlags();
            $count++;
            $this->line("Sincronizado Jogador ID {$player->id} (Usuário ID {$player->user_id})");
        }

        $this->info("Sincronização concluída com sucesso para {$count} personagem(ns)!");
        return 0;
    }
}
