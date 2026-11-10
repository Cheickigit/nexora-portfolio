@props(['experiences'])
@php $l = app()->getLocale(); @endphp

<div class="relative space-y-8 pl-10">
    <div class="tl-line"></div>
    @foreach($experiences as $e)
        @php
            $role    = data_get($e, 'role_'.$l) ?? data_get($e, 'title_'.$l) ?? data_get($e, 'position_'.$l) ?? data_get($e, 'title') ?? data_get($e, 'role') ?? '';
            $company = data_get($e, 'company_'.$l) ?? data_get($e, 'company') ?? data_get($e, 'organization') ?? data_get($e, 'school') ?? '';
            $period  = data_get($e, 'period_'.$l) ?? data_get($e, 'period')
                       ?? trim((data_get($e, 'start_date') ?? '').' — '.(data_get($e, 'end_date') ?? ($l === 'fr' ? 'Présent' : 'Present')), ' —');
            $desc    = data_get($e, 'description_'.$l) ?? data_get($e, 'description') ?? '';
        @endphp
        <div class="relative reveal" style="transition-delay: {{ $loop->index * 100 }}ms">
            <span class="tl-dot" style="left:-2.5rem"></span>
            <div class="glass p-5">
                <div class="flex flex-wrap items-baseline justify-between gap-2">
                    <h3 class="font-bold text-lg">{{ $role }}</h3>
                    <span class="text-xs font-mono text-cyan-300">{{ $period }}</span>
                </div>
                @if($company)<p class="text-sm text-slate-400 mt-1">{{ $company }}</p>@endif
                @if($desc)<p class="text-sm text-slate-300 mt-3 leading-relaxed">{{ $desc }}</p>@endif
            </div>
        </div>
    @endforeach
</div>
