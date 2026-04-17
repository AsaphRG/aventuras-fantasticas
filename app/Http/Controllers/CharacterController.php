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

        return redirect()->route('enchantment_choice', ['id' => $newCharacter->id]);
    }
}
