<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel RPG') }}</title>

        <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-300 antialiased bg-slate-950">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-black">

            <div class="mb-6 text-center">
                <a href="/" class="flex flex-col items-center group">
                    <span class="text-4xl mb-2 group-hover:scale-110 transition duration-300">⚔️</span>
                    <h1 class="font-cinzel text-3xl font-bold text-amber-500 tracking-widest drop-shadow-md group-hover:text-amber-400 transition">
                        {{ config('app.name', 'RPG GAME') }}
                    </h1>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-8 py-8 bg-slate-800 border border-slate-700 shadow-[0_0_40px_-10px_rgba(0,0,0,0.7)] overflow-hidden sm:rounded-xl relative">
                <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-amber-600/50 to-transparent"></div>

                {{ $slot }}
            </div>

            <div class="mt-8 text-slate-600 text-xs">
                &copy; {{ date('Y') }} O Destino aguarda.
            </div>
        </div>
    </body>
</html>
