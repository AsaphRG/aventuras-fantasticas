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
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'status' => 'ended']);
            }
            return redirect()->route('game', ['id' => $character->id]);
        }

        $res = $engine->nextRound($character, $battleState);

        if ($request->wantsJson()) {
            return $this->jsonResponse($character, $battleState, $res);
        }

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
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'status' => 'ended']);
            }
            return redirect()->route('game', ['id' => $character->id]);
        }

        $res = $engine->testLuckInBattle($character, $battleState, $useLuck);

        if ($request->wantsJson()) {
            return $this->jsonResponse($character, $battleState, $res);
        }

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
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'status' => 'ended']);
            }
            return redirect()->route('game', ['id' => $character->id]);
        }

        $res = $engine->fleeBattle($character, $battleState);

        if ($request->wantsJson()) {
            return $this->jsonResponse($character, $battleState, $res);
        }

        if (!$res['success']) {
            return redirect()->route('game', ['id' => $character->id])->with('error_message', $res['message']);
        }

        return redirect()->route('game', ['id' => $character->id])->with('flee_message', $res['message']);
    }

    /**
     * Monta a resposta JSON com o estado atualizado do combate para o Alpine.js.
     */
    private function jsonResponse($character, $battleState, array $res = [])
    {
        $character->refresh();
        $battleState->refresh();

        $logData = json_decode($battleState->last_round_log, true) ?: [];
        $msg = $res['message'] ?? ($logData['message'] ?? '');

        return response()->json([
            'success' => $res['success'] ?? true,
            'status' => $battleState->status,
            'round_number' => $battleState->round_number,
            'message' => $msg,
            'luck_test_context' => $battleState->luck_test_context,
            'player' => [
                'energy_current' => (int) $character->energyCurrent,
                'energy_start' => (int) $character->energyStart,
                'skill_current' => (int) $character->skillCurrent,
                'luck_current' => (int) $character->luckCurrent,
                'dead' => (bool) $character->dead,
            ],
            'enemy' => [
                'id' => (int) $battleState->enemy_id,
                'name' => $battleState->enemy ? $battleState->enemy->name : '',
                'ability' => (int) $battleState->enemy_current_ability,
                'energy_current' => (int) $battleState->enemy_current_energy,
                'energy_max' => $battleState->enemy ? (int) $battleState->enemy->energy : 1,
            ]
        ]);
    }
}
