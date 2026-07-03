<?php

namespace App\Console\Commands;

use App\Models\Choice;
use App\Models\Enchantment;
use App\Models\Item;
use Illuminate\Console\Command;

class MapGameFlagsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:map-flags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mapeia automaticamente required_flag nas escolhas (choices) com base nas magias e itens do jogo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando o mapeamento de flags nas escolhas...');

        // Mapeamento de termos encontrados no texto para os nomes exatos de flags (magias/itens)
        $mappings = [
            // Magias (Enchantments)
            'Cópia de Criatura' => 'Cópia de criatura',
            'Cópia de criatura' => 'Cópia de criatura',
            'Percepção Extra-Sensorial' => 'Percepção Extra-Sensorial',
            'Fogo' => 'Fogo',
            'Ouro dos Tolos' => 'Ouro dos Tolos',
            'Ilusão' => 'Ilusão',
            'Levitação' => 'Levitação',
            'Escudo' => 'Escudo',
            'Força' => 'Força',
            'Fraqueza' => 'Fraqueza',
            
            // Itens (Items)
            'Miríade de Bolso' => 'Miríade de Bolso',
            'Aranha em um Vidro' => 'Aranha em um Vidro',
            'Pequenas Amoras' => 'Pequenas Amoras',
            'Essência de Erva de Porco' => 'Essência de Erva de Porco',
            'Adaga de metal encantada' => 'Adaga de metal encantada',
            'adaga de arremesso' => 'Adaga de metal encantada',
        ];

        $choices = Choice::all();
        $updatedCount = 0;

        foreach ($choices as $choice) {
            $desc = $choice->choice_description;

            // Evitar colocar flag de requisito em opções de fallback (ex: "Se você não tiver nenhum encanto...")
            if (stripos($desc, 'não tiver') !== false || 
                stripos($desc, 'não puder') !== false ||
                stripos($desc, 'se não') !== false ||
                stripos($desc, 'nenhum') !== false ||
                stripos($desc, 'não possuir') !== false) {
                continue;
            }

            foreach ($mappings as $searchTerm => $flagName) {
                if (stripos($desc, $searchTerm) !== false) {
                    // Atualiza apenas se estiver vazio ou diferente
                    if ($choice->required_flag !== $flagName) {
                        $choice->required_flag = $flagName;
                        $choice->save();
                        $updatedCount++;
                        $this->line("Mapeado Choice ID {$choice->id} (Nó {$choice->from_story_node_id} -> {$choice->to_story_node_id}): [{$flagName}]");
                    }
                    break; // Para no primeiro match encontrado para esta escolha
                }
            }
        }

        $this->info("Mapeamento concluído com sucesso! {$updatedCount} escolhas atualizadas.");
        return 0;
    }
}
