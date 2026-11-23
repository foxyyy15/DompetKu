@props([
    'size' => 'md',
    'showText' => true,
    'subtitle' => null,
])

@php
    $iconClasses = match ($size) {
        'sm' => 'h-7 w-7',
        'lg' => 'h-10 w-10',
        default => 'h-8 w-8',
    };
    $textClasses = match ($size) {
        'sm' => 'text-sm',
        'lg' => 'text-xl',
        default => 'text-base',
    };
@endphp

<div {{ $attributes->merge(['class' => 'inline-flex items-center gap-2']) }}>
    <img src="{{ asset('images/logo-dompetku.png') }}"
         alt="Logo DompetKu"
         class="{{ $iconClasses }} brand-logo-img shrink-0 object-contain">

    @if ($showText)
        <div class="flex flex-col leading-none">
            <span class="{{ $textClasses }} brand-logo-text font-extrabold tracking-tight">
                <span class="text-emerald-200">Dompet</span><span class="text-emerald-500">Ku</span>
            </span>
            @if ($subtitle)
                <span class="mt-0.5 text-[10px] font-semibold tracking-[0.16em] text-slate-400 uppercase">{{ $subtitle }}</span>
            @endif
        </div>
    @endif
</div>
