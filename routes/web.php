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
Route::get('/character/{id}', [CharacterController::class, 'show'])->name('character.show')->middleware('auth');
Route::get('/game/{id}', [GameController::class, 'game'])->name('game')->middleware('auth');
Route::get('/game/{id}/enchantment_choice', [EnchantmentController::class, 'enchantmentChoice'])->name('enchantment_choice')->middleware('auth');
Route::post('/game/{id}/save_enchantments', [EnchantmentController::class, 'enchantmentAttribution'])->name('save_enchantments')->middleware('auth');
Route::get('/nextChap/{id}', [GameController::class, 'nextChap'])->name('nextChap')->middleware('auth');
Route::post('/game/{id}/test-luck', [GameController::class, 'testLuck'])->name('game.test_luck')->middleware('auth');
Route::post('/game/{id}/cast-spell/{spell_id}', [GameController::class, 'castInstantSpell'])->name('game.cast_spell')->middleware('auth');
Route::post('/game/{id}/use-item/{item_id}', [GameController::class, 'useItem'])->name('game.use_item')->middleware('auth');

Route::get('/dashboard', [HomeController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
