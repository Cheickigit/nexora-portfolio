@props(['icon', 'title', 'intro' => null, 'center' => false])
<header class="mb-10 flex gap-4 {{ $center ? 'flex-col items-center text-center' : 'items-start' }}">
    <span class="ic-chip"><x-icon :name="$icon" /></span>
    <div>
        <h2 class="text-3xl md:text-4xl font-bold leading-tight">{{ $title }}</h2>
        @if($intro)<p class="text-slate-400 mt-2 max-w-[60ch] {{ $center ? 'mx-auto' : '' }}">{{ $intro }}</p>@endif
    </div>
</header>