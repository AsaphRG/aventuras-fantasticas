<?php

namespace App\Http\Controllers;

use App\Models\StoryNode;
use App\Models\Choice;
use Illuminate\Http\Request;
use App\Logic\Player as PlayerLogic;
use App\Models\PlayerEnchantments;
use App\Models\PlayerStoryNode;
use App\Models\NodeEffect;

class GameController extends Controller
{
    public function adventureChoice(Request $request) {
        $user = $request->user();

        $characters = $user->character->reverse();

        $playable_character = [];

        foreach($characters as $character) {
            if(!($character->win || $character->dead)) {

                $enchantments = $character->enchantments;

                $playable_instance = new PlayerLogic($character->skillStart, $character->skillCurrent, $character->energyStart, $character->energyCurrent, $character->luckStart, $character->luckCurrent, $character->enchantmentStart, $character->gold, $character->currentStoryNode, $character->id, $character->win, $character->dead);

                $playable_instance->createGrimory($enchantments);

                $playable_character[] = $playable_instance;
            }
        }

        return view('adventure_choice', [
            'user' => $user,
            'characters' => $playable_character
        ]);
    }

    public function game(Request $request, int $character_id) {
        $user = $request->user();

        $character = $user->character()->findOrFail($character_id);

        $enchantments = $character->enchantments;

        if(count($enchantments) == 0) {
            return redirect()->route('enchantment_choice', ['id' => $character->id]);
        }

        $this->checkEndGameStatus($character);

        $playable_character = new PlayerLogic($character->skillStart, $character->skillCurrent, $character->energyStart, $character->energyCurrent, $character->luckStart, $character->luckCurrent, $character->enchantmentStart, $character->gold, $character->currentStoryNode, $character->id, $character->win, $character->dead);

        $playable_character->createGrimory($enchantments);

        // Sincroniza automaticamente flags de magias/itens para personagens já criados
        $character->syncFlags();
        $character_flags = $character->flags()->pluck('flag_name')->toArray();

        $story = $character->storyNode;

        $choices = $story->choices;

        $data = [
            'character' => $playable_character,
            'model_character' => $character,
            'character_flags' => $character_flags,
            'enchantments_list' => $character->enchantments()->with('enchantment')->get(),
            'story' => $story,
            'choices' => $choices,
        ];

        return view('game', $data);
    }

    public function nextChap(Request $request, int $character_id) {
        $user = $request->user();

        $character = $user->character()->findOrFail($character_id);

        $choice = Choice::findOrFail($request->choice_id);

        if (is_null($choice->to_story_node_id)) {
            return redirect()->route('adventure_choice');
        }

        if ($choice->required_flag) {
            $flagName = $choice->required_flag;

            // 1. Tenta consumir como Magia
            $spell = $character->enchantments()
                ->whereHas('enchantment', fn($q) => $q->where('name', $flagName))
                ->where('used', false)
                ->first();

            if ($spell) {
                $spell->update(['used' => true]);

                // Verifica se ainda restam cópias não usadas dessa magia
                $remaining = $character->enchantments()
                    ->whereHas('enchantment', fn($q) => $q->where('name', $flagName))
                    ->where('used', false)
                    ->count();

                if ($remaining === 0) {
                    $character->flags()->where('flag_name', $flagName)->delete();
                }

                session()->flash('spell_casted', "🪄 Você conjurou o feitiço: {$flagName}!");
            } else {
                // 2. Tenta consumir como Item (se não for magia)
                $item = $character->items()->where('name', $flagName)->first();
                if ($item && $item->category === 'Consumable') {
                    $item->delete();
                }
                // Exclui a flag de permissão
                $character->flags()->where('flag_name', $flagName)->delete();
            }
        }

        $character->currentStoryNode = $choice->to_story_node_id;
        $this->applyNodeEffects($character, $choice->to_story_node_id);
        $this->checkEndGameStatus($character);
        $character->save();

        PlayerStoryNode::create([
            'player_id' => $character->id,
            'story_node_id' => $choice->to_story_node_id
        ]);

        return redirect()->route('game', ['id' => $character->id]);
    }

    public function castInstantSpell(Request $request, int $character_id, int $spell_id) {
        $user = $request->user();
        $character = $user->character()->findOrFail($character_id);

        $spell = $character->enchantments()->with('enchantment')->findOrFail($spell_id);

        if ($spell->used) {
            return redirect()->route('game', ['id' => $character->id])->with('error_message', 'Esta magia já foi utilizada!');
        }

        $enchantmentId = $spell->enchantment_id;
        $spellName = $spell->enchantment->name;
        $message = '';

        $playable_character = new PlayerLogic($character->skillStart, $character->skillCurrent, $character->energyStart, $character->energyCurrent, $character->luckStart, $character->luckCurrent, $character->enchantmentStart, $character->gold, $character->currentStoryNode, $character->id, $character->win, $character->dead);

        if ($enchantmentId == 7) { // Sorte
            $oldVal = $playable_character->getLuckCurrent();
            $playable_character->increaseLuck((int) ceil($character->luckStart / 2));
            $actualGain = $playable_character->getLuckCurrent() - $oldVal;
            $message = "✨ Feitiço da Sorte conjurado! Você recuperou +{$actualGain} de Sorte!";
        } elseif ($enchantmentId == 9) { // Habilidade
            $oldVal = $playable_character->getSkillCurrent();
            $playable_character->increaseSkill((int) ceil($character->skillStart / 2));
            $actualGain = $playable_character->getSkillCurrent() - $oldVal;
            $message = "✨ Feitiço de Habilidade conjurado! Você recuperou +{$actualGain} de Habilidade!";
        } elseif ($enchantmentId == 10) { // Energia
            $oldVal = $playable_character->getEnergyCurrent();
            $playable_character->increaseEnergy((int) ceil($character->energyStart / 2));
            $actualGain = $playable_character->getEnergyCurrent() - $oldVal;
            $message = "✨ Feitiço de Energia conjurado! Você recuperou +{$actualGain} de Energia!";
        } else {
            return redirect()->route('game', ['id' => $character->id])->with('error_message', 'Este feitiço só pode ser usado em momentos específicos da história!');
        }

        $playable_character->syncToModel($character);
        $character->save();
        $spell->update(['used' => true]);

        // Verifica se restam cópias não usadas dessa magia
        $remaining = $character->enchantments()
            ->where('enchantment_id', $enchantmentId)
            ->where('used', false)
            ->count();

        if ($remaining === 0) {
            $character->flags()->where('flag_name', $spellName)->delete();
        }

        return redirect()->route('game', ['id' => $character->id])->with('spell_casted', $message);
    }

    public function testLuck(Request $request, int $character_id) {
        $user = $request->user();

        $character = $user->character()->findOrFail($character_id);
        $luckTest = $character->storyNode->luckTest;

        if (!$luckTest) {
            return redirect()->route('game', ['id' => $character->id]);
        }

        $playable_character = new PlayerLogic($character->skillStart, $character->skillCurrent, $character->energyStart, $character->energyCurrent, $character->luckStart, $character->luckCurrent, $character->enchantmentStart, $character->gold, $character->currentStoryNode, $character->id, $character->win, $character->dead);

        $isLucky = $playable_character->testLuck();
        $character->luckCurrent = $playable_character->getLuckCurrent();

        $targetNode = $isLucky ? $luckTest->success_go_to : $luckTest->fail_go_to;
        $message = $isLucky ? $luckTest->success_message : $luckTest->fail_message;

        $character->currentStoryNode = $targetNode;
        $this->applyNodeEffects($character, $targetNode);
        $this->checkEndGameStatus($character);
        $character->save();

        PlayerStoryNode::create([
            'player_id' => $character->id,
            'story_node_id' => $targetNode
        ]);

        return redirect()->route('game', ['id' => $character->id])
                         ->with('luck_result', $isLucky ? 'success' : 'failure')
                         ->with('luck_message', $message);
    }

    private function applyNodeEffects($character, int $targetNodeId): void {
        $effects = NodeEffect::where('story_node_id', $targetNodeId)
            ->where('trigger_type', 'on_enter')
            ->get();

        if ($effects->isEmpty()) {
            return;
        }

        $playable_character = new PlayerLogic(
            $character->skillStart, $character->skillCurrent,
            $character->energyStart, $character->energyCurrent,
            $character->luckStart, $character->luckCurrent,
            $character->enchantmentStart, $character->gold,
            $targetNodeId, $character->id, $character->win, $character->dead
        );

        $messages = [];
        foreach ($effects as $effect) {
            $playable_character->applyStatChange($effect->attribute, $effect->value);
            if ($effect->message) {
                $messages[] = $effect->message;
            }
        }

        $playable_character->syncToModel($character);

        if (!empty($messages)) {
            session()->flash('node_effects', $messages);
        }
    }

    private function checkEndGameStatus($character): void {
        $dirty = false;
        if ($character->currentStoryNode == 402 || $character->energyCurrent <= 0) {
            if (!$character->dead) {
                $character->dead = true;
                $dirty = true;
            }
        } elseif ($character->currentStoryNode == 400) {
            if (!$character->win) {
                $character->win = true;
                $dirty = true;
            }
        }
        if ($dirty) {
            $character->save();
        }
    }
}

