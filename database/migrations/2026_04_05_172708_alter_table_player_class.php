<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->enum('class', ['Mingy', 'The Weak', 'Vitruvian', 'Pilgrim', 'Swashbuckler', 'Paladin', 'Artificer', 'Xamã', 'Monk', 'Rogue', 'Mage', 'Druid', 'Cleric', 'Sorcerer', 'Warrior', 'Barbarian', 'Ranger', 'Wizard'])->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('class');
        });
    }
};
