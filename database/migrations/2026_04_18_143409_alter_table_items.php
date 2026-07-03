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
        Schema::table('items', function (Blueprint $table) {
            $table->string('bonus_type', 255)->nullable()->after('description')->default(null);
            $table->renameColumn('abilityBonus', 'ability_bonus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->renameColumn('ability_bonus', 'abilityBonus');
            $table->dropColumn('bonus_type');
        });
    }
};
