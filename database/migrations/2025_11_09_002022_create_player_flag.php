<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Player;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('player_flag', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Player::class, 'player_id')->constrained()->onDelete('cascade');
            $table->string('flag_name', 255);
            $table->timestamps();
            $table->unique(['player_id', 'flag_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_flag');
    }
};
