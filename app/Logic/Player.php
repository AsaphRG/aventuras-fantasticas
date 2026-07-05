<?php

namespace App\Logic;
use Illuminate\Support\Collection;

use function is_null;

class Player extends Fighter {
    protected int $gold;
    protected int $skillStart;
    protected int $energyStart;
    protected int $luckStart;
    protected int $luckCurrent;
    protected int $enchantmentStart;
    protected Collection $items;
    protected Collection $enchantments;
    protected int $currentStoryNode;
    protected mixed $id;
    protected bool $win;
    protected bool $dead;

    /**
     * Create a Player object.
     *
     * @param int|null $skillStart
     * @param int|null $skillCurrent
     * @param int|null $energyStart
     * @param int|null $energyCurrent
     * @param int|null $luckStart
     * @param int|null $luckCurrent
     * @param int|null $enchantment
     * @param int|null $gold
     * @param int $currentStoryNode
     */
    public function __construct(?int $skillStart = null, ?int $skillCurrent = null, ?int $energyStart = null, ?int $energyCurrent = null, ?int $luckStart = null, ?int $luckCurrent = null, ?int $enchantment = null, int $gold = 0, int $currentStoryNode = 401, mixed $id = null, bool $win = False, bool $dead = False) {
        $skillStart = $skillStart ?? (rand(1, 6) + 6);
        $skillCurrent = is_null($skillCurrent) ? $skillStart : $skillCurrent;
        $energyStart = $energyStart ?? (rand(1, 6) + rand(1, 6) + 12);
        $energyCurrent = is_null($energyCurrent) ? $energyStart : $energyCurrent;
        $luckStart = $luckStart ?? (rand(1, 6) + 6);
        $luckCurrent = is_null($luckCurrent) ? $luckStart : $luckCurrent;
        $enchantment = $enchantment ?? (rand(1, 6) + rand(1, 6) + 6);

        $this->skillStart = $skillStart;
        $this->skillCurrent = $skillCurrent;
        $this->energyStart = $energyStart;
        $this->energyCurrent = $energyCurrent;
        $this->luckStart = $luckStart;
        $this->luckCurrent = $luckCurrent;
        $this->enchantmentStart = $enchantment;
        $this->gold = $gold;
        $this->items = collect([]);
        $this->enchantments = collect([]);
        $this->currentStoryNode = $currentStoryNode;
        $this->id = $id;
        $this->win = $win;
        $this->dead = $dead;
    }

    public function getId():int {return $this->id;}

    public function getSkillStart():int {return $this->skillStart;}

    public function getEnergyStart():int {return $this->energyStart;}

    public function getLuckStart():int {return $this->luckStart;}
    public function getLuckCurrent():int {return $this->luckCurrent;}

    public function getEnchantmentStart():int {return $this->enchantmentStart;}
    public function getEnchantmentCurrent():int {return $this->enchantments->where('used', false)->count();}

    public function getGold():int {return $this->gold;}
    public function increaseGold(int $value):void {$this->gold += $value;}

    public function getCurrentStoryNode():int {return $this->currentStoryNode;}

    public function testLuck():bool {
        if((rand(1, 6) + rand(1, 6)) <= $this->luckCurrent) {
            $this->luckCurrent -= 1;
            return true;
        } else {
            $this->luckCurrent -= 1;
            return false;
        }
    }

    public function decreaseSkillCurrent(int $value):void {
        $this->skillCurrent -= $value;
    }

    public function increaseSkill(int $value):void {
        if(($this->skillCurrent + $value) >= $this->skillStart) {
            $this->skillCurrent = $this->skillStart;
        } else {
            $this->skillCurrent += $value;
        }
    }

    public function increaseEnergy(int $value):void {
        if(($this->energyCurrent + $value) >= $this->energyStart) {
            $this->energyCurrent = $this->energyStart;
        } else {
            $this->energyCurrent += $value;
        }
    }

    public function increaseLuck(int $value):void {
        if(($this->luckCurrent + $value) >= $this->luckStart) {
            $this->luckCurrent = $this->luckStart;
        } else {
            $this->luckCurrent += $value;
        }
    }

    public function createGrimory(Collection|null $enchantments):void {
        if(!is_null($enchantments)) {
            $this->enchantments = $enchantments;
        }
    }

    public function loadItems(Collection|null $items):void {
        if(!is_null($items)) {
            $this->items = $items;
        }
    }

    public function addItem($item):void {
        $this->items->push($item);
    }

    public function getSkillBonusFromItems(): int {
        $bonus = 0;
        foreach ($this->items as $item) {
            if (in_array($item->category, ['Weapon', 'Equipment', 'Passive'])) {
                if ($item->abilityBonus && str_starts_with($item->abilityBonus, 'Skill:')) {
                    $bonus += (int) substr($item->abilityBonus, 6);
                }
            }
        }
        return $bonus;
    }

    public function getEffectiveSkill(): int {
        return $this->skillCurrent + $this->getSkillBonusFromItems();
    }

    public function getLuckBonusFromItems(): int {
        $bonus = 0;
        foreach ($this->items as $item) {
            if (in_array($item->category, ['Weapon', 'Equipment', 'Passive'])) {
                if ($item->abilityBonus && str_starts_with($item->abilityBonus, 'Luck:')) {
                    $bonus += (int) substr($item->abilityBonus, 5);
                }
            }
        }
        return $bonus;
    }

    public function getEffectiveLuck(): int {
        return $this->luckCurrent + $this->getLuckBonusFromItems();
    }

    public function getWin():bool {return $this->win;}
    public function setWin(bool $status):void {$this->win = $status;}

    public function getDead():bool {return $this->dead;}
    public function setDead(bool $status):void {$this->dead = $status;}

    public function decreaseEnergyCurrent(int $value):void {
        $this->energyCurrent = max(0, $this->energyCurrent - $value);
    }

    public function decreaseLuckCurrent(int $value):void {
        $this->luckCurrent = max(0, $this->luckCurrent - $value);
    }

    public function decreaseGold(int $value):void {
        $this->gold = max(0, $this->gold - $value);
    }

    public function applyStatChange(string $attribute, int $value): void {
        switch ($attribute) {
            case 'luck':
                $value > 0 ? $this->increaseLuck($value) : $this->decreaseLuckCurrent(abs($value));
                break;
            case 'energy':
                $value > 0 ? $this->increaseEnergy($value) : $this->decreaseEnergyCurrent(abs($value));
                break;
            case 'skill':
                $value > 0 ? $this->increaseSkill($value) : $this->decreaseSkillCurrent(abs($value));
                break;
            case 'gold':
                $value > 0 ? $this->increaseGold($value) : $this->decreaseGold(abs($value));
                break;
            case 'dead':
                if ($value > 0 || $value === true || $value === '1') {
                    $this->setDead(true);
                }
                break;
            case 'win':
                if ($value > 0 || $value === true || $value === '1') {
                    $this->setWin(true);
                }
                break;
        }
    }

    public function syncToModel($character): void {
        $character->skillCurrent = $this->getSkillCurrent();
        $character->energyCurrent = $this->getEnergyCurrent();
        $character->luckCurrent = $this->getLuckCurrent();
        $character->gold = $this->getGold();
        $character->dead = $this->getDead();
        $character->win = $this->getWin();
    }
}
