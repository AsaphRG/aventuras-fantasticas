<?php

namespace App\Http\Controllers;

use App\Models\StoryNode;
use Illuminate\Http\Request;
use App\Logic\Player as PlayerLogic;

class GameController extends Controller
{
    public function stories(Request $request) {
        $stories = StoryNode::all();

        return view('stories', ['stories' => $stories]);
    }

    public function game(Request $request, int $character_id) {
        $user = $request->user();

        $character = $user->character()->findOrFail($character_id);

        $playable_character = new PlayerLogic($character->skillStart, $character->skillCurrent, $character->energyStart, $character->energyCurrent, $character->luckStart, $character->luckCurrent, $character->enchantmentStart, $character->gold, $character->currentStoryNode, $character->id);

        $story = $character->currentStoryNode;

        $data = [
            'character' => $playable_character,
            'story' => $story
        ];

        return view('game', $data);
    }
}
