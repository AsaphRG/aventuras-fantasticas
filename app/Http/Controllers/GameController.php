<?php

namespace App\Http\Controllers;

use App\Models\StoryNode;
use App\Models\Choice;
use Illuminate\Http\Request;
use App\Logic\Player as PlayerLogic;

class GameController extends Controller
{
    public function user_panel(Request $request) {
        $user = $request->user();

        $characters = $user->character->reverse();

        $playable_character = [];

        foreach($characters as $character) {
            if(!($character->win || $character->dead)) {
                $playable_character[] = new PlayerLogic($character->skillStart, $character->skillCurrent, $character->energyStart, $character->energyCurrent, $character->luckStart, $character->luckCurrent, $character->enchantmentStart, $character->gold, $character->currentStoryNode, $character->id, $character->win, $character->dead);
            }
        }

        return view('user_panel', [
            'user' => $user,
            'characters' => $playable_character
        ]);
    }

    public function game(Request $request, int $character_id) {
        $user = $request->user();

        $character = $user->character()->findOrFail($character_id);

        $playable_character = new PlayerLogic($character->skillStart, $character->skillCurrent, $character->energyStart, $character->energyCurrent, $character->luckStart, $character->luckCurrent, $character->enchantmentStart, $character->gold, $character->currentStoryNode, $character->id, $character->win, $character->dead);

        $character_flags = $character->flags->pluck('flag_name')->toArray();

        $story = $character->storyNode;

        $choices = $story->choices;

        $data = [
            'character' => $playable_character,
            'character_flags' => $character_flags,
            'story' => $story,
            'choices' => $choices,
        ];

        return view('game', $data);
    }

    public function nextChap(Request $request, int $character_id) {
        $user = $request->user();

        $character = $user->character()->findOrFail($character_id);

        $choice = Choice::findOrFail($request->choice_id);

        $character->currentStoryNode = $choice->to_story_node_id;
        $character->save();

        return redirect()->route('game', ['id' => $character->id]);
    }
}
