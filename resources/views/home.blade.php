<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <title>{{ config('app.name', 'Laravel RPG') }} - Início</title>

    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <style>
        /* Animação sutil de "flutuar" para o logo ou herói */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-200 font-sans antialiased min-h-screen flex flex-col relative selection:bg-amber-500 selection:text-white">

    <div class="fixed inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-black opacity-90"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100 contrast-150"></div>
    </div>

    <header class="w-full fixed top-0 z-50 transition-all duration-300 backdrop-blur-md border-b border-white/5">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div class="font-cinzel text-xl font-bold text-amber-500 tracking-widest flex items-center gap-2">
                <span class="text-2xl">⚔️</span> {{ config('app.name', 'RPG GAME') }}
            </div>

            @if (Route::has('login'))
                <nav class="flex items-center gap-6">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="font-cinzel text-amber-400 hover:text-amber-300 transition">Continuar Aventura →</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-300 hover:text-white transition uppercase tracking-wide">Login</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2 bg-amber-600 hover:bg-amber-500 text-white text-sm font-bold rounded shadow-lg shadow-amber-900/50 transition-all hover:scale-105 border border-amber-400/30 uppercase tracking-wider">
                                Criar Conta
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </header>

    <main class="relative z-10 flex-grow flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8 mt-16">

        <div class="text-center max-w-4xl mx-auto space-y-8 py-20">

            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-amber-900/30 border border-amber-500/30 text-amber-400 text-xs font-bold uppercase tracking-[0.2em] mb-4">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                Alpha Version
            </div>

            <h1 class="font-cinzel text-5xl md:text-7xl lg:text-8xl font-black text-transparent bg-clip-text bg-gradient-to-b from-amber-200 via-amber-500 to-amber-700 drop-shadow-sm animate-float">
                {{ config('app.name', 'A LENDA') }}
            </h1>

            <p class="text-lg md:text-2xl text-slate-400 font-light max-w-2xl mx-auto leading-relaxed">
                Escolha seu caminho, enfrente seus demônios e forje seu destino em um mundo onde cada <span class="text-amber-500 font-semibold">escolha</span> define sua sobrevivência.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-8">
                @auth
                    <a href="{{ url('/game') }}" class="w-full sm:w-auto px-8 py-4 bg-amber-600 hover:bg-amber-500 text-white font-cinzel font-bold text-lg rounded shadow-[0_0_20px_rgba(217,119,6,0.3)] hover:shadow-[0_0_30px_rgba(217,119,6,0.6)] transition-all border border-amber-400/30 transform hover:-translate-y-1">
                        Retomar Jornada
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 bg-amber-600 hover:bg-amber-500 text-white font-cinzel font-bold text-lg rounded shadow-[0_0_20px_rgba(217,119,6,0.3)] hover:shadow-[0_0_30px_rgba(217,119,6,0.6)] transition-all border border-amber-400/30 transform hover:-translate-y-1">
                        Começar Aventura
                    </a>
                    <a href="#features" class="w-full sm:w-auto px-8 py-4 bg-transparent hover:bg-slate-800 text-slate-300 font-cinzel font-bold text-lg rounded border border-slate-600 hover:border-slate-400 transition-all">
                        Saiba Mais
                    </a>
                @endauth
            </div>
        </div>

        <div id="features" class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl w-full mt-12 mb-20">

            <div class="bg-slate-900/50 p-6 rounded-xl border border-slate-800 hover:border-amber-500/50 transition duration-300 backdrop-blur-sm group">
                <div class="w-12 h-12 bg-slate-800 rounded-lg flex items-center justify-center mb-4 text-2xl group-hover:scale-110 transition">🎲</div>
                <h3 class="font-cinzel text-xl text-amber-500 font-bold mb-2">RPG Textual</h3>
                <p class="text-slate-400 text-sm leading-relaxed">Uma narrativa profunda onde sua imaginação é o melhor gráfico. Reviva a era de ouro dos jogos de texto com mecânicas modernas.</p>
            </div>

            <div class="bg-slate-900/50 p-6 rounded-xl border border-slate-800 hover:border-amber-500/50 transition duration-300 backdrop-blur-sm group">
                <div class="w-12 h-12 bg-slate-800 rounded-lg flex items-center justify-center mb-4 text-2xl group-hover:scale-110 transition">⚔️</div>
                <h3 class="font-cinzel text-xl text-amber-500 font-bold mb-2">Combate & Atributos</h3>
                <p class="text-slate-400 text-sm leading-relaxed">Gerencie Habilidade, Energia e Sorte. Cada batalha é calculada, cada item encontrado pode ser a diferença entre a vida e a morte.</p>
            </div>

            <div class="bg-slate-900/50 p-6 rounded-xl border border-slate-800 hover:border-amber-500/50 transition duration-300 backdrop-blur-sm group">
                <div class="w-12 h-12 bg-slate-800 rounded-lg flex items-center justify-center mb-4 text-2xl group-hover:scale-110 transition">👑</div>
                <h3 class="font-cinzel text-xl text-amber-500 font-bold mb-2">Consequências Reais</h3>
                <p class="text-slate-400 text-sm leading-relaxed">Suas escolhas alteram o rumo da história. Desbloqueie conquistas, encontre finais secretos e torne-se uma lenda.</p>
            </div>

        </div>

    </main>

    <footer class="relative z-10 w-full border-t border-slate-800 bg-slate-950 py-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-slate-500 text-sm mb-2">
                Desenvolvido com <span class="text-red-500">❤</span> e <strong>Laravel</strong>.
            </p>
            <p class="text-slate-600 text-xs">
                © {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados aos dados, mas o código é livre para aprendizado.
            </p>
        </div>
    </footer>

</body>
</html>
