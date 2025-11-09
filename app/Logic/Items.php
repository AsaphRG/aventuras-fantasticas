<?php

abstract class Item {
    protected string $name;
    protected string $description;

    public function __construct(string $name, string $description) {
        $this->name = $name;
        $this->description = $description;
    }
}

class Consumable extends Item {}

class Mission extends Item {}

class Weapon extends Item {
    protected int $abilityBonus;

    public function __construct(string $name, string $description, int $abilityBonus) {
        parent::__construct($name, $description);
        $this->abilityBonus = $abilityBonus;
    }

    public function getAbility(): int {
        return $this->abilityBonus;
    }
}
