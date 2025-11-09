<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\StoryNode;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('choices', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(StoryNode::class, 'from_story_node_id')->nullable()->constrained()->onDelete('set null');
            $table->text('choice_description');
            $table->foreignIdFor(StoryNode::class, 'to_story_node_id')->nullable()->constrained()->onDelete('set null');
            $table->string('required_flag', 255)->nullable();
            $table->string('set_flag', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('choices');
    }
};
