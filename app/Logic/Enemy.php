<?php

namespace App\Logic;

class Enemy extends Fighter {
    protected string $name;

    public function __construct(string $name, int $skill, int $energy) {
        $this->name = $name;
        $this->skillCurrent = $skill;
        $this->energyCurrent = $energy;
    }
}
