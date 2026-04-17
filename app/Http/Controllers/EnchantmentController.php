<?php

namespace App\Http\Controllers;

use App\Models\Enchantment;
use App\Models\PlayerEnchantments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class EnchantmentController extends Controller
{
    public function enchantmentChoice(Request $request, int $character_id) {
        $enchantments = Enchantment::all();
        $current_character = $request->user()->character()->findOrFail($character_id);
        $number_of_enchantments = $current_character->enchantmentStart;
        $data = [
            "enchantments_limit" => $number_of_enchantments,
            "enchantments" => $enchantments
        ];
        return view('enchantmentsChoices', $data = $data);
    }

    // public function enchantmentAttribution(Request $request, int $character_id) {
    public function enchantmentAttribution(Request $request, int $character_id) {
        $current_character = $request->user()->character()->findOrFail($character_id);
        $enchantments = $request->enchantments;
        $insert_enchantments = [];
        foreach ($enchantments as $enchantment_id => $quantity) {
            for ($i = 0; $i < $quantity; $i++) {
                $insert_enchantments[] = [
                    'player_id' => $current_character->id,
                    'enchantment_id' => $enchantment_id
                ];
            }
        }
        PlayerEnchantments::insert($insert_enchantments);

        return Redirect::route('game', ['id' => $current_character->id]);
    }
}
