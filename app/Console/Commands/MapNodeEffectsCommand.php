<?php

namespace App\Console\Commands;

use App\Models\NodeEffect;
use App\Models\StoryNode;
use Illuminate\Console\Command;

class MapNodeEffectsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'game:map-node-effects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mapeia automaticamente efeitos de atributos nos capítulos (ex: +2 de Sorte no Capítulo 21)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando o mapeamento de efeitos de capítulo...');

        // Garantir explicitamente o bônus do Capítulo 21 solicitado pelo usuário
        $node21 = NodeEffect::firstOrCreate([
            'story_node_id' => 21,
            'attribute' => 'luck',
            'trigger_type' => 'on_enter'
        ], [
            'value' => 2,
            'message' => '✨ +2 Pontos de Sorte pelas informações obtidas com a aldeã!'
        ]);
        if ($node21->wasRecentlyCreated) {
            $this->line("Cadastrado efeito explícito para o Capítulo 21: +2 Sorte");
        }

        $nodes = StoryNode::all();
        $createdCount = 0;

        foreach ($nodes as $node) {
            $text = strip_tags($node->history);

            // Buscar somas: "Some X pontos de SORTE/ENERGIA/HABILIDADE" ou "ganhe X pontos de..."
            if (preg_match_all('/(?:some|ganhe|adicione|recupere|ganhando)\s+(\d+)\s+(?:pontos?\s+de\s+)?(sorte|energia|habilidade)/ui', $text, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $val = (int) $match[1];
                    $attrName = mb_strtolower($match[2]);
                    $attr = match ($attrName) {
                        'sorte' => 'luck',
                        'energia' => 'energy',
                        'habilidade' => 'skill',
                        default => null
                    };

                    if ($attr) {
                        $effect = NodeEffect::firstOrCreate([
                            'story_node_id' => $node->id,
                            'attribute' => $attr,
                            'trigger_type' => 'on_enter'
                        ], [
                            'value' => $val,
                            'message' => "✨ +{$val} Pontos de " . mb_convert_case($attrName, MB_CASE_TITLE) . "!"
                        ]);
                        if ($effect->wasRecentlyCreated) {
                            $createdCount++;
                            $this->line("Capítulo {$node->id}: +{$val} de {$attrName}");
                        }
                    }
                }
            }

            // Buscar perdas: "perca X pontos de SORTE/ENERGIA/HABILIDADE"
            if (preg_match_all('/(?:perca|subtraia|deduza|perde|perdendo)\s+(\d+)\s+(?:pontos?\s+de\s+)?(sorte|energia|habilidade)/ui', $text, $matches, PREG_SET_ORDER)) {
                foreach ($matches as $match) {
                    $val = -((int) $match[1]);
                    $attrName = mb_strtolower($match[2]);
                    $attr = match ($attrName) {
                        'sorte' => 'luck',
                        'energia' => 'energy',
                        'habilidade' => 'skill',
                        default => null
                    };

                    if ($attr) {
                        $effect = NodeEffect::firstOrCreate([
                            'story_node_id' => $node->id,
                            'attribute' => $attr,
                            'trigger_type' => 'on_enter'
                        ], [
                            'value' => $val,
                            'message' => "⚠️ {$val} Pontos de " . mb_convert_case($attrName, MB_CASE_TITLE) . "!"
                        ]);
                        if ($effect->wasRecentlyCreated) {
                            $createdCount++;
                            $this->line("Capítulo {$node->id}: {$val} de {$attrName}");
                        }
                    }
                }
            }
        }

        $this->info("Mapeamento de efeitos concluído! {$createdCount} novos efeitos automáticos cadastrados.");
        return 0;
    }
}
