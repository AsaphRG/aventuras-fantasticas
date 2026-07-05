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

            <div class="mt-4 border-t border-slate-700 pt-4">
                <h3 class="text-xs font-cinzel font-bold uppercase tracking-widest text-slate-400 mb-3 flex items-center gap-2">
                    <span>📖</span> {{ __('Meu Grimório') }}
                </h3>
                @if(isset($enchantments_list) && $enchantments_list->count() > 0)
                    <div class="flex flex-col gap-2 max-h-60 overflow-y-auto pr-1 custom-scrollbar">
                        @foreach($enchantments_list as $spell)
                            <div class="p-2.5 rounded border text-xs flex items-center justify-between {{ $spell->used ? 'bg-slate-900/40 border-slate-800 text-slate-500 opacity-60' : 'bg-slate-900 border-purple-500/30 text-purple-200 shadow-sm' }}">
                                <div class="flex flex-col">
                                    <span class="font-bold {{ $spell->used ? 'line-through' : 'text-purple-300' }}">{{ $spell->enchantment->name }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $spell->used ? 'Gasto' : 'Disponível' }}</span>
                                </div>
                                @if(!$spell->used && in_array($spell->enchantment_id, [7, 9, 10]))
                                    <form method="POST" action="{{ route('game.cast_spell', ['id' => $model_character->id, 'spell_id' => $spell->id]) }}">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-bold rounded shadow transition text-[10px] cursor-pointer" title="Conjurar magia de cura">
                                            ⚡ Conjurar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-500 italic">Nenhum feitiço no grimório.</p>
                @endif
            </div>

            <div class="mt-4 border-t border-slate-700 pt-4">
                <h3 class="text-xs font-cinzel font-bold uppercase tracking-widest text-slate-400 mb-3 flex items-center gap-2">
                    <span>🎒</span> {{ __('Mochila & Equipamentos') }}
                </h3>
                @if(isset($model_character) && $model_character->items->count() > 0)
                    <div class="flex flex-col gap-2 max-h-60 overflow-y-auto pr-1 custom-scrollbar">
                        @foreach($model_character->items as $item)
                            <div class="p-2.5 rounded border text-xs flex items-center justify-between bg-slate-900 border-amber-500/30 text-amber-200 shadow-sm">
                                <div class="flex flex-col">
                                    <span class="font-bold text-amber-300">{{ $item->name }}</span>
                                    @if($item->abilityBonus)
                                        <span class="text-[10px] text-emerald-400 font-semibold">+{{ str_replace(':', ' +', $item->abilityBonus) }}</span>
                                    @elseif($item->description)
                                        <span class="text-[10px] text-slate-400 line-clamp-1" title="{{ $item->description }}">{{ $item->description }}</span>
                                    @endif
                                </div>
                                @if(in_array($item->category, ['Consumable', 'Potion']))
                                    <form method="POST" action="{{ route('game.use_item', ['id' => $model_character->id, 'item_id' => $item->id]) }}">
                                        @csrf
                                        <button type="submit" class="px-2 py-1 bg-gradient-to-r from-amber-600 to-orange-600 hover:from-amber-500 hover:to-orange-500 text-white font-bold rounded shadow transition text-[10px] cursor-pointer" title="Usar item">
                                            🧪 Usar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-xs text-slate-500 italic">Sua mochila está vazia.</p>
                @endif
            </div>

        </div>
    </div>
@endsection

@section('content')
    @if (session('node_effects'))
        <div class="p-6 rounded-xl border-2 mb-6 shadow-2xl transition-all duration-500 animate-fade-in bg-gradient-to-r from-amber-950/80 to-slate-900 border-amber-500/80 text-amber-200 shadow-amber-950/50">
            <div class="flex items-center gap-4">
                <div class="text-4xl p-3 rounded-full bg-amber-900/50 border border-amber-500/50">
                    ✨
                </div>
                <div>
                    <h4 class="font-cinzel font-bold text-xl uppercase tracking-wider text-amber-400">
                        {{ __('Acontecimento do Destino!') }}
                    </h4>
                    <ul class="font-light text-lg mt-1 leading-relaxed list-disc list-inside">
                        @foreach(session('node_effects') as $effectMsg)
                            <li>{{ $effectMsg }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif
    @if (session('spell_casted'))
        <div class="p-6 rounded-xl border-2 mb-6 shadow-2xl transition-all duration-500 animate-fade-in bg-gradient-to-r from-purple-950/80 to-slate-900 border-purple-500/80 text-purple-200 shadow-purple-950/50">
            <div class="flex items-center gap-4">
                <div class="text-4xl p-3 rounded-full bg-purple-900/50 border border-purple-500/50">
                    🪄
                </div>
                <div>
                    <h4 class="font-cinzel font-bold text-xl uppercase tracking-wider text-purple-400">
                        {{ __('Magia Conjurada!') }}
                    </h4>
                    <p class="font-light text-lg mt-1 leading-relaxed">{{ session('spell_casted') }}</p>
                </div>
            </div>
        </div>
    @endif
    @if (session('item_acquired'))
        <div class="p-6 rounded-xl border-2 mb-6 shadow-2xl transition-all duration-500 animate-fade-in bg-gradient-to-r from-amber-950/80 to-slate-900 border-amber-500/80 text-amber-200 shadow-amber-950/50">
            <div class="flex items-center gap-4">
                <div class="text-4xl p-3 rounded-full bg-amber-900/50 border border-amber-500/50">
                    🎒
                </div>
                <div>
                    <h4 class="font-cinzel font-bold text-xl uppercase tracking-wider text-amber-400">
                        {{ __('Novo Item Adquirido!') }}
                    </h4>
                    <p class="font-light text-lg mt-1 leading-relaxed">{{ session('item_acquired') }}</p>
                </div>
            </div>
        </div>
    @endif
    @if (session('item_used'))
        <div class="p-6 rounded-xl border-2 mb-6 shadow-2xl transition-all duration-500 animate-fade-in bg-gradient-to-r from-emerald-950/80 to-slate-900 border-emerald-500/80 text-emerald-200 shadow-emerald-950/50">
            <div class="flex items-center gap-4">
                <div class="text-4xl p-3 rounded-full bg-emerald-900/50 border border-emerald-500/50">
                    🧪
                </div>
                <div>
                    <h4 class="font-cinzel font-bold text-xl uppercase tracking-wider text-emerald-400">
                        {{ __('Item Utilizado!') }}
                    </h4>
                    <p class="font-light text-lg mt-1 leading-relaxed">{{ session('item_used') }}</p>
                </div>
            </div>
        </div>
    @endif
    @if (session('item_error'))
        <div class="p-6 rounded-xl border-2 mb-6 shadow-2xl transition-all duration-500 animate-fade-in bg-gradient-to-r from-red-950/80 to-slate-900 border-red-500/80 text-red-200 shadow-red-950/50">
            <div class="flex items-center gap-4">
                <div class="text-4xl p-3 rounded-full bg-red-900/50 border border-red-500/50">
                    ⚠️
                </div>
                <div>
                    <h4 class="font-cinzel font-bold text-xl uppercase tracking-wider text-red-400">
                        {{ __('Aviso') }}
                    </h4>
                    <p class="font-light text-lg mt-1 leading-relaxed">{{ session('item_error') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session('error_message'))
        <div class="p-6 rounded-xl border-2 mb-6 shadow-2xl transition-all duration-500 animate-fade-in bg-gradient-to-r from-red-950/80 to-slate-900 border-red-500/80 text-red-200 shadow-red-950/50">
            <div class="flex items-center gap-4">
                <div class="text-4xl p-3 rounded-full bg-red-900/50 border border-red-500/50">
                    ⚠️
                </div>
                <div>
                    <h4 class="font-cinzel font-bold text-xl uppercase tracking-wider text-red-400">
                        {{ __('Aviso Mágico') }}
                    </h4>
                    <p class="font-light text-lg mt-1 leading-relaxed">{{ session('error_message') }}</p>
                </div>
            </div>
        </div>
    @endif
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

        @if ($character->getDead() || $story->id == 402 || $story->is_death)
            <div class="mb-6 p-5 bg-gradient-to-r from-red-950/90 to-slate-900 border-2 border-red-500/80 rounded-xl text-red-300 flex items-center gap-4 shadow-xl">
                <span class="text-4xl">💀</span>
                <div>
                    <h4 class="font-cinzel font-bold text-xl uppercase tracking-wider text-red-400">Missão Fracassada</h4>
                    <p class="text-sm font-light text-red-200 mt-1">Seu herói encontrou seu fim na Cidadela do Caos.</p>
                </div>
            </div>
        @elseif ($character->getWin() || $story->id == 400 || $story->is_win)
            <div class="mb-6 p-5 bg-gradient-to-r from-amber-950/90 to-slate-900 border-2 border-amber-500/80 rounded-xl text-amber-300 flex items-center gap-4 shadow-xl">
                <span class="text-4xl">🏆</span>
                <div>
                    <h4 class="font-cinzel font-bold text-xl uppercase tracking-wider text-amber-400">Vitória Gloriosa!</h4>
                    <p class="text-sm font-light text-amber-100 mt-1">Você derrotou Balthus Dire e salvou o Vale dos Salgueiros!</p>
                </div>
            </div>
        @endif

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
    @elseif (isset($battle_state) && $battle_state && in_array($battle_state->status, ['in_progress', 'waiting_luck_test']))
        @php
            $logData = json_decode($battle_state->last_round_log, true) ?: [];
            $logMsg = $logData['message'] ?? '';
            $enemyMaxEnergy = max(1, $battle_state->enemy->energy);
            $playerMaxEnergy = max(1, $character->getEnergyStart());
            $canFleeInit = ($battle_config && $battle_config->can_flee) ? true : false;
            $fleeAfterRoundsInit = ($battle_config && $battle_config->flee_after_rounds !== null) ? $battle_config->flee_after_rounds : null;
        @endphp
        <div class="mt-6 p-6 md:p-8 bg-gradient-to-br from-slate-950 via-red-950/40 to-slate-950 border-2 border-red-600/80 rounded-2xl shadow-[0_0_40px_rgba(220,38,38,0.25)] relative overflow-hidden"
             x-data="battleArena({
                 status: '{{ $battle_state->status }}',
                 round: {{ $battle_state->round_number }},
                 message: {{ json_encode($logMsg) }},
                 luckContext: '{{ $battle_state->luck_test_context }}',
                 attackUrl: '{{ route('battle.attack', ['id' => $character->getId()]) }}',
                 luckUrl: '{{ route('battle.luck', ['id' => $character->getId()]) }}',
                 fleeUrl: '{{ route('battle.flee', ['id' => $character->getId()]) }}',
                 player: {
                     energyCurrent: {{ $character->getEnergyCurrent() }},
                     energyStart: {{ $playerMaxEnergy }},
                     skillCurrent: {{ $character->getSkillCurrent() }},
                     luckCurrent: {{ $character->getLuckCurrent() }}
                 },
                 enemy: {
                     name: {{ json_encode($battle_state->enemy->name) }},
                     ability: {{ $battle_state->enemy_current_ability }},
                     energyCurrent: {{ $battle_state->enemy_current_energy }},
                     energyMax: {{ $enemyMaxEnergy }}
                 },
                 canFlee: {{ $canFleeInit ? 'true' : 'false' }},
                 fleeAfterRounds: {{ $fleeAfterRoundsInit !== null ? $fleeAfterRoundsInit : 'null' }}
             })">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_var(--tw-gradient-stops))] from-red-600/10 via-transparent to-transparent pointer-events-none"></div>

            <div class="flex items-center justify-center gap-3 mb-6 border-b border-red-500/30 pb-4">
                <span class="text-3xl animate-pulse">⚔️</span>
                <h3 class="font-cinzel text-2xl md:text-3xl font-bold text-red-400 uppercase tracking-widest text-center drop-shadow-md">
                    {{ __('Combate em Andamento') }} ({{ __('Rodada') }} <span x-text="round">{{ $battle_state->round_number }}</span>)
                </h3>
                <span class="text-3xl animate-pulse">⚔️</span>
            </div>

            <div x-show="message" x-transition.opacity.duration.300ms class="mb-8 p-5 bg-slate-900/90 border-l-4 border-amber-500 rounded-r-xl shadow-inner text-amber-200 font-cinzel text-base md:text-lg leading-relaxed" x-text="message">
                {{ $logMsg }}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Ficha do Jogador -->
                <div class="p-5 bg-gradient-to-b from-slate-900 to-slate-950 border border-emerald-500/40 rounded-xl shadow-lg relative group">
                    <div class="flex items-center justify-between mb-4 border-b border-slate-800 pb-3">
                        <span class="font-cinzel font-bold text-lg text-emerald-400 flex items-center gap-2">
                            <span>🛡️</span> {{ __('Sua Ficha') }}
                        </span>
                        <span class="text-xs uppercase px-2 py-1 bg-emerald-950 text-emerald-300 rounded border border-emerald-500/30">Herói</span>
                    </div>
                    <div class="space-y-4 font-cinzel">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">{{ __('Habilidade') }}:</span>
                            <span class="text-xl font-bold text-amber-400" x-text="player.skillCurrent">{{ $character->getSkillCurrent() }}</span>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-slate-400">{{ __('Energia') }}:</span>
                                <span class="text-xl font-bold text-emerald-400" x-text="player.energyCurrent + ' / ' + player.energyStart">{{ $character->getEnergyCurrent() }} / {{ $character->getEnergyStart() }}</span>
                            </div>
                            <div class="w-full bg-slate-800 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-gradient-to-r from-emerald-500 to-green-400 h-full transition-all duration-500"
                                     :style="`width: ${Math.max(0, Math.min(100, (player.energyCurrent / Math.max(1, player.energyStart)) * 100))}%`"></div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">{{ __('Sorte') }}:</span>
                            <span class="text-xl font-bold text-purple-400" x-text="player.luckCurrent">{{ $character->getLuckCurrent() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Ficha do Inimigo -->
                <div class="p-5 bg-gradient-to-b from-slate-900 to-red-950/60 border border-red-500/50 rounded-xl shadow-lg relative group" :class="{ 'animate-pulse': animatingHit }">
                    <div class="flex items-center justify-between mb-4 border-b border-red-900/50 pb-3">
                        <span class="font-cinzel font-bold text-lg text-red-400 flex items-center gap-2">
                            <span>🐉</span> <span x-text="enemy.name">{{ $battle_state->enemy->name }}</span>
                        </span>
                        <span class="text-xs uppercase px-2 py-1 bg-red-950 text-red-300 rounded border border-red-500/30">Oponente</span>
                    </div>
                    <div class="space-y-4 font-cinzel">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">{{ __('Habilidade') }}:</span>
                            <span class="text-xl font-bold text-amber-400" x-text="enemy.ability">{{ $battle_state->enemy_current_ability }}</span>
                        </div>
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-slate-400">{{ __('Energia') }}:</span>
                                <span class="text-xl font-bold text-red-400" x-text="enemy.energyCurrent">{{ $battle_state->enemy_current_energy }}</span>
                            </div>
                            <div class="w-full bg-slate-800 h-2.5 rounded-full overflow-hidden">
                                <div class="bg-gradient-to-r from-red-600 to-rose-500 h-full transition-all duration-500"
                                     :style="`width: ${Math.max(0, Math.min(100, (enemy.energyCurrent / Math.max(1, enemy.energyMax)) * 100))}%`"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botões de Ação do Combate -->
            <div x-show="status === 'waiting_luck_test'" x-transition.opacity class="p-6 bg-purple-950/60 border-2 border-purple-500 rounded-xl text-center shadow-xl mb-4" style="{{ $battle_state->status === 'waiting_luck_test' ? '' : 'display: none;' }}">
                <h4 class="font-cinzel text-xl font-bold text-purple-300 mb-2">🍀 {{ __('Momento Decisivo: Testar Sua Sorte?') }}</h4>
                <p class="text-slate-300 text-sm mb-6 max-w-xl mx-auto font-light">
                    <template x-if="luckContext === 'enemy_hit'">
                        <span>{{ __('Você feriu o inimigo! Se testar a sorte e vencer, causará +2 de dano extra (4 no total). Se falhar, o dano será reduzido para apenas 1 ponto.') }}</span>
                    </template>
                    <template x-if="luckContext !== 'enemy_hit'">
                        <span>{{ __('Você foi ferido! Se testar a sorte e vencer, absorverá parte do impacto e sofrerá apenas 1 ponto de dano. Se falhar, o golpe atingirá um ponto crítico e você sofrerá 3 de dano!') }}</span>
                    </template>
                </p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <button type="button" @click="testLuck(true)" :disabled="loading" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white font-cinzel font-bold text-base rounded-xl shadow-lg border border-purple-400 transition-all transform hover:-translate-y-0.5 cursor-pointer disabled:opacity-50">
                        <span x-show="!loading">🍀 {{ __('Sim, Testar Minha Sorte (-1 Sorte)') }}</span>
                        <span x-show="loading" style="display: none;">⌛ Processando...</span>
                    </button>
                    <button type="button" @click="testLuck(false)" :disabled="loading" class="w-full sm:w-auto px-6 py-4 bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white font-cinzel font-semibold text-base rounded-xl border border-slate-600 transition-all cursor-pointer disabled:opacity-50">
                        <span x-show="!loading">⏩ {{ __('Não, Aceitar Resultado da Rodada') }}</span>
                        <span x-show="loading" style="display: none;">⌛ Processando...</span>
                    </button>
                </div>
            </div>

            <div x-show="status !== 'waiting_luck_test'" x-transition.opacity class="flex flex-col sm:flex-row items-center justify-center gap-4" style="{{ $battle_state->status !== 'waiting_luck_test' ? '' : 'display: none;' }}">
                <button type="button" @click="attack()" :disabled="loading" class="w-full sm:w-auto flex-1 max-w-md py-5 px-8 bg-gradient-to-r from-red-600 via-rose-600 to-red-600 hover:from-red-500 hover:via-rose-500 hover:to-red-500 text-white font-cinzel font-bold text-lg rounded-xl shadow-[0_0_25px_rgba(220,38,38,0.5)] border border-red-400 transition-all duration-300 transform hover:-translate-y-1 cursor-pointer flex items-center justify-center gap-3 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none">
                    <span>⚔️</span>
                    <span x-show="!loading">{{ __('ATACAR / PRÓXIMA RODADA') }}</span>
                    <span x-show="loading" style="display: none;">⌛ {{ __('ENVIANDO GOLPE...') }}</span>
                    <span>⚔️</span>
                </button>

                <template x-if="canFlee">
                    <div class="w-full sm:w-auto">
                        <template x-if="fleeAfterRounds === null || round > fleeAfterRounds">
                            <button type="button" @click="flee()" :disabled="loading" class="w-full py-5 px-6 bg-amber-600/80 hover:bg-amber-600 text-white font-cinzel font-bold text-base rounded-xl border border-amber-400 shadow-lg transition-all cursor-pointer flex items-center justify-center gap-2 disabled:opacity-50">
                                <span>🏃</span>
                                <span x-show="!loading">{{ __('FUGIR DA BATALHA') }}</span>
                                <span x-show="loading" style="display: none;">⌛ Fugindo...</span>
                            </button>
                        </template>
                        <template x-if="fleeAfterRounds !== null && round <= fleeAfterRounds">
                            <button disabled class="w-full sm:w-auto py-5 px-6 bg-slate-800/80 text-slate-500 font-cinzel font-semibold text-sm rounded-xl border border-slate-700 cursor-not-allowed flex items-center justify-center gap-2">
                                <span>🔒</span>
                                <span>{{ __('Fuga liberada após rodada') }} <span x-text="fleeAfterRounds"></span></span>
                            </button>
                        </template>
                    </div>
                </template>
            </div>
        </div>

        <script>
            function battleArena(initialData) {
                return {
                    status: initialData.status,
                    round: initialData.round,
                    message: initialData.message,
                    luckContext: initialData.luckContext,
                    attackUrl: initialData.attackUrl,
                    luckUrl: initialData.luckUrl,
                    fleeUrl: initialData.fleeUrl,
                    player: initialData.player,
                    enemy: initialData.enemy,
                    canFlee: initialData.canFlee,
                    fleeAfterRounds: initialData.fleeAfterRounds,
                    loading: false,
                    animatingHit: false,

                    async sendRequest(url, data = {}) {
                        if (window.axios) {
                            return window.axios.post(url, data, {
                                headers: { 'Accept': 'application/json' }
                            });
                        } else {
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                            const res = await fetch(url, {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': csrfToken
                                },
                                body: JSON.stringify(data)
                            });
                            return { data: await res.json() };
                        }
                    },

                    async attack() {
                        if (this.loading) return;
                        this.loading = true;
                        try {
                            const response = await this.sendRequest(this.attackUrl, {});
                            this.handleResponse(response.data);
                        } catch (e) {
                            window.location.reload();
                        } finally {
                            this.loading = false;
                        }
                    },

                    async testLuck(useLuck) {
                        if (this.loading) return;
                        this.loading = true;
                        try {
                            const response = await this.sendRequest(this.luckUrl, { use_luck: useLuck ? 1 : 0 });
                            this.handleResponse(response.data);
                        } catch (e) {
                            window.location.reload();
                        } finally {
                            this.loading = false;
                        }
                    },

                    async flee() {
                        if (this.loading) return;
                        this.loading = true;
                        try {
                            const response = await this.sendRequest(this.fleeUrl, {});
                            this.handleResponse(response.data);
                        } catch (e) {
                            window.location.reload();
                        } finally {
                            this.loading = false;
                        }
                    },

                    handleResponse(data) {
                        if (!data.success || data.status === 'ended') {
                            window.location.reload();
                            return;
                        }

                        this.status = data.status;
                        this.round = data.round_number;
                        this.message = data.message;
                        this.luckContext = data.luck_test_context;
                        this.player = {
                            energyCurrent: data.player.energy_current,
                            energyStart: data.player.energy_start,
                            skillCurrent: data.player.skill_current,
                            luckCurrent: data.player.luck_current
                        };
                        this.enemy = {
                            name: data.enemy.name,
                            ability: data.enemy.ability,
                            energyCurrent: data.enemy.energy_current,
                            energyMax: data.enemy.energy_max
                        };

                        this.animatingHit = true;
                        setTimeout(() => { this.animatingHit = false; }, 400);

                        if (data.status === 'won' || data.status === 'lost' || data.status === 'fled') {
                            this.loading = true;
                            setTimeout(() => {
                                window.location.reload();
                            }, 1800);
                        }
                    }
                }
            }
        </script>
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

                        @if (is_null($choice->to_story_node_id))
                            <button type="submit" class="group w-full text-center p-5 bg-gradient-to-r from-red-900 via-slate-900 to-red-900 border-2 border-red-500/70 rounded-xl hover:border-red-400 hover:bg-slate-800 transition-all duration-300 shadow-xl shadow-red-950/50 flex items-center justify-center gap-3">
                                <span class="text-xl font-bold text-red-200 group-hover:text-white font-cinzel tracking-wider uppercase flex items-center gap-2">
                                    <span>🔙 {{$choice->choice_description}}</span>
                                </span>
                            </button>
                        @else
                            <button type="submit" class="group w-full text-left p-5 bg-slate-900 border border-slate-700 rounded-lg hover:border-amber-500 hover:bg-slate-800 transition-all duration-300 shadow-md hover:shadow-amber-900/10 flex items-center justify-between">
                                <span class="text-lg text-slate-200 group-hover:text-amber-400 font-cinzel transition-colors flex items-center gap-2">
                                    @if($choice->required_flag)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-purple-900/80 text-purple-300 border border-purple-500/40 shrink-0">
                                            🔮 Requer: {{ $choice->required_flag }}
                                        </span>
                                    @endif
                                    <span>{{$choice->choice_description}}</span>
                                </span>
                                <span class="opacity-0 group-hover:opacity-100 text-amber-500 transition-opacity">
                                    ➤
                                </span>
                            </button>
                        @endif
                    </form>
                @endif
            @endforeach
        </div>
    @endif
@endsection
