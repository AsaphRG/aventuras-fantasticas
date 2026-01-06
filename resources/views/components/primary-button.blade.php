<button {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-4 py-3 bg-amber-600 border border-transparent rounded-md font-cinzel font-bold text-xs text-white uppercase tracking-widest hover:bg-amber-500 focus:bg-amber-700 active:bg-amber-800 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 focus:ring-offset-slate-800 transition ease-in-out duration-150 w-full shadow-lg']) }}>
    {{ $slot }}
</button>
