@extends('layouts.game')

@section('title', 'Aventura')

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
            $className = 'Aventureiro';

            // (Sua lógica de ifs gigantesca aqui - Simplifiquei para o exemplo, mas use a sua completa)
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

        <div class="relative h-48 bg-slate-950">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-800 to-transparent z-10"></div>
            <img src="{{ asset('images/' . $img) }}" alt="Character" class="w-full h-full object-cover object-top opacity-90">
            <div class="absolute bottom-3 left-4 z-20">
                <div class="text-xs text-amber-500 font-bold uppercase tracking-wider mb-0.5">Herói Atual</div>
                <div class="text-2xl font-cinzel font-bold text-white">ID: {{$character->getId()}}</div>
            </div>
        </div>

        <div class="p-5 flex flex-col gap-4">

            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-slate-400 uppercase font-bold">Habilidade</span>
                        <span class="text-slate-200">{{$character->getSkillCurrent()}} / {{$character->getSkillStart()}}</span>
                    </div>
                    <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-blue-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]" style="width: {{ $skillPct * 100 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-slate-400 uppercase font-bold">Energia</span>
                        <span class="text-slate-200">{{$character->getEnergyCurrent()}} / {{$character->getEnergyStart()}}</span>
                    </div>
                    <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full bg-red-500 shadow-[0_0_10px_rgba(239,68,68,0.5)]" style="width: {{ $energyPct * 100 }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-slate-400 uppercase font-bold">Sorte</span>
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
                    <div class="text-[10px] text-slate-500 uppercase tracking-widest">Ouro</div>
                </div>
                <div class="bg-slate-900 rounded p-2 text-center border border-slate-700">
                    <div class="text-purple-400 font-bold text-lg">{{$character->getEnchantmentStart()}}</div>
                    <div class="text-[10px] text-slate-500 uppercase tracking-widest">Magia</div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('content')
    <div class="bg-slate-800/80 backdrop-blur-sm p-8 rounded-xl border border-slate-700 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-amber-500 to-transparent opacity-50"></div>

        <h1 class="font-cinzel text-3xl md:text-4xl text-amber-500 mb-6 drop-shadow-sm border-b border-slate-700 pb-4">
            {{$story->title}}
        </h1>

        <div class="prose prose-invert max-w-none text-slate-300 leading-loose text-lg font-light tracking-wide">
            {!! $story->history !!}
        </div>
    </div>

    <div class="flex flex-col gap-3">
        <h3 class="text-slate-400 font-cinzel text-sm uppercase tracking-widest mb-2 ml-1">O que você vai fazer?</h3>

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
@endsection
