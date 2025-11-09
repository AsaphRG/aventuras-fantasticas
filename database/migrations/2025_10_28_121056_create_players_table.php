<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Player;
use App\Models\User;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->constrained()->onDelete('cascade');
            $table->integer('skillStart');
            $table->integer('skillCurrent');
            $table->integer('energyStart');
            $table->integer('energyCurrent');
            $table->integer('luckStart');
            $table->integer('luckCurrent');
            $table->integer('enchantmentStart');
            $table->integer('gold');
            $table->integer('currentChapter');
            $table->timestamps();
        });

        Schema::create('player_items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Player::class, 'player_id')->constrained()->onDelete('cascade');
            $table->string('name', 255);
            $table->text('description');
            $table->integer('abilityBonus')->nullable();
            $table->enum('category', ['Consumable', 'Mission', 'Weapon']);
            $table->timestamps();
        });

        Schema::create('player_enchantments', function(Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Player::class, 'player_id')->constrained()->onDelete('cascade');
            $table->string('name', 255);
            $table->text('description');
            $table->boolean('used')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
        Schema::dropIfExists('enchantments');
        Schema::dropIfExists('players');
    }
};
