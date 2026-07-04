<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Logic\BattleEngine;

class BattleController extends Controller
{
    /**
     * Processa a próxima rodada de ataque do combate.
     */
    public function attack(Request $request, int $character_id)
    {
        $user = $request->user();
        $character = $user->character()->findOrFail($character_id);

        $engine = new BattleEngine();
        $battleState = $engine->getOrInitializeBattle($character, $character->storyNode);

        if (!$battleState) {
            return redirect()->route('game', ['id' => $character->id]);
        }

        $engine->nextRound($character, $battleState);

        return redirect()->route('game', ['id' => $character->id]);
    }

    /**
     * Processa a decisão de testar ou não a sorte após um golpe em combate.
     */
    public function luck(Request $request, int $character_id)
    {
        $user = $request->user();
        $character = $user->character()->findOrFail($character_id);

        $useLuck = $request->boolean('use_luck', true);

        $engine = new BattleEngine();
        $battleState = $engine->getOrInitializeBattle($character, $character->storyNode);

        if (!$battleState) {
            return redirect()->route('game', ['id' => $character->id]);
        }

        $engine->testLuckInBattle($character, $battleState, $useLuck);

        return redirect()->route('game', ['id' => $character->id]);
    }

    /**
     * Tenta fugir do combate atual caso as regras da seção permitam.
     */
    public function flee(Request $request, int $character_id)
    {
        $user = $request->user();
        $character = $user->character()->findOrFail($character_id);

        $engine = new BattleEngine();
        $battleState = $engine->getOrInitializeBattle($character, $character->storyNode);

        if (!$battleState) {
            return redirect()->route('game', ['id' => $character->id]);
        }

        $res = $engine->fleeBattle($character, $battleState);

        if (!$res['success']) {
            return redirect()->route('game', ['id' => $character->id])->with('error_message', $res['message']);
        }

        return redirect()->route('game', ['id' => $character->id])->with('flee_message', $res['message']);
    }
}
