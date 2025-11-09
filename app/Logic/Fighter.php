<?php

namespace App\Logic;

abstract class Fighter {
    protected int $energyCurrent;
    protected int $skillCurrent;

    public function getEnergyCurrent():int  {return $this->energyCurrent;}

    public function getSkillCurrent():int {return $this->skillCurrent;}

    public function calculateAttackForce():int {
        return (rand(1, 6) + rand(1, 6) + $this->skillCurrent);
    }

    public function getDamaged(int $damage = 2):void {
        $this->energyCurrent -= $damage;
    }

    public function isAlive():bool {
        return $this->energyCurrent > 0;
    }
}
