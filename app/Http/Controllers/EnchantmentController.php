<?php

namespace App\Http\Controllers;

use App\Models\Enchantment;
use Illuminate\Http\Request;

class EnchantmentController extends Controller
{
    public function enchantmentChoice(Request $request, int $character_id) {
        $enchantments = Enchantment::all();
        $data = [
            "enchantments" => $enchantments
        ];
        return view('enchantmentsChoices', $data = $data);
    }

    public function enchantmentAtribution(Request $request, int $character_id) {

        return redirect()->route('game', ['id' => $character_id]);
    }
}
