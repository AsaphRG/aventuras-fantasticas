@extends('layouts.game')

@section('title', 'Todas as Histórias')

@section('header-nav')
    <a href="{{ url('/adventure_choice') }}" class="text-slate-400 hover:text-amber-500 transition">Voltar</a>
@endsection

@section('content')
    <div class="flex justify-between items-end mb-8 border-b border-slate-800 pb-4">
        <div>
            <h1 class="font-cinzel text-3xl md:text-4xl text-white drop-shadow-md">Todas as Histórias</h1>
            <p class="text-slate-400 text-sm mt-1">Uma visão geral de todas as narrativas disponíveis.</p>
        </div>
    </div>

    <div class="space-y-8">
        @foreach ($stories as $story)
            <div class="bg-slate-800/80 backdrop-blur-sm p-8 rounded-xl border border-slate-700 shadow-2xl">
                <h2 class="font-cinzel text-2xl text-amber-500 mb-4 border-b border-slate-700 pb-2">{{ $story->title }}</h2>
                <div class="prose prose-invert max-w-none text-slate-300">
                    {!! $story->history !!}
                </div>
            </div>
        @endforeach
    </div>
@endsection
