<?php

namespace App\Http\Controllers;

use App\Models\Player as PlayerModel;
use App\Logic\Player as PlayerLogic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CharacterController extends Controller {
    public function newCharacter(Request $request) {
        $user = Auth::user();
        $stats = new PlayerLogic();

        $newCharacter = PlayerModel::create([
            'user_id' => $user->id,

            'skillStart' => $stats->getSkillStart(),
            'skillCurrent' => $stats->getSkillCurrent(),
            'energyStart' => $stats->getEnergyStart(),
            'energyCurrent' => $stats->getEnergyCurrent(),
            'luckStart' => $stats->getLuckStart(),
            'luckCurrent' => $stats->getLuckCurrent(),
            'enchantmentStart' => $stats->getEnchantmentStart(),
            'gold' => $stats->getGold(),
            'currentStoryNode' => $stats->getCurrentStoryNode(),
            'win' => $stats->getWin(),
            'dead' => $stats->getDead(),
        ]);

        \App\Models\PlayerStoryNode::create([
            'player_id' => $newCharacter->id,
            'story_node_id' => $newCharacter->currentStoryNode,
        ]);

        return redirect()->route('enchantment_choice', ['id' => $newCharacter->id]);
    }

    public function show(Request $request, int $id) {
        $character = PlayerModel::with(['playerStoryNode.storyNode', 'enchantments.enchantment', 'items', 'storyNode'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $steps = $character->playerStoryNode;
        $choicesMade = [];

        for ($i = 0; $i < count($steps); $i++) {
            $stepId = $steps[$i]->id;
            $currentStoryNodeId = $steps[$i]->story_node_id;

            if ($i < count($steps) - 1) {
                $nextStoryNodeId = $steps[$i + 1]->story_node_id;

                if ($currentStoryNodeId == 0) {
                    $choicesMade[$stepId] = 'Início da aventura em direção à Cidadela do Caos';
                    continue;
                }

                $choice = \App\Models\Choice::where('from_story_node_id', $currentStoryNodeId)
                    ->where('to_story_node_id', $nextStoryNodeId)
                    ->first();

                if ($choice && $choice->choice_description) {
                    $choicesMade[$stepId] = $choice->choice_description;
                } else {
                    $choicesMade[$stepId] = 'Avançou para o capítulo ' . $nextStoryNodeId;
                }
            } else {
                if ($character->win) {
                    $choicesMade[$stepId] = '👑 Vitória e glória eterna!';
                } elseif ($character->dead) {
                    $choicesMade[$stepId] = '💀 O herói pereceu em sua jornada.';
                } else {
                    $choicesMade[$stepId] = '⏳ Aguardando próxima decisão...';
                }
            }
        }

        return view('character_history', [
            'character' => $character,
            'choicesMade' => $choicesMade,
        ]);
    }
}
