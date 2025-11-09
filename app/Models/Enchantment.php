<?php

namespace App\Models; // Corrigido de App.Models

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Enchantment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The players that have this enchantment.
     * Um 'Enchantment' (encantamento) pode pertencer a muitos 'Players' (jogadores).
     */
    public function players(): BelongsToMany
    {
        // Define a relação Muitos-para-Muitos
        // O Laravel vai procurar pela tabela 'enchantment_player'
        // Adicionamos withPivot para acessar a coluna 'used' na tabela-pivô
        return $this->belongsToMany(Player::class)->withPivot('used')->withTimestamps();
    }
}
