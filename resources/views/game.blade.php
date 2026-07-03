@extends('layouts.game')

@section('title', __('Adventure'))

@section('sidebar')
    <div class="bg-slate-800 rounded-xl overflow-hidden border border-slate-600 shadow-2xl">

        @php
            // Cálculos (Mantidos da sua lógica original)
            $skillPct = ($character->getSkillStart() - 7) / (12 - 7);
            $energyPct = ($character->getEnergyStart() - 14) / (24 - 14);
            $luckPct = ($character->getLuckStart() - 7) / (12 - 7);
            $enchantmentPct = ($character->getEnchantmentStart() - 8) / (18 - 8);

            // Lógica de Imagem
            $img = 'Mingy.png';
            $className = 'Unknown';

            // (Sua lógica de ifs completa)
            if ($character->getGold() >= 100) { $img = 'Mingy.png'; $className = 'Rich'; }
            elseif ($skillPct == 0 && $energyPct == 0 && $luckPct == 0 && $enchantmentPct == 0) { $img = 'The Weak.png'; $className = 'Weak'; }
            elseif ($skillPct == 1 && $energyPct == 1 && $luckPct == 1 && $enchantmentPct == 1) { $img = 'Vitruvian.png'; $className = 'Perfect'; }
            elseif ($skillPct == $energyPct && $skillPct == $luckPct && $skillPct == $enchantmentPct) { $img = 'Pilgrim.png'; $className = 'Pilgrim'; }
            elseif ($skillPct == $energyPct && $skillPct == $luckPct && $skillPct > $enchantmentPct) { $img = 'Swashbuckler.png'; $className = 'Swashbuckler'; }
            elseif ($skillPct == $energyPct && $skillPct == $enchantmentPct && $skillPct > $luckPct) { $img = 'Paladin.png'; $className = 'Paladin'; }
            elseif ($skillPct > $energyPct && $skillPct == $luckPct && $skillPct == $enchantmentPct) { $img = 'Artificer.png'; $className = 'Artificer'; }
            elseif ($skillPct < $energyPct && $energyPct == $luckPct && $energyPct == $enchantmentPct) { $img = 'Xamã.png'; $className = 'Shaman'; }
            elseif ($skillPct == $energyPct && $skillPct > $luckPct && $skillPct > $enchantmentPct) { $img = 'Monk.png'; $className = 'Monk'; }
            elseif ($skillPct == $luckPct && $skillPct > $energyPct && $skillPct > $enchantmentPct) { $img = 'Rogue.png'; $className = 'Rogue'; }
            elseif ($skillPct == $enchantmentPct && $skillPct > $energyPct && $skillPct > $luckPct) { $img = 'Mage.png'; $className = 'Mage'; }
            elseif ($energyPct == $luckPct && $energyPct > $skillPct && $energyPct > $enchantmentPct) { $img = 'Druid.png'; $className = 'Druid'; }
            elseif ($energyPct == $enchantmentPct && $energyPct > $skillPct && $energyPct > $luckPct) { $img = 'Cleric.png'; $className = 'Cleric'; }
            elseif ($luckPct == $enchantmentPct && $luckPct > $skillPct && $luckPct > $energyPct) { $img = 'Sorcerer.png'; $className = 'Sorcerer'; }
            elseif ($skillPct > $energyPct && $skillPct > $luckPct && $skillPct > $enchantmentPct) { $img = 'Warrior.png'; $className = 'Warrior'; }
            elseif ($energyPct > $skillPct && $energyPct > $luckPct && $energyPct > $enchantmentPct) { $img = 'Barbarian.png'; $className = 'Barbarian'; }
            elseif ($luckPct > $skillPct && $luckPct > $energyPct && $luckPct > $enchantmentPct) { $img = 'Ranger.png'; $className = 'Ranger'; }
            elseif ($enchantmentPct > $skillPct && $enchantmentPct > $energyPct && $enchantmentPct > $luckPct) { $img = 'Wizard.png'; $className = 'Wizard'; }

            $skillPct = $character->getSkillCurrent() / $character->getSkillStart();
            $energyPct = $character->getEnergyCurrent() / $character->getEnergyStart();
            $luckPct = $character->getLuckCurrent() / $character->getLuckStart();
            $enchantmentPct = $character->getEnchantmentCurrent() / $character->getEnchantmentStart();
        @endphp

        <div class="relative h-48 bg-slate-950">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-800 to-transparent z-10"></div>
            <img src="{{ asset('images/' . $img) }}" alt="{{ __($className) }}" class="w-full h-full object-cover object-top opacity-90">
            <div class="absolute bottom-3 left-4 z-20">
                <div class="text-xs text-amber-500 font-bold uppercase tracking-wider mb-0.5">{{ __('Current Hero') }}</div>
                <div class="text-2xl font-cinzel font-bold text-white">ID: {{$character->getId()}}</div>
            </div>
        </div>

        <div class="p-5 flex flex-col gap-4">

            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-slate-400 uppercase font-bold">{{ __('Skill') }}</span>
                        <span class="text-slate-200">{{$character->getSkillCurrent()}} / {{$character->getSkillStart()}}</span>
                    </div>
                    <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]" style="width: {{ $skillPct * 100 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-slate-400 uppercase font-bold">{{ __('Energy') }}</span>
                        <span class="text-slate-200">{{$character->getEnergyCurrent()}} / {{$character->getEnergyStart()}}</span>
                    </div>
                    <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.5)]" style="width: {{ $energyPct * 100 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-slate-400 uppercase font-bold">{{ __('Luck') }}</span>
                        <span class="text-slate-200">{{$character->getLuckCurrent()}} / {{$character->getLuckStart()}}</span>
                    </div>
                    <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.5)]" style="width: {{ $luckPct * 100 }}%"></div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mt-2 border-t border-slate-700 pt-4">
                <div class="bg-slate-900 rounded p-2 text-center border border-slate-700">
                    <div class="text-amber-400 font-bold text-lg">{{$character->getGold()}}</div>
                    <div class="text-[10px] text-slate-500 uppercase tracking-widest">{{ __('Gold') }}</div>
                </div>
                <div class="bg-slate-900 rounded p-2 text-center border border-slate-700">
                    <div class="text-purple-400 font-bold text-lg">{{$character->getEnchantmentCurrent()}}</div>
                    <div class="text-[10px] text-slate-500 uppercase tracking-widest">{{ __('Spells') }}</div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('content')
    @if (session('luck_message'))
        <div class="p-6 rounded-xl border-2 mb-6 shadow-2xl transition-all duration-500 animate-fade-in {{ session('luck_result') == 'success' ? 'bg-gradient-to-r from-green-950/80 to-slate-900 border-green-500/80 text-green-200 shadow-green-950/50' : 'bg-gradient-to-r from-red-950/80 to-slate-900 border-red-500/80 text-red-200 shadow-red-950/50' }}">
            <div class="flex items-center gap-4">
                <div class="text-4xl p-3 rounded-full {{ session('luck_result') == 'success' ? 'bg-green-900/50 border border-green-500/50' : 'bg-red-900/50 border border-red-500/50' }}">
                    {{ session('luck_result') == 'success' ? '🍀' : '💀' }}
                </div>
                <div>
                    <h4 class="font-cinzel font-bold text-xl uppercase tracking-wider {{ session('luck_result') == 'success' ? 'text-green-400' : 'text-red-400' }}">
                        {{ session('luck_result') == 'success' ? __('Sorte!') : __('Azar!') }}
                    </h4>
                    <p class="font-light text-lg mt-1 leading-relaxed">{{ session('luck_message') }}</p>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t {{ session('luck_result') == 'success' ? 'border-green-800/50 text-green-400/80' : 'border-red-800/50 text-red-400/80' }} text-xs flex items-center justify-between font-mono tracking-widest uppercase">
                <span>{{ __('Mecânica do Destino') }}</span>
                <span>{{ __('-1 Ponto de Sorte Deduzido') }}</span>
            </div>
        </div>
    @endif

    <div class="bg-slate-800/80 backdrop-blur-sm p-8 rounded-xl border border-slate-700 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-amber-500 to-transparent opacity-50"></div>

        <h1 class="font-cinzel text-3xl md:text-4xl text-amber-500 mb-6 drop-shadow-sm border-b border-slate-700 pb-4">
            {{$story->title}}
        </h1>

        <div class="prose prose-invert max-w-none text-slate-300 leading-loose text-lg font-light tracking-wide">
            {!! $story->history !!}
        </div>
    </div>

    @if ($story->luckTest)
        <div class="mt-4 p-8 bg-gradient-to-br from-slate-900 via-purple-950/40 to-slate-900 border-2 border-purple-500/60 rounded-xl shadow-[0_0_30px_rgba(168,85,247,0.2)] text-center relative overflow-hidden group">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-purple-500/10 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700 pointer-events-none"></div>
            
            <div class="w-20 h-20 bg-purple-900/60 border-2 border-purple-400/80 rounded-full flex items-center justify-center mx-auto mb-5 text-4xl shadow-[0_0_15px_rgba(168,85,247,0.4)] animate-bounce">
                🎲
            </div>
            
            <h3 class="font-cinzel text-2xl text-purple-300 font-bold mb-2 tracking-wide">{{ __('O Destino Exige uma Provocarão') }}</h3>
            <p class="text-slate-300 text-base max-w-lg mx-auto mb-6 leading-relaxed font-light">
                {{ __('Esta ação depende puramente de sua sorte. Ao testar sua sorte, 2 dados (2d6) serão rolados e comparados com sua Sorte Atual.') }}
            </p>
            
            <div class="inline-flex items-center gap-3 px-6 py-3 rounded-full bg-purple-950/80 border border-purple-500/40 mb-8 shadow-inner">
                <span class="text-xs uppercase font-bold tracking-widest text-purple-300">{{ __('Sorte Atual') }}:</span>
                <span class="text-2xl font-cinzel font-bold text-amber-400">{{ $character->getLuckCurrent() }}</span>
            </div>

            <form method="POST" action="{{ route('game.test_luck', ['id' => $character->getId()]) }}">
                @csrf
                <button type="submit" class="w-full sm:w-auto px-10 py-5 bg-gradient-to-r from-purple-700 via-indigo-700 to-purple-700 hover:from-purple-600 hover:via-indigo-600 hover:to-purple-600 text-white font-cinzel font-bold text-lg rounded-xl shadow-xl shadow-purple-950/80 border border-purple-400/50 transition-all duration-300 transform hover:-translate-y-1 hover:shadow-[0_0_25px_rgba(168,85,247,0.5)] cursor-pointer tracking-wider">
                    🍀 {{ __('TESTAR MINHA SORTE') }} 🎲
                </button>
            </form>
        </div>
    @else
        <div class="flex flex-col gap-3">
            <h3 class="text-slate-400 font-cinzel text-sm uppercase tracking-widest mb-2 ml-1">{{ __('What are you going to do?') }}</h3>

            @foreach ($choices as $choice)
                @php
                    // Verifica se pode mostrar a escolha
                    $canShow = $choice->required_flag == null || in_array($choice->required_flag, $character_flags);
                @endphp

                @if ($canShow)
                    <form method="GET" action="{{ route('nextChap', ['id'=>$character->getId()]) }}" class="w-full">
                        @csrf
                        <input type="hidden" name="choice_id" value="{{$choice->id}}">

                        <button type="submit" class="group w-full text-left p-5 bg-slate-900 border border-slate-700 rounded-lg hover:border-amber-500 hover:bg-slate-800 transition-all duration-300 shadow-md hover:shadow-amber-900/10 flex items-center justify-between">
                            <span class="text-lg text-slate-200 group-hover:text-amber-400 font-cinzel transition-colors">
                                {{$choice->choice_description}}
                            </span>
                            <span class="opacity-0 group-hover:opacity-100 text-amber-500 transition-opacity">
                                ➤
                            </span>
                        </button>
                    </form>
                @endif
            @endforeach
        </div>
    @endif
@endsection
