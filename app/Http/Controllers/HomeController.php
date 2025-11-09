<?php

namespace App\Http\Controllers;

use App\Models\Player as PlayerModel;
use App\Logic\Player as PlayerLogic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request) {

        $user = $request->user();

        $characters = $user->character;

        $playable_character = [];

        foreach($characters as $character) {
            $playable_character[] = new PlayerLogic($character->skillStart, $character->skillCurrent, $character->energyStart, $character->energyCurrent, $character->luckStart, $character->luckCurrent, $character->enchantmentStart, $character->gold, $character->currentChapter, $character->id);
        }

        return view('home', [
            'user' => $user,
            'characters' => $playable_character
        ]);
    }
}
