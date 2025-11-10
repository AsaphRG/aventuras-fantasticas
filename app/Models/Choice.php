<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Choice extends Model
{
    protected $fillable = [
        'from_story_node_id',
        'choice_description',
        'to_story_node_id',
        'required_flag',
        'set_flag',
    ];

    public function fromStoryNode(): BelongsTo
    {
        return $this->belongsTo(StoryNode::class, 'from_story_node_id');
    }

    public function toStoryNode(): BelongsTo
    {
        return $this->belongsTo(StoryNode::class, 'to_story_node_id');
    }
}
