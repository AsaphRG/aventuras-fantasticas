<?php

namespace App\Logic;

use function PHPUnit\Framework\isNull;

class Player extends Fighter {
    protected int $gold;
    protected int $skillStart;
    protected int $energyStart;
    protected int $luckStart;
    protected int $luckCurrent;
    protected int $enchantmentStart;
    protected array $items;
    protected array $enchantments;
    protected int $currentChapter;
    protected mixed $id;

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
     * @param int|null $currentChapter
     */
    public function __construct(?int $skillStart = null, ?int $skillCurrent = null, ?int $energyStart = null, ?int $energyCurrent = null, ?int $luckStart = null, ?int $luckCurrent = null, ?int $enchantment = null, int $gold = 0, int $currentChapter = -1, mixed $id = null) {
        $skillStart = $skillStart ?? (rand(1, 6) + 6);
        $skillCurrent = isNull($skillCurrent) ? $skillStart : $skillCurrent;
        $energyStart = $energyStart ?? (rand(1, 6) + rand(1, 6) + 12);
        $energyCurrent = isNull($energyCurrent) ? $energyStart : $energyCurrent;
        $luckStart = $luckStart ?? (rand(1, 6) + 6);
        $luckCurrent = isNull($luckCurrent) ? $luckStart : $luckCurrent;
        $enchantment = $enchantment ?? (rand(1, 6) + rand(1, 6) + 6);

        $this->skillStart = $skillStart;
        $this->skillCurrent = $skillCurrent;
        $this->energyStart = $energyStart;
        $this->energyCurrent = $energyCurrent;
        $this->luckStart = $luckStart;
        $this->luckCurrent = $luckCurrent;
        $this->enchantmentStart = $enchantment;
        $this->gold = $gold;
        $this->items = [];
        $this->enchantments = [];
        $this->currentChapter = $currentChapter;
        $this->id = $id;
    }

    public function getId():int {return $this->id;}

    public function getSkillStart():int {return $this->skillStart;}

    public function getEnergyStart():int {return $this->energyStart;}

    public function getLuckStart():int {return $this->luckStart;}
    public function getLuckCurrent():int {return $this->luckCurrent;}

    public function getEnchantmentStart():int {return $this->enchantmentStart;}

    public function getGold():int {return $this->gold;}
    public function increaseGold(int $value):void {$this->gold += $value;}

    public function getCurrentChapter():int {return $this->currentChapter;}

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

    public function createGrimory(array $enchantments):void {
        if(empty($this->enchantments)) {
            $this->enchantments = $enchantments;
        }
    }

    public function addItem($item):void {
        $this->items[] = $item;
    }
}
