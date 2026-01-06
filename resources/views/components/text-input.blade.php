@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-slate-700 bg-slate-950 text-slate-200 focus:border-amber-500 focus:ring-amber-500 rounded-md shadow-sm w-full placeholder-slate-600 transition duration-200 ease-in-out']) !!}>
