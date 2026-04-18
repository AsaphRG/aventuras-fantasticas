<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CharacterController;
use App\Http\Controllers\EnchantmentController;
use App\Http\Controllers\GameController;
use Illuminate\Support\Env;

Route::get('/', [HomeController::class, 'home'])->name('home');
Route::get('/adventure_choice', [GameController::class, 'adventureChoice'])->name('adventure_choice')->middleware('auth');
Route::get('/new_character', [CharacterController::class, 'newCharacter'])->name('new_character')->middleware('auth');
Route::get('/game/{id}', [GameController::class, 'game'])->name('game')->middleware('auth');
Route::get('/game/{id}/enchantment_choice', [EnchantmentController::class, 'enchantmentChoice'])->name('enchantment_choice')->middleware('auth');
Route::post('/game/{id}/save_enchantments', [EnchantmentController::class, 'enchantmentAttribution'])->name('save_enchantments')->middleware('auth');
Route::get('/nextChap/{id}', [GameController::class, 'nextChap'])->name('nextChap')->middleware('auth');

Route::get('/dashboard', function () {
    return view('dashboard', ['totalGames' => 0, 'totalWins' => 0, 'totalDeaths' => 0, 'winRate' => 0, 'heroes' => []]);
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
