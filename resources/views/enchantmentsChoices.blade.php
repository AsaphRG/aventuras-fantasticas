@extends('layouts.game')

@section('title', __('Spell Choice'))

@section('header-nav')
    @if (Route::has('login'))
        @auth
            <a href="{{ url('/dashboard') }}" class="rpg-btn">{{ __('Dashboard') }}</a>
        @else
            <a href="{{ route('login') }}" class="text-slate-400 hover:text-amber-500 transition">Log in</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="rpg-btn">Register</a>
            @endif
        @endauth
    @endif
@endsection

@section('content')
    <div class="flex justify-between items-end mb-8 border-b border-slate-800 pb-4">
        <div>
            <h1 class="font-cinzel text-3xl md:text-4xl text-purple-400 drop-shadow-md">{{ __('Arcane Tomes') }}</h1>
            <p class="text-slate-400 text-sm mt-1">{{ __('Study and memorize your spells. You can choose the same spell several times.') }}</p>
        </div>

        <div class="bg-slate-950 border border-slate-700 px-6 py-3 rounded-lg shadow-inner text-center">
            <span class="block text-xs uppercase tracking-widest text-slate-500 font-bold mb-1">{{ __('Available Points') }}</span>
            <span id="remaining-counter" class="text-3xl font-cinzel text-amber-400 font-bold">{{ $enchantments_limit }}</span>
        </div>
    </div>

    <form action="{{ route('save_enchantments', request()->route('id')) }}" method="POST" id="enchantment-form">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-10">
            @foreach ($enchantments as $enchantment)
                <div class="bg-slate-800 rounded-xl overflow-hidden border border-slate-700 hover:border-purple-500 transition-all duration-300 hover:shadow-lg hover:shadow-purple-900/20 flex flex-col">

                    <div class="p-5 flex-grow">
                        <h3 class="font-cinzel text-xl font-bold text-white mb-2">{{ $enchantment->name }}</h3>
                        <p class="text-sm text-slate-400 line-clamp-3">
                            {{ $enchantment->description ?? __('An ancient magic with mysterious powers.') }}
                        </p>
                    </div>

                    <div class="p-4 bg-slate-900/50 border-t border-slate-700 flex items-center justify-between">
                        <span class="text-xs text-slate-500 uppercase font-bold tracking-wider">{{ __('Memorize') }}</span>

                        <div class="flex items-center gap-3">
                            <button type="button" class="btn-minus w-8 h-8 rounded bg-slate-700 hover:bg-red-900 text-white flex items-center justify-center transition disabled:opacity-50 disabled:cursor-not-allowed" data-id="{{ $enchantment->id }}">
                                -
                            </button>
                            <input type="text" name="enchantments[{{ $enchantment->id }}]" id="input-{{ $enchantment->id }}" value="0" readonly class="w-10 text-center bg-transparent text-white font-bold font-mono focus:outline-none select-none">

                            <button type="button" class="btn-plus w-8 h-8 rounded bg-slate-700 hover:bg-green-700 text-white flex items-center justify-center transition disabled:opacity-50 disabled:cursor-not-allowed" data-id="{{ $enchantment->id }}">
                                +
                            </button>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>

        <div class="flex justify-end sticky bottom-6">
            <button type="submit" id="submit-btn" disabled class="px-8 py-4 bg-slate-800 text-slate-500 font-bold rounded-lg transition-all duration-300 uppercase tracking-widest border border-slate-700 cursor-not-allowed shadow-lg">
                {{ __('Confirm Spells') }}
            </button>
        </div>
    </form>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const limit = {{ $enchantments_limit }};
        const inputs = document.querySelectorAll('input[id^="input-"]');
        const btnsMinus = document.querySelectorAll('.btn-minus');
        const btnsPlus = document.querySelectorAll('.btn-plus');
        const remainingCounter = document.getElementById('remaining-counter');
        const submitBtn = document.getElementById('submit-btn');

        function updateUI() {
            let currentTotal = 0;
            inputs.forEach(input => {
                currentTotal += parseInt(input.value) || 0;
            });

            let remaining = limit - currentTotal;
            remainingCounter.textContent = remaining;

            // Estilização do contador numérico
            if(remaining === 0) {
                remainingCounter.classList.replace('text-amber-400', 'text-green-500');
            } else {
                remainingCounter.classList.replace('text-green-500', 'text-amber-400');
            }

            // Controle dos botões MAIS
            btnsPlus.forEach(btn => {
                btn.disabled = (remaining <= 0);
            });

            // Controle dos botões MENOS
            btnsMinus.forEach(btn => {
                const id = btn.getAttribute('data-id');
                const input = document.getElementById('input-' + id);
                btn.disabled = (parseInt(input.value) <= 0);
            });

            // Controle do botão de Submit
            if (remaining === 0) {
                submitBtn.disabled = false;
                submitBtn.classList.remove('bg-slate-800', 'text-slate-500', 'border-slate-700', 'cursor-not-allowed');
                submitBtn.classList.add('bg-purple-700', 'hover:bg-purple-600', 'text-white', 'border-purple-500', 'hover:shadow-purple-500/30', 'cursor-pointer');
            } else {
                submitBtn.disabled = true;
                submitBtn.classList.add('bg-slate-800', 'text-slate-500', 'border-slate-700', 'cursor-not-allowed');
                submitBtn.classList.remove('bg-purple-700', 'hover:bg-purple-600', 'text-white', 'border-purple-500', 'hover:shadow-purple-500/30', 'cursor-pointer');
            }
        }

        // Event Listeners
        btnsPlus.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const input = document.getElementById('input-' + id);

                let currentTotal = 0;
                inputs.forEach(inp => currentTotal += parseInt(inp.value) || 0);

                if (currentTotal < limit) {
                    input.value = parseInt(input.value) + 1;
                    updateUI();
                }
            });
        });

        btnsMinus.forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const input = document.getElementById('input-' + id);

                if (parseInt(input.value) > 0) {
                    input.value = parseInt(input.value) - 1;
                    updateUI();
                }
            });
        });

        // Inicializa a interface
        updateUI();
    });
</script>
@endpush
