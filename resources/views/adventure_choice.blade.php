<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <title>{{ config('app.name', 'ÇALKSJFDA') }}</title>

    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-900 text-slate-200 font-sans antialiased min-h-screen flex flex-col">

    <header class="w-full bg-slate-950 border-b border-slate-800 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="font-cinzel text-xl font-bold text-amber-500 tracking-wider">
                {{ config('app.name', 'RPG GAME') }}
            </div>

            @if (Route::has('login'))
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="rpg-btn">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-400 hover:text-amber-500 transition">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="rpg-btn">Register</a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">

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
    </main>
</body>
</html>
