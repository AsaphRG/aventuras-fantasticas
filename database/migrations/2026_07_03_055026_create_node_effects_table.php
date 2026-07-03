<?php

use App\Models\StoryNode;
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
        Schema::create('node_effects', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(StoryNode::class, 'story_node_id')->constrained('story_nodes')->onDelete('cascade');
            $table->string('attribute', 50);
            $table->integer('value');
            $table->string('message', 500)->nullable();
            $table->string('trigger_type', 50)->default('on_enter');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('node_effects');
    }
};
