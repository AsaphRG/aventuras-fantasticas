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
            $table->renameColumn('ability_bonus', 'skill_bonus');
            $table->integer('bonus_value')->nullable()->after('skill_bonus');
            $table->dropColumn('bonus_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->renameColumn('skill_bonus', 'ability_bonus');
            $table->dropColumn('bonus_value');
            $table->string('bonus_type', 255)->nullable()->after('description')->default(null);
        });
    }
};
