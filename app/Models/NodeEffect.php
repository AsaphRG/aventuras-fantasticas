<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NodeEffect extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_node_id',
        'attribute',
        'value',
        'message',
        'trigger_type',
    ];

    public function storyNode(): BelongsTo
    {
        return $this->belongsTo(StoryNode::class);
    }
}
