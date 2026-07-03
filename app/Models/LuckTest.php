<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LuckTest extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'story_node_id',
        'success_go_to',
        'success_message',
        'fail_go_to',
        'fail_message',
    ];

    public function storyNode(): BelongsTo
    {
        return $this->belongsTo(StoryNode::class, 'story_node_id');
    }

    public function successStoryNode(): BelongsTo
    {
        return $this->belongsTo(StoryNode::class, 'success_go_to');
    }

    public function failStoryNode(): BelongsTo
    {
        return $this->belongsTo(StoryNode::class, 'fail_go_to');
    }
}
