<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <title>{{ config('app.name', 'Cidadela do Caos') }} - @yield('title', __('Playing'))</title>
    <link rel="shortcut icon" href="{{ asset('images/logo-nobg.png') }}" type="image/x-icon">

    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-900 text-slate-200 font-sans antialiased min-h-screen flex flex-col bg-[url('/images/bg-texture.png')] bg-repeat">

    <header class="w-full bg-slate-950 border-b border-slate-800 shadow-lg z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="font-cinzel text-xl font-bold text-amber-500 tracking-wider flex items-center gap-2">
                {{ config('app.name', 'RPG GAME') }}
            </div>

            <nav class="flex items-center gap-4">
                @if (\View::hasSection('header-nav'))
                    @yield('header-nav')
                @else
                    <a href="{{ url('/adventure_choice') }}" class="text-slate-400 hover:text-amber-500 transition text-sm">{{ __('Exit Story') }}</a>
                @endif
            </nav>
        </div>
    </header>

    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full">
        @if (\View::hasSection('sidebar'))
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                <div class="lg:col-span-2 flex flex-col gap-6 order-2 lg:order-1">
                    @yield('content')
                </div>
                <div class="lg:col-span-1 lg:sticky lg:top-8 order-1 lg:order-2">
                    @yield('sidebar')
                </div>
            </div>
        @else
            @yield('content')
        @endif
    </main>
    @stack('scripts')
</body>
</html>
