@extends('layouts.app')

@php 
    $locale = app()->getLocale(); 
    $projectTitle = locale_pick($project, 'title');
@endphp

@section('title', $projectTitle . ' — ' . ($currentPortfolio?->displayName() ?? ''))

@section('content')
<div class="relative overflow-hidden">
    <div class="absolute inset-0 bg-grid opacity-60"></div>
    <div class="orb w-80 h-80 bg-[#1f6bff] -top-20 -right-20"></div>

    <article class="relative max-w-3xl mx-auto px-4 py-20">
        <a href="{{ route('home', ['locale' => $locale]) }}#projects" class="reveal inline-block text-sm text-slate-400 hover:text-cyan-300 transition">
            {{ __('ui.project.back') }}
        </a>

        <header class="reveal mt-6 mb-12">
            <h1 class="text-4xl md:text-5xl font-bold mb-5 text-gradient">{{ $projectTitle }}</h1>
            
            @if($project->confidential)
                <span class="inline-block text-xs px-3 py-1 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/30 mb-4">🔒 {{ __('ui.project.confidential') }}</span>
            @endif
            
            <div class="flex flex-wrap gap-2">
                @foreach(($project->stack ?? []) as $tech)
                    <span class="text-xs px-3 py-1 rounded-full bg-white/5 border border-white/10 text-cyan-200 font-mono">{{ $tech }}</span>
                @endforeach
                @foreach(collect($project->meta ?? [])->flatMap(fn ($v) => explode(',', (string) $v))->map(fn ($v) => trim($v))->filter() as $badge)
                    <span class="text-xs px-3 py-1 rounded-full bg-white/5 border border-white/10 text-cyan-200 font-mono">{{ $badge }}</span>
                @endforeach
            </div>
        </header>

        <div class="space-y-6">
            @foreach(['context', 'role', 'challenges', 'result'] as $i => $key)
                @php $content = locale_pick($project, $key); @endphp
                @if($content)
                    <section class="glass reveal p-6 hover:!transform-none" style="transition-delay: {{ $i * 80 }}ms">
                        <h2 class="eyebrow mb-3">{{ __('ui.project.' . $key) }}</h2>
                        <p class="text-slate-300 leading-relaxed">{{ $content }}</p>
                    </section>
                @endif
            @endforeach
        </div>

        @if($project->demo_url || $project->repo_url)
            <div class="reveal mt-12 flex flex-wrap gap-4">
                @if($project->demo_url)<a href="{{ $project->demo_url }}" target="_blank" class="btn-primary">{{ __('ui.project.demo') }}</a>@endif
                @if($project->repo_url)<a href="{{ $project->repo_url }}" target="_blank" class="btn-ghost">{{ __('ui.project.repo') }}</a>@endif
            </div>
        @endif
    </article>
</div>
@endsection