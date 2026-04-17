<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Enchantment;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('player_enchantments', function (Blueprint $table) {
            $table->dropColumn('name', 'description');
            $table->foreignIdFor(Enchantment::class, 'enchantment_id')->constrained()->noActionOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('player_enchantments', function (Blueprint $table) {
            //
        });
    }
};
