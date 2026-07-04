<x-app-layout>

    <div class="py-12 bg-[url('/images/stone-texture.png')] min-h-screen bg-slate-900 bg-fixed">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                <div class="bg-slate-800 p-4 rounded-lg border border-slate-700 shadow-lg text-center">
                    <div class="text-3xl mb-1">📜</div>
                    <div class="text-2xl font-bold text-white font-cinzel">{{ $totalGames }}</div>
                    <div class="text-xs text-slate-400 uppercase tracking-widest">{{ __('Finished Journeys') }}</div>
                </div>

                <div class="bg-slate-800 p-4 rounded-lg border border-amber-500/30 shadow-[0_0_15px_rgba(245,158,11,0.1)] text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-2 opacity-10 text-6xl">👑</div>
                    <div class="text-3xl mb-1 text-amber-500">🏆</div>
                    <div class="text-2xl font-bold text-amber-400 font-cinzel">{{ $totalWins }}</div>
                    <div class="text-xs text-amber-400 uppercase tracking-widest">{{ __('Legends Forged') }}</div>
                </div>

                <div class="bg-slate-800 p-4 rounded-lg border border-red-900/50 shadow-lg text-center relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-2 opacity-10 text-6xl">💀</div>
                    <div class="text-3xl mb-1 text-slate-500">⚰️</div>
                    <div class="text-2xl font-bold text-red-400 font-cinzel">{{ $totalDeaths }}</div>
                    <div class="text-xs text-red-400 uppercase tracking-widest">{{ __('Fallen in Combat') }}</div>
                </div>

                <div class="bg-slate-800 p-4 rounded-lg border border-slate-700 shadow-lg text-center">
                    <div class="text-3xl mb-1">📈</div>
                    <div class="text-2xl font-bold text-blue-300 font-cinzel">{{ $winRate }}%</div>
                    <div class="text-xs text-slate-400 uppercase tracking-widest">{{ __('Win Rate') }}</div>
                </div>
            </div>

            <div class="border-b border-slate-700 mb-8"></div>

            @if(is_null($heroes) || count($heroes) == 0)
                <div class="text-center py-20 bg-slate-800/50 rounded-xl border border-dashed border-slate-700">
                    <p class="text-slate-500 font-cinzel text-xl">{{ __('The hall is empty.') }}</p>
                    <p class="text-slate-600 text-sm mt-2">{{ __('Complete your first journey to be immortalized here.') }}</p>
                </div>
            @else
                <h3 class="font-cinzel text-slate-300 text-lg mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 bg-amber-500 rounded-full"></span> {{ __('Ancient Records') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($heroes as $hero)
                        @php
                            // Configuração Visual baseada no destino
                            $isWin = $hero->win;
                            $borderColor = $isWin ? 'border-amber-500/50' : 'border-red-900/50 group-hover:border-red-500/80';
                            $glow = $isWin ? 'hover:shadow-[0_0_20px_rgba(245,158,11,0.2)]' : 'hover:shadow-[0_0_20px_rgba(239,68,68,0.15)]';
                            $opacity = $isWin ? 'opacity-100' : 'opacity-85 grayscale hover:grayscale-0 transition-all duration-500';
                            $icon = $isWin ? '👑' : '💀';
                            $statusText = $isWin ? 'Ascended as Legend' : 'Perished in Darkness';
                            $statusColor = $isWin ? 'text-amber-400' : 'text-red-400';
                        @endphp

                        <div class="relative bg-slate-800 border {{ $borderColor }} rounded-xl overflow-hidden {{ $glow }} transition-all duration-300 flex flex-col justify-between group {{ $opacity }}">

                            <div>
                                <div class="p-4 bg-slate-900/50 border-b border-slate-700 flex justify-between items-center">
                                    <span class="font-cinzel font-bold text-slate-200">{{ __('Hero') }} #{{ $hero->getId() }}</span>
                                    <span class="text-2xl" title="{{ __($statusText) }}">{{ $icon }}</span>
                                </div>

                                <div class="p-6 space-y-4">
                                    <div class="text-center mb-4">
                                        <p class="text-xs font-bold {{ $statusColor }} uppercase tracking-widest mb-1">{{ __($statusText) }}</p>
                                        <p class="text-xs text-slate-400">{{ __('Finished in Chapter') }} {{ $hero->getCurrentStoryNode() }}</p>
                                        @if($hero->storyNode && $hero->storyNode->title)
                                            <p class="text-[11px] text-slate-500 italic mt-0.5">"{{ $hero->storyNode->title }}"</p>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-3 gap-2 text-center text-sm bg-slate-900 rounded p-3 border border-slate-700/50">
                                        <div>
                                            <div class="text-blue-500 font-bold">{{ $hero->getSkillCurrent() }}</div>
                                            <div class="text-[10px] text-slate-600 uppercase">{{ __('Hab') }}</div>
                                        </div>
                                        <div>
                                            <div class="text-red-500 font-bold">{{ $hero->getEnergyCurrent() }}</div>
                                            <div class="text-[10px] text-slate-600 uppercase">{{ __('Ene') }}</div>
                                        </div>
                                        <div>
                                            <div class="text-green-500 font-bold">{{ $hero->getLuckCurrent() }}</div>
                                            <div class="text-[10px] text-slate-600 uppercase">{{ __('Sor') }}</div>
                                        </div>
                                    </div>

                                    <div class="flex justify-between items-center px-2 mt-2">
                                        <span class="text-xs text-slate-500 uppercase">{{ __('Fortune left') }}</span>
                                        <span class="text-amber-400 font-mono font-bold">{{ $hero->getGold() }} {{ __('Gold') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                @if(($hero->enchantments && $hero->enchantments->count() > 0) || ($hero->items && $hero->items->count() > 0))
                                    <div class="px-6 py-3 bg-slate-900/80 border-t border-slate-700/50 flex flex-wrap gap-1.5 items-center">
                                        @if($hero->enchantments)
                                            @foreach($hero->enchantments as $pe)
                                                @if($pe->enchantment)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-purple-950/80 text-purple-300 border border-purple-500/30" title="Magia">
                                                        🪄 {{ $pe->enchantment->name }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        @endif
                                        @if($hero->items)
                                            @foreach($hero->items as $item)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-amber-950/80 text-amber-300 border border-amber-500/30" title="Item">
                                                    🎒 {{ $item->name }}
                                                </span>
                                            @endforeach
                                        @endif
                                    </div>
                                @endif

                                <div class="px-6 py-3 bg-slate-950/40 border-t border-slate-800 flex justify-between items-center">
                                    <a href="{{ route('character.show', ['id' => $hero->getId()]) }}" class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-500/10 hover:bg-amber-500/20 border border-amber-500/30 hover:border-amber-500 rounded text-xs font-cinzel font-bold text-amber-300 hover:text-amber-200 transition-all">
                                        <span>📜</span> {{ __('View Chronicle') }}
                                    </a>
                                    <span class="text-[10px] text-slate-600">
                                        {{ __('Registered at') }}: {{ $hero->updated_at->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
