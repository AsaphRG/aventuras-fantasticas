<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Player extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'skillStart',
        'skillCurrent',
        'energyStart',
        'energyCurrent',
        'luckStart',
        'luckCurrent',
        'enchantmentStart',
        'gold',
        'currentStoryNode',
        'win',
        'dead'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PlayerItems::class, 'player_id');
    }

    public function enchantments(): HasMany
    {
        return $this->hasMany(PlayerEnchantments::class);
    }

    public function storyNode(): BelongsTo
    {
        return $this->belongsTo(StoryNode::class, 'currentStoryNode', 'id');
    }

    public function flags(): HasMany
    {
        return $this->hasMany(PlayerFlag::class);
    }

    public function playerStoryNode(): HasMany
    {
        return $this->hasMany(PlayerStoryNode::class);
    }

    /**
     * Sincroniza as magias não utilizadas e itens do personagem com a tabela player_flag.
     * Isso é essencial para garantir que personagens já criados antes da implementação de flags tenham seus registros.
     */
    public function syncFlags(): void
    {
        $enchantments = $this->enchantments()
            ->where('used', false)
            ->with('enchantment')
            ->get()
            ->map(function ($pe) {
                return $pe->enchantment ? $pe->enchantment->name : null;
            })
            ->filter();

        $items = $this->items()
            ->pluck('name')
            ->filter();

        $allNames = $enchantments->merge($items)->unique();

        $insertData = [];
        $now = now();
        foreach ($allNames as $name) {
            $insertData[] = [
                'player_id' => $this->id,
                'flag_name' => $name,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (!empty($insertData)) {
            PlayerFlag::insertOrIgnore($insertData);
        }
    }
}
