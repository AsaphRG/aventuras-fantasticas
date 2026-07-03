<?php

namespace App\Http\Controllers;

use App\Models\Enchantment;
use App\Models\PlayerEnchantments;
use App\Models\PlayerFlag;
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

    public function enchantmentAttribution(Request $request, int $character_id) {
        $current_character = $request->user()->character()->findOrFail($character_id);
        $enchantments = $request->enchantments ?? [];
        $insert_enchantments = [];
        $selected_ids = [];

        foreach ($enchantments as $enchantment_id => $quantity) {
            if ($quantity > 0) {
                $selected_ids[] = $enchantment_id;
                for ($i = 0; $i < $quantity; $i++) {
                    $insert_enchantments[] = [
                        'player_id' => $current_character->id,
                        'enchantment_id' => $enchantment_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        if (!empty($insert_enchantments)) {
            PlayerEnchantments::insert($insert_enchantments);
        }

        // Sincronizar feitiços com a tabela de flags de narrativa do jogador
        if (!empty($selected_ids)) {
            $enchantmentNames = Enchantment::whereIn('id', $selected_ids)->pluck('name');
            $insert_flags = [];
            foreach ($enchantmentNames as $name) {
                $insert_flags[] = [
                    'player_id' => $current_character->id,
                    'flag_name' => $name,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            if (!empty($insert_flags)) {
                PlayerFlag::insertOrIgnore($insert_flags);
            }
        }

        return Redirect::route('game', ['id' => $current_character->id]);
    }
}
