@extends('layouts.game')

@section('title', 'Escolha seu Herói')

@section('header-nav')
    @if (Route::has('login'))
        @auth
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-slate-400 bg-slate-900 hover:text-amber-500 hover:bg-slate-800 focus:outline-none transition ease-in-out duration-150 font-cinzel tracking-wide border-slate-700 hover:border-amber-500/50">
                                <div>{{ Auth::user()->name }}</div>

                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('dashboard')" class="text-slate-300 hover:bg-slate-700 hover:text-amber-500">
                                {{ __('Salão dos Heróis') }}
                            </x-dropdown-link>

                            <x-dropdown-link :href="route('profile.edit')" class="text-slate-300 hover:bg-slate-700 hover:text-amber-500">
                                {{ __('Perfil') }}
                            </x-dropdown-link>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')"
                                        class="text-slate-300 hover:bg-slate-700 hover:text-amber-500"
                                        onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                    {{ __('Sair') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>

                <div class="-me-2 flex items-center sm:hidden">
                    <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-amber-500 hover:bg-slate-800 focus:outline-none focus:bg-slate-800 focus:text-amber-500 transition duration-150 ease-in-out">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-900 border-b border-slate-800">
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                    class="text-slate-300 hover:text-amber-500 hover:bg-slate-800 hover:border-amber-500 font-cinzel">
                    {{ __('Salão dos Heróis') }}
                </x-responsive-nav-link>
            </div>

            <div class="pt-4 pb-1 border-t border-slate-700">
                <div class="px-4">
                    <div class="font-medium text-base text-amber-500 font-cinzel">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>
                </div>

                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')" class="text-slate-400 hover:text-amber-500 hover:bg-slate-800">
                        {{ __('Perfil') }}
                    </x-responsive-nav-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-responsive-nav-link :href="route('logout')"
                                class="text-slate-400 hover:text-amber-500 hover:bg-slate-800"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Sair') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="text-slate-400 hover:text-amber-500 transition">Log in</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="rpg-btn">Register</a>
            @endif
        @endauth
    @endif
@endsection

@section('content')
    <div class="bg-amber-900/20 border-l-4 border-amber-500 text-amber-200 p-4 mb-8 rounded-r shadow-md" role="alert">
        <p class="font-bold text-sm uppercase tracking-wide">Modo de Desenvolvimento</p>
        <p class="text-sm">Este é um projeto focado em aprendizado e não tem finalidades comerciais.</p>
    </div>

    <div class="flex justify-between items-end mb-8 border-b border-slate-800 pb-4">
        <div>
            <h1 class="font-cinzel text-3xl md:text-4xl text-white drop-shadow-md">Seus Heróis</h1>
            <p class="text-slate-400 text-sm mt-1">Escolha seu destino e continue sua jornada.</p>
        </div>
        <a href="{{route('new_character')}}" class="rpg-btn-primary">
            + Novo Personagem
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($characters as $character)
            @php
                // Cálculos de Porcentagem
                $skillPct = ($character->getSkillStart() - 7) / (12 - 7);
                $energyPct = ($character->getEnergyStart() - 14) / (24 - 14);
                $luckPct = ($character->getLuckStart() - 7) / (12 - 7);
                $enchantmentPct = ($character->getEnchantmentStart() - 8) / (18 - 8);

                // Lógica de Imagem e Classe (Movida para cá para limpar o HTML)
                $img = 'Mingy.png'; // Fallback
                $className = 'Desconhecido';

                if ($character->getGold() >= 100) { $img = 'Mingy.png'; $className = 'Rico'; }
                elseif ($skillPct == 0 && $energyPct == 0 && $luckPct == 0 && $enchantmentPct == 0) { $img = 'The Weak.png'; $className = 'Fraco'; }
                elseif ($skillPct == 1 && $energyPct == 1 && $luckPct == 1 && $enchantmentPct == 1) { $img = 'Vitruvian.png'; $className = 'Perfeito'; }
                elseif ($skillPct == $energyPct && $skillPct == $luckPct && $skillPct == $enchantmentPct) { $img = 'Pilgrim.png'; $className = 'Peregrino'; }
                elseif ($skillPct == $energyPct && $skillPct == $luckPct && $skillPct > $enchantmentPct) { $img = 'Swashbuckler.png'; $className = 'Espadachim'; }
                elseif ($skillPct == $energyPct && $skillPct == $enchantmentPct && $skillPct > $luckPct) { $img = 'Paladin.png'; $className = 'Paladino'; }
                elseif ($skillPct > $energyPct && $skillPct == $luckPct && $skillPct == $enchantmentPct) { $img = 'Artificer.png'; $className = 'Artífice'; }
                elseif ($skillPct < $energyPct && $energyPct == $luckPct && $energyPct == $enchantmentPct) { $img = 'Xamã.png'; $className = 'Xamã'; }
                elseif ($skillPct == $energyPct && $skillPct > $luckPct && $skillPct > $enchantmentPct) { $img = 'Monk.png'; $className = 'Monge'; }
                elseif ($skillPct == $luckPct && $skillPct > $energyPct && $skillPct > $enchantmentPct) { $img = 'Rogue.png'; $className = 'Ladino'; }
                elseif ($skillPct == $enchantmentPct && $skillPct > $energyPct && $skillPct > $luckPct) { $img = 'Mage.png'; $className = 'Mago'; }
                elseif ($energyPct == $luckPct && $energyPct > $skillPct && $energyPct > $enchantmentPct) { $img = 'Druid.png'; $className = 'Druida'; }
                elseif ($energyPct == $enchantmentPct && $energyPct > $skillPct && $energyPct > $luckPct) { $img = 'Cleric.png'; $className = 'Clérigo'; }
                elseif ($luckPct == $enchantmentPct && $luckPct > $skillPct && $luckPct > $energyPct) { $img = 'Sorcerer.png'; $className = 'Feiticeiro'; }
                elseif ($skillPct > $energyPct && $skillPct > $luckPct && $skillPct > $enchantmentPct) { $img = 'Warrior.png'; $className = 'Guerreiro'; }
                elseif ($energyPct > $skillPct && $energyPct > $luckPct && $energyPct > $enchantmentPct) { $img = 'Barbarian.png'; $className = 'Bárbaro'; }
                elseif ($luckPct > $skillPct && $luckPct > $energyPct && $luckPct > $enchantmentPct) { $img = 'Ranger.png'; $className = 'Patrulheiro'; }
                elseif ($enchantmentPct > $skillPct && $enchantmentPct > $energyPct && $enchantmentPct > $luckPct) { $img = 'Wizard.png'; $className = 'Bruxo'; }


                $skillPct = $character->getSkillCurrent() / $character->getSkillStart();
                $energyPct = $character->getEnergyCurrent() / $character->getEnergyStart();
                $luckPct = $character->getLuckCurrent() / $character->getLuckStart();
                $enchantmentPct = $character->getEnchantmentCurrent() / $character->getEnchantmentStart();
            @endphp

            <div class="group relative bg-slate-800 rounded-xl overflow-hidden border border-slate-700 hover:border-amber-500 transition-all duration-300 hover:shadow-xl hover:shadow-amber-900/20 flex flex-col">

                <div class="relative h-48 bg-slate-950 overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 to-transparent z-10"></div>
                    <img src="{{ asset('images/' . $img) }}" alt="{{ $className }}" class="w-full h-full object-cover object-top opacity-80 group-hover:opacity-100 group-hover:scale-105 transition-all duration-500">
                    <div class="absolute bottom-2 left-4 z-20">
                        <span class="text-xs text-amber-400 font-bold tracking-widest uppercase mb-1 block">{{ $className }}</span>
                        <h2 class="text-xl font-bold text-white font-cinzel">ID: {{ $character->getId() }}</h2>
                    </div>
                </div>

                <div class="p-5 flex-grow flex flex-col gap-4">

                    <div class="grid grid-cols-2 gap-x-4 gap-y-3 text-sm">
                        <div class="stat-group">
                            <div class="flex justify-between mb-1">
                                <span class="text-slate-400">Habilidade</span>
                                <span class="text-slate-200 font-mono">{{$character->getSkillCurrent()}}</span>
                            </div>
                            <div class="h-1.5 bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-full bg-blue-500" style="width: {{ $skillPct * 100 }}%"></div>
                            </div>
                        </div>

                        <div class="stat-group">
                            <div class="flex justify-between mb-1">
                                <span class="text-slate-400">Energia</span>
                                <span class="text-slate-200 font-mono">{{$character->getEnergyCurrent()}}</span>
                            </div>
                            <div class="h-1.5 bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-full bg-red-500" style="width: {{ $energyPct * 100 }}%"></div>
                            </div>
                        </div>

                        <div class="stat-group">
                            <div class="flex justify-between mb-1">
                                <span class="text-slate-400">Sorte</span>
                                <span class="text-slate-200 font-mono">{{$character->getLuckCurrent()}}</span>
                            </div>
                            <div class="h-1.5 bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-full bg-green-500" style="width: {{ $luckPct * 100 }}%"></div>
                            </div>
                        </div>

                        <div class="stat-group">
                            <div class="flex justify-between mb-1">
                                <span class="text-slate-400">Magias</span>
                                <span class="text-slate-200 font-mono">{{$character->getEnchantmentStart()}}</span>
                            </div>
                            <div class="h-1.5 bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-full bg-purple-500" style="width: {{ $enchantmentPct * 100 }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-2 pt-3 border-t border-slate-700 grid grid-cols-1 gap-2 text-center">
                        <div class="bg-slate-900/50 rounded p-2 border border-slate-700/50">
                            <span class="block text-xs text-slate-500 uppercase">Ouro</span>
                            <span class="text-amber-400 font-bold">{{ $character->getGold() }}</span>
                        </div>
                        {{-- <div class="bg-slate-900/50 rounded p-2 border border-slate-700/50">
                            <span class="block text-xs text-slate-500 uppercase">Capítulo</span>
                            <span class="text-slate-300 font-bold">{{ $character->getCurrentStoryNode() }}</span>
                        </div> --}}
                    </div>
                </div>

                <form method="GET" action="{{ route('game', ['id'=>$character->getId()]) }}" class="p-4 pt-0">
                    @csrf
                    <button type="submit" class="w-full py-2 bg-slate-700 hover:bg-amber-600 text-white font-bold rounded transition-colors uppercase text-sm tracking-wider border border-transparent hover:border-amber-400">
                        Continuar Jornada
                    </button>
                </form>
            </div>
        @endforeach
    </div>
@endsection
