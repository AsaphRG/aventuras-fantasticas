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
        Schema::create('luck_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(StoryNode::class, 'story_node_id')->constrained()->onDelete('cascade');
            $table->foreignIdFor(StoryNode::class, 'success_go_to')->constrained('story_nodes')->onDelete('cascade');
            $table->text('success_message');
            $table->foreignIdFor(StoryNode::class, 'fail_go_to')->constrained('story_nodes')->onDelete('cascade');
            $table->text('fail_message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('luck_tests');
    }
};
