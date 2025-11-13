<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        @vite(['resources/js/app.js', 'resources/css/main.css'])
        <title>{{ config('app.name', 'Laravel') }}</title>
    </head>
    <body>
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-end gap-4">
                    @auth
                        <a
                            href="{{ url('/dashboard') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal"
                        >
                            Dashboard
                        </a>
                    @else
                        <a
                            href="{{ route('login') }}"
                            class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal"
                        >
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a
                                href="{{ route('register') }}"
                                class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>
        <div class='alert'>Este é um projeto focado em aprendizado e não tem finalidades comerciais.</div>
        <a href="{{route('new_character')}}">Novo personagem</a>
        <h1>Personagens</h1>
        <ul>
            @foreach ($characters as $character)
                <form class="character" method="GET" action="{{ route('game', ['id'=>$character->getId()]) }}">
                    @csrf
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
                </form>
            @endforeach
        </ul>
    </body>
</html>
