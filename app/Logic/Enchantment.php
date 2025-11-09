<?php

class Enchantment {
    protected string $name;
    protected string $description;
    protected bool $used;

    public function __construct(string $name, string $description) {
        $this->name = $name;
        $this->description = $description;
        $this->used = false;
    }

    public function getName():string {return $this->name;}

    public function getDescription():string {return $this->description;}

    public function useEnchantment():bool {
        if($this->used) {
            return false;
        } else {
            $this->used = true;
            return true;
        }
    }
}
