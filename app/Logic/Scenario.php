<?php

use App\Logic\Player;
use App\Logic\Enemy;

class Seção {
    protected int $numero;
    protected string $texto;

    public function __construct(int $numero, string $texto) {
        $this->numero = $numero;
        $this->texto = $texto;
    }

    public function apresentar() {
        return $this->texto;
    }
}

class Batalha {
    protected Player $jogador;
    protected Enemy $combatente;


}

class Escolha {}

class Teste {}
