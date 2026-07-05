<x-app-layout>
    <div class="py-12 bg-[url('/images/stone-texture.png')] min-h-screen bg-slate-900 bg-fixed text-slate-200">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            {{-- Top Navigation --}}
            <div class="mb-8 flex flex-wrap items-center justify-between gap-4">
                @if($character->win || $character->dead)
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 hover:border-amber-500/50 rounded-lg text-sm font-cinzel font-bold text-amber-400 transition-all shadow-md">
                        <span>⬅</span> {{ __('Back to Memorial') }}
                    </a>
                @else
                    <a href="{{ route('adventure_choice') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 hover:border-amber-500/50 rounded-lg text-sm font-cinzel font-bold text-amber-400 transition-all shadow-md">
                        <span>⬅</span> {{ __('Back to Selection') }}
                    </a>
                    <a href="{{ route('game', ['id' => $character->getId()]) }}" class="inline-flex items-center gap-2 px-6 py-2 bg-amber-600 hover:bg-amber-500 border border-amber-400 rounded-lg text-sm font-cinzel font-bold text-white transition-all shadow-lg shadow-amber-900/30">
                        <span>⚔️</span> {{ __('Continue Journey') }}
                    </a>
                @endif
            </div>

            {{-- Hero Profile Banner --}}
            @php
                $isWin = $character->win;
                $isDead = $character->dead;
                $borderColor = $isWin ? 'border-amber-500/60 shadow-[0_0_25px_rgba(245,158,11,0.2)]' : ($isDead ? 'border-red-900/70 shadow-[0_0_25px_rgba(239,68,68,0.15)]' : 'border-slate-700 shadow-lg');
                $statusText = $isWin ? 'Ascended as Legend' : ($isDead ? 'Perished in Darkness' : 'Playing');
                $statusColor = $isWin ? 'text-amber-400 font-bold' : ($isDead ? 'text-red-400 font-bold' : 'text-blue-400 font-bold');
                $statusIcon = $isWin ? '👑' : ($isDead ? '💀' : '⚔️');
            @endphp

            <div class="bg-slate-800 border {{ $borderColor }} rounded-2xl p-6 md:p-8 mb-12 relative overflow-hidden">
                <div class="absolute -right-6 -bottom-6 text-9xl opacity-5 select-none pointer-events-none">{{ $statusIcon }}</div>
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 pb-6 border-b border-slate-700/80">
                    <div>
                        <div class="flex items-center gap-3 mb-1">
                            <span class="font-cinzel text-2xl md:text-3xl font-extrabold text-white">{{ __('Hero') }} #{{ $character->getId() }}</span>
                            <span class="text-3xl" title="{{ __($statusText) }}">{{ $statusIcon }}</span>
                        </div>
                        <p class="text-sm uppercase tracking-widest {{ $statusColor }} flex items-center gap-2">
                            <span>{{ __($statusText) }}</span>
                            @if(!$isWin && !$isDead)
                                <span class="text-slate-500">•</span>
                                <span class="text-slate-400 text-xs normal-case">{{ __('Chapter') }} {{ $character->getCurrentStoryNode() }}</span>
                            @endif
                        </p>
                    </div>

                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-4 gap-3 w-full md:w-auto text-center font-mono">
                        <div class="bg-slate-900/80 px-4 py-2.5 rounded-xl border border-slate-700/60">
                            <div class="text-blue-400 text-lg font-bold">{{ $character->getSkillCurrent() }}</div>
                            <div class="text-[10px] text-slate-500 uppercase font-sans font-bold">{{ __('Hab') }}</div>
                        </div>
                        <div class="bg-slate-900/80 px-4 py-2.5 rounded-xl border border-slate-700/60">
                            <div class="text-red-400 text-lg font-bold">{{ $character->getEnergyCurrent() }}</div>
                            <div class="text-[10px] text-slate-500 uppercase font-sans font-bold">{{ __('Ene') }}</div>
                        </div>
                        <div class="bg-slate-900/80 px-4 py-2.5 rounded-xl border border-slate-700/60">
                            <div class="text-green-400 text-lg font-bold">{{ $character->getLuckCurrent() }}</div>
                            <div class="text-[10px] text-slate-500 uppercase font-sans font-bold">{{ __('Sor') }}</div>
                        </div>
                        <div class="bg-slate-900/80 px-4 py-2.5 rounded-xl border border-slate-700/60">
                            <div class="text-amber-400 text-lg font-bold">{{ $character->getGold() }}</div>
                            <div class="text-[10px] text-slate-500 uppercase font-sans font-bold">{{ __('Gold') }}</div>
                        </div>
                    </div>
                </div>

                {{-- Spells & Items --}}
                @if(($character->enchantments && $character->enchantments->count() > 0) || ($character->items && $character->items->count() > 0))
                    <div class="pt-5 flex flex-wrap items-center gap-2">
                        <span class="text-xs font-cinzel font-bold uppercase tracking-wider text-slate-400 mr-2">{{ __('Equipamentos & Magias') }}:</span>
                        @if($character->enchantments)
                            @foreach($character->enchantments as $pe)
                                @if($pe->enchantment)
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-xs font-medium bg-purple-950/80 text-purple-300 border border-purple-500/40 shadow-sm" title="Tomo Arcano / Magia">
                                        🪄 {{ $pe->enchantment->name }}
                                    </span>
                                @endif
                            @endforeach
                        @endif
                        @if($character->items)
                            @foreach($character->items as $item)
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-md text-xs font-medium bg-amber-950/80 text-amber-300 border border-amber-500/40 shadow-sm" title="Item de Inventário">
                                    🎒 {{ $item->name }}
                                </span>
                            @endforeach
                        @endif
                    </div>
                @endif
            </div>

            {{-- Title Chronicle --}}
            <div class="mb-8 border-b border-slate-800 pb-4">
                <h2 class="font-cinzel text-2xl md:text-3xl text-white flex items-center gap-3">
                    <span class="text-amber-500">📜</span> {{ __('Path of Destiny') }}
                </h2>
                <p class="text-slate-400 text-sm mt-1">Acompanhe todos os passos, escolhas e decisões tomadas por este herói em sua jornada.</p>
            </div>

            {{-- Timeline of Choices --}}
            @if(is_null($character->playerStoryNode) || $character->playerStoryNode->count() == 0)
                <div class="text-center py-16 bg-slate-800/40 rounded-2xl border border-dashed border-slate-700">
                    <p class="text-slate-500 font-cinzel text-lg">{{ __('No choices recorded yet.') }}</p>
                </div>
            @else
                <div class="relative pl-6 md:pl-8 border-l-2 border-amber-500/30 ml-4 md:ml-6 space-y-10">
                    @foreach($character->playerStoryNode as $step)
                        @php
                            $nodeId = $step->story_node_id;
                            $nodeObj = $step->storyNode;
                            
                            // Cores especiais para nós de fim
                            $isEndWin = ($nodeId == 400 || ($nodeObj && $nodeObj->is_win));
                            $isEndLoss = ($nodeId == 402 || ($nodeObj && $nodeObj->is_death));
                            $cardBorder = $isEndWin ? 'border-amber-500 shadow-[0_0_20px_rgba(245,158,11,0.15)]' : ($isEndLoss ? 'border-red-900 shadow-[0_0_20px_rgba(239,68,68,0.15)]' : 'border-slate-700/80 hover:border-slate-600');
                            $dotColor = $isEndWin ? 'bg-amber-500 ring-4 ring-amber-500/20' : ($isEndLoss ? 'bg-red-500 ring-4 ring-red-500/20' : 'bg-slate-600 group-hover:bg-amber-400');
                        @endphp

                        <div class="relative group">
                            {{-- Timeline Dot --}}
                            <div class="absolute -left-[31px] md:-left-[39px] top-1 w-4 h-4 rounded-full {{ $dotColor }} transition-all duration-300"></div>

                            {{-- Timeline Card --}}
                            <div class="bg-slate-800/90 border {{ $cardBorder }} rounded-xl p-6 transition-all duration-300 shadow-md">
                                
                                {{-- Step Header --}}
                                <div class="flex flex-wrap justify-between items-center gap-2 mb-4 pb-3 border-b border-slate-700/60">
                                    <div class="flex items-center gap-2.5">
                                        <span class="px-2.5 py-0.5 rounded text-[11px] font-mono font-bold uppercase bg-amber-500/10 text-amber-400 border border-amber-500/30">
                                            {{ __('Step') }} {{ $loop->iteration }}
                                        </span>

                                        @if($nodeId == 0)
                                            <h3 class="font-cinzel font-bold text-lg text-slate-200">{{ __('Start of Journey') }}</h3>
                                        @elseif($nodeObj)
                                            <h3 class="font-cinzel font-bold text-lg text-slate-200">
                                                {{ __('Chapter') }} {{ $nodeId }}
                                                @if($nodeObj->title && $nodeObj->title != $nodeId && $nodeObj->title != (string)$nodeId)
                                                    <span class="text-amber-400 font-normal"> — {{ $nodeObj->title }}</span>
                                                @endif
                                            </h3>
                                        @else
                                            <h3 class="font-cinzel font-bold text-lg text-slate-200">{{ __('Chapter') }} {{ $nodeId }}</h3>
                                        @endif
                                    </div>

                                    @if($step->created_at)
                                        <span class="text-xs text-slate-500 font-mono">
                                            {{ $step->created_at->format('d/m/Y H:i:s') }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Step Content --}}
                                <div class="prose prose-invert max-w-none text-slate-300 text-sm md:text-base leading-relaxed font-serif space-y-3">
                                    @if($nodeId == 0)
                                        <p class="italic text-slate-400">{{ __('The adventurer prepares their gear and begins their exploration of the Citadel of Chaos.') }}</p>
                                    @elseif($nodeObj && $nodeObj->history)
                                        {!! $nodeObj->history !!}
                                    @else
                                        <p class="italic text-slate-500">Registros arcanos não encontrados para este capítulo.</p>
                                    @endif
                                </div>

                                {{-- Choice Taken --}}
                                @if(isset($choicesMade[$step->id]))
                                    <div class="mt-5 pt-3.5 border-t border-slate-700/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-900/80 -mx-6 -mb-6 p-4 rounded-b-xl border-l-4 {{ $isEndWin ? 'border-l-amber-500' : ($isEndLoss ? 'border-l-red-500 font-semibold' : 'border-l-amber-400') }}">
                                        <div class="flex items-start gap-2.5">
                                            <span class="text-base">⚡</span>
                                            <div>
                                                <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-0.5">{{ __('Choice Made') }}:</span>
                                                <span class="text-sm font-semibold text-amber-300 font-sans">
                                                    "{{ $choicesMade[$step->id] }}"
                                                </span>
                                            </div>
                                        </div>
                                        @if(!$loop->last && isset($character->playerStoryNode[$loop->index + 1]))
                                            <span class="text-xs font-mono font-bold text-slate-300 bg-slate-800 px-2.5 py-1 rounded border border-slate-700/80 flex items-center gap-1.5 self-end sm:self-center whitespace-nowrap shadow-sm">
                                                <span>➔</span> Capítulo {{ $character->playerStoryNode[$loop->index + 1]->story_node_id }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Bottom Back Button --}}
            <div class="mt-12 text-center">
                @if($character->win || $character->dead)
                    <a href="{{ route('dashboard') }}" class="rpg-btn-primary inline-block px-8">
                        ⬅ {{ __('Back to Memorial') }}
                    </a>
                @else
                    <a href="{{ route('adventure_choice') }}" class="rpg-btn-primary inline-block px-8">
                        ⬅ {{ __('Back to Selection') }}
                    </a>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
