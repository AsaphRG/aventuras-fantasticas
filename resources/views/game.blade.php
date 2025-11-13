<div class="character">
    <div class="left-side">
        @php
            $skill = ($character->getSkillStart() - 7) / (12 - 7);
            $energy = ($character->getEnergyStart() - 14) / (24 - 14);
            $luck = ($character->getLuckStart() - 7) / (12 - 7);
            $enchantment = ($character->getEnchantmentStart() - 8) / (18 - 8);
        @endphp
        <div>Personagem: {{$character->getId()}}</div>
        <div class="stats">
            <div class="stats-box">
                <div>{{$character->getSkillCurrent()}} - {{number_format($skill * 100, 0, ',', '.')."%"}}</div>
                <div>Habilidade</div>
            </div>
            <div class="stats-box">
                <div>{{$character->getEnergyCurrent()}} - {{number_format($energy * 100, 0, ',', '.')."%"}}</div>
                <div>Energia</div>
            </div>
            <div class="stats-box">
                <div>{{$character->getLuckCurrent()}} - {{number_format($luck * 100, 0, ',', '.')."%"}}</div>
                <div>Sorte</div>
            </div>
            <div class="stats-box">
                <div>{{$character->getEnchantmentStart()}} - {{number_format($enchantment * 100, 0, ',', '.')."%"}}</div>
                <div>Encantamento</div>
            </div class="stats-box">
            <div class="stats-box">
                <div>{{$character->getGold()}}</div>
                <div>Ouro</div>
            </div>
            <div class="stats-box">
                <div>{{$character->getCurrentStoryNode()}}</div>
                <div>Capítulo</div>
            </div>
        </div>
        <input type="hidden" name="char_id" value="{{$character->getId()}}">
        <input type="submit" value="Jogar">
    </div>
    <div class="right-side">
        <img src="
        @if ($character->getGold() >= 100)
            {{ asset('images/Mingy.png') }}
        @elseif ($skill == 0 && $energy == 0 && $luck == 0 && $enchantment == 0)
            {{ asset('images/The Weak.png') }}
        @elseif ($skill == 1 && $energy == 1 && $luck == 1 && $enchantment == 1)
            {{ asset('images/Vitruvian.png') }}
        @elseif ($skill == $energy && $skill == $luck && $skill == $enchantment)
            {{ asset('images/Pilgrim.png') }}
        @elseif ($skill == $energy && $skill == $luck && $skill > $enchantment)
            {{ asset('images/Swashbuckler.png') }}
        @elseif ($skill == $energy && $skill == $enchantment && $skill > $luck)
            {{ asset('images/Paladin.png') }}
        @elseif ($skill > $energy && $skill == $luck && $skill == $enchantment)
            {{ asset('images/Artificer.png') }}
        @elseif ($skill < $energy && $energy == $luck && $energy == $enchantment)
            {{ asset('images/Xamã.png') }}
        @elseif ($skill == $energy && $skill > $luck && $skill > $enchantment)
            {{ asset('images/Monk.png') }}
        @elseif ($skill == $luck && $skill > $energy && $skill > $enchantment)
            {{ asset('images/Rogue.png') }}
        @elseif ($skill == $enchantment && $skill > $energy && $skill > $luck)
            {{ asset('images/Mage.png') }}
        @elseif ($energy == $luck && $energy > $skill && $energy > $enchantment)
            {{ asset('images/Druid.png') }}
        @elseif ($energy == $enchantment && $energy > $skill && $energy > $luck)
            {{ asset('images/Cleric.png') }}
        @elseif ($luck == $enchantment && $luck > $skill && $luck > $energy)
            {{ asset('images/Sorcerer.png') }}
        @elseif ($skill > $energy && $skill > $luck && $skill > $enchantment)
            {{ asset('images/Warrior.png') }}
        @elseif ($energy > $skill && $energy > $luck && $energy > $enchantment)
            {{ asset('images/Barbarian.png') }}
        @elseif ($luck > $skill && $luck > $energy && $luck > $enchantment)
            {{ asset('images/Ranger.png') }}
        @elseif ($enchantment > $skill && $enchantment > $energy && $enchantment > $luck)
            {{ asset('images/Wizard.png') }}
        @endif
        " alt="">
    </div>
</div>
