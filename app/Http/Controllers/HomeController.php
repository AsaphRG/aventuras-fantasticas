<?php

namespace App\Http\Controllers;

use App\Models\Player as PlayerModel;
use App\Logic\Player as PlayerLogic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(Request $request) {
        return view('home');
    }

    public function dashboard(Request $request) {
        $user = $request->user();
        $heroes = $user->character()
            ->with(['storyNode', 'enchantments.enchantment', 'items'])
            ->where(function ($query) {
                $query->where('win', true)->orWhere('dead', true);
            })
            ->orderBy('updated_at', 'desc')
            ->get();

        $totalGames = $heroes->count();
        $totalWins = $heroes->where('win', true)->count();
        $totalDeaths = $heroes->where('dead', true)->count();
        $winRate = $totalGames > 0 ? round(($totalWins / $totalGames) * 100) : 0;

        return view('dashboard', [
            'totalGames' => $totalGames,
            'totalWins' => $totalWins,
            'totalDeaths' => $totalDeaths,
            'winRate' => $winRate,
            'heroes' => $heroes,
        ]);
    }
}
