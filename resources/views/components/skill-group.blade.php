@props(['group'])
@php
    $l = app()->getLocale();
    $title = data_get($group, 'label_'.$l) ?: data_get($group, 'label_fr') ?: data_get($group, 'title_'.$l) ?: ucfirst((string) data_get($group, 'category', ''));
    $items = data_get($group, 'items') ?? [];
    $key = strtolower($title . ' ' . data_get($group, 'category', ''));
    $icon = match (true) {
        str_contains($key, 'back') => 'server',
        str_contains($key, 'front') => 'monitor',
        str_contains($key, 'donn') || str_contains($key, 'data') || str_contains($key, 'base') => 'database',
        str_contains($key, 'out') || str_contains($key, 'tool') => 'wrench',
        str_contains($key, 'lang') => 'languages',
        str_contains($key, 'analy') || str_contains($key, 'financ') || str_contains($key, 'risque') => 'chart-column',
        default => 'sparkles',
    };
@endphp
<div class="glass p-6 h-full hover:!transform-none">
    <div class="flex items-center gap-3 mb-5">
        <span class="ic-chip"><x-icon :name="$icon" /></span>
        <h3 class="text-lg font-bold">{{ $title }}</h3>
    </div>
    <div class="flex flex-wrap gap-2">
        @foreach($items as $it)
            @php $name = is_string($it) ? $it : (data_get($it, 'name_'.$l) ?? data_get($it, 'name') ?? data_get($it, 'label') ?? ''); @endphp
            <span class="text-sm px-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-slate-200">{{ $name }}</span>
        @endforeach
    </div>
</div>
