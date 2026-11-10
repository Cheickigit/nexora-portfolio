@props(['text'])
@php
    $unit = mb_strtoupper(trim(\Illuminate\Support\Str::limit($text, 40, ''))) . ' • ';
    $label = str_repeat($unit, max(1, (int) round(40 / max(1, mb_strlen($unit)))));
    $id = 'ring-' . md5($text);
@endphp

<div aria-hidden="true" class="w-72 h-72 animate-[spin_30s_linear_infinite] motion-reduce:animate-none">
    <svg viewBox="0 0 100 100" class="w-full h-full overflow-visible">
        <defs><path id="{{ $id }}" d="M 50,50 m -37,0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0" /></defs>
        <text class="fill-cyan-400 font-bold" style="font-size:7.5px">
            <textPath href="#{{ $id }}" textLength="232" lengthAdjust="spacing">{{ $label }}</textPath>
        </text>
    </svg>
</div>