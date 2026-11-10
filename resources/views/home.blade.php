@extends('layouts.app')

@section('title', collect([$profile['name'] ?? null, $profile['title_' . app()->getLocale()] ?? null])->filter()->implode(' — '))

@section('content')
@php
    $locale = app()->getLocale();
    $other = $locale === 'fr' ? 'en' : 'fr';
    $fr = $locale === 'fr';

    $cfg = $portfolio->profileConfig();
    $isTech = $portfolio->profile_type === 'tech';
    $projectsLabel = $cfg['projects_label'][$locale] ?? ($fr ? 'Projets' : 'Projects');
    $projIcon = ['tech' => 'code', 'finance' => 'chart-column', 'law' => 'scale', 'marketing' => 'megaphone'][$portfolio->profile_type] ?? 'layers';
    $showStack = in_array('stack', $cfg['meta_fields'], true);
    $tags = collect($projects)->pluck('stack')->flatten()->filter()->unique()->values();
    $summary = $portfolio->{'summary_' . $locale} ?: $portfolio->{'summary_' . $other};

    // Fonction pour trouver le logo d'une techno dans public/
    $getTechIcon = function($name) {
        $clean = strtolower(trim($name));
        
        // Correspondances spécifiques
        $map = [
            'tailwind' => 'tailwindcss',
            'next.js' => 'nextdotjs',
            'node' => 'nodedotjs',
            'node.js' => 'nodedotjs',
            'vue' => 'vuedotjs',
            'vue.js' => 'vuedotjs',
            'chart.js' => 'chartdotjs',
            'alpine' => 'alpinedotjs',
            'alpine.js' => 'alpinedotjs',
            'html' => 'html5',
            'css' => 'css3',
            'google sheets' => 'googlesheets',
        ];

        $filename = $map[$clean] ?? $clean;
        
        // On cherche directement à la racine de public/
        $path = public_path("{$filename}.svg");

        return file_exists($path) ? asset("{$filename}.svg") : null;
    };

    // Texte "À propos" : les retours à la ligne d'un CV collé deviennent des paragraphes lisibles
    $paras = collect(preg_split('/\R{2,}/', trim((string) $summary)))->map(fn ($p) => trim(preg_replace('/\s*\R\s*/', ' ', $p)))->filter()->values();
    if ($paras->count() === 1 && mb_strlen($paras[0]) > 420) {
        $chunks = []; $cur = '';
        foreach (preg_split('/(?<=[.!?])\s+/', $paras[0]) as $s) {
            if ($cur !== '' && mb_strlen($cur) > 220) { $chunks[] = $cur; $cur = ''; }
            $cur = trim($cur . ' ' . $s);
        }
        if ($cur !== '') { $chunks[] = $cur; }
        $paras = collect($chunks);
    }

    $stats = collect([
        [count($projects), $projectsLabel, $projIcon],
        [count($experiences), $fr ? 'Expériences' : 'Experiences', 'briefcase'],
        $showStack ? [$tags->count(), 'Technologies', 'sparkles'] : [count($educations), $fr ? 'Formations' : 'Education', 'graduation-cap'],
        [count($skills), $fr ? 'Domaines de compétences' : 'Skill areas', 'layers'],
    ])->filter(fn ($s) => $s[0] > 0)->take(3)->values();

    $blocks = collect($cfg['sections'])->map(fn ($s) => match ($s) {
        'experiences', 'educations' => 'path',
        'languages', 'certifications' => 'extras',
        default => $s,
    })->unique()->values();
    $pathOrder = collect($cfg['sections'])->filter(fn ($s) => in_array($s, ['experiences', 'educations']))->values();
    $has = [
        'projects' => count($projects) > 0,
        'skills'   => count($skills) > 0,
        'path'     => count($experiences) + count($educations) > 0,
        'extras'   => count($languages) + count($certifications) > 0,
    ];
    $ico = fn ($label, $url) => match (true) {
        str_contains(strtolower($label . $url), 'linkedin') => 'linkedin',
        str_contains(strtolower($label . $url), 'github') => 'github',
        str_contains(strtolower($label . $url), 'wa.me'), str_contains(strtolower($label), 'whatsapp') => 'message-circle',
        default => 'globe',
    };
    $whatsapp = collect($portfolio->links ?? [])->first(fn ($l) => str_contains(strtolower(($l['label'] ?? '') . ($l['url'] ?? '')), 'whatsapp') || str_contains($l['url'] ?? '', 'wa.me'));
@endphp

    {{-- FOND MATRIX ANIMÉ (Très discret en arrière-plan) --}}
    <div class="absolute inset-0 h-screen z-0 opacity-20 pointer-events-none">
        <x-matrix-background :columns="30" color="#00d4ff" />
    </div>

    <div class="relative z-10">
        <x-hero :profile="$profile" />

        {{-- À PROPOS + CHIFFRES --}}
        <section id="about" class="max-w-6xl mx-auto px-4 py-24 relative overflow-hidden">
            <div class="absolute left-1/2 top-1/3 -translate-x-1/2 w-[36rem] h-[36rem] -z-10 pointer-events-none" style="background: radial-gradient(closest-side, rgba(0,212,255,.10), transparent)"></div>

            @if($paras->isNotEmpty())
                <div class="reveal max-w-3xl mx-auto mb-16 text-center">
                    <div class="flex items-center justify-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-cyan-500/10 flex items-center justify-center border border-cyan-500/20">
                            <x-icon name="user" class="w-5 h-5 text-cyan-400" />
                        </div>
                        <h2 class="text-3xl md:text-4xl font-bold text-white tracking-tight">{{ $fr ? 'À propos' : 'About' }}</h2>
                    </div>
                    <div class="space-y-5">
                        @foreach($paras as $p)
                            <p class="text-[17px] leading-8 text-slate-300">{{ $p }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($stats->isNotEmpty())
                <div class="relative">
                    @if(! empty($profile['title_' . $locale]))
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-20">
                            <x-circular-text :text="$profile['title_' . $locale]" />
                        </div>
                    @endif
                    <div class="relative z-10 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto place-items-center">
                        @foreach($stats as $index => [$n, $label, $ic])
                            <div class="reveal w-full flex justify-center" style="transition-delay: {{ $index * 100 }}ms">
                                <x-stat-floating :icon="$ic" :value="$n" :label="$label" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>

        {{-- BANDEAU DE TECHNOLOGIES (DÉFILEMENT CONTINU) --}}
        @if($showStack && $tags->count() >= 3)
            @php
                $loopItems = collect(range(1, max(1, (int) ceil(24 / $tags->count()))))->flatMap(fn () => $tags);
                $dur = max(30, $loopItems->count() * 2.4);
            @endphp
            <div class="relative py-8 border-y border-white/5 bg-[#0a0c16]/50 backdrop-blur-sm overflow-hidden" aria-label="Technologies">
                <div class="absolute inset-y-0 left-0 w-24 bg-gradient-to-r from-[#04050d] to-transparent z-10 pointer-events-none"></div>
                <div class="absolute inset-y-0 right-0 w-24 bg-gradient-to-l from-[#04050d] to-transparent z-10 pointer-events-none"></div>

                <div class="flex w-max animate-marquee hover:[animation-play-state:paused]" style="--dur: {{ $dur }}s">
                    @foreach([0, 1] as $copy)
                        <div class="flex items-center gap-4 pr-4" @if($copy) aria-hidden="true" @endif>
                            @foreach($loopItems as $t)
                                @php $logo = $getTechIcon($t); @endphp
                                <span class="group inline-flex items-center gap-2.5 text-sm px-5 py-2.5 rounded-full bg-white/[0.03] border border-white/5 text-slate-300 font-medium whitespace-nowrap hover:bg-white/10 hover:text-white transition-colors cursor-default">
                                    @if($logo)
                                        <!-- Ajout de brightness-0 et invert pour forcer la couleur blanche -->
                                        <img src="{{ $logo }}" alt="{{ $t }}" class="w-5 h-5 object-contain opacity-70 group-hover:opacity-100 transition-all brightness-0 invert">
                                    @else
                                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-500/50"></span>
                                    @endif
                                    {{ $t }}
                                </span>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @foreach($blocks as $block)
            @continue(! ($has[$block] ?? false))

            @if($block === 'projects')
                <section id="projects" class="max-w-6xl mx-auto px-4 py-20" x-data="{ tag: 'all' }">
                    <x-section-head :icon="$projIcon" :title="$isTech ? __('ui.sections.projects_title') : $projectsLabel" :intro="$isTech ? __('ui.sections.projects_intro') : null" />

                    @if($showStack && $tags->count() > 1)
                        <div class="flex flex-wrap gap-2 mb-8">
                            <button type="button" class="chip" :class="{ 'active': tag === 'all' }" @click="tag = 'all'">{{ $fr ? 'Tous' : 'All' }}</button>
                            @foreach($tags as $t)
                                <button type="button" class="chip" :class="{ 'active': tag === @js($t) }" @click="tag = @js($t)">{{ $t }}</button>
                            @endforeach
                        </div>
                    @endif

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($projects as $project)
                            <x-project-card :project="$project" :featured="$loop->first && count($projects) % 3 === 2" :icon="$projIcon" />
                        @endforeach
                    </div>
                </section>

            @elseif($block === 'skills')
                <section id="skills" class="max-w-6xl mx-auto px-4 py-20">
                    <x-section-head icon="sparkles" :title="$isTech ? __('ui.sections.skills_title') : ($fr ? 'Compétences' : 'Skills')" />
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($skills as $group)
                            <x-skill-group :group="$group" />
                        @endforeach
                    </div>
                </section>

            @elseif($block === 'path')
                <section id="path" class="max-w-6xl mx-auto px-4 py-20">
                    <x-section-head icon="briefcase" :title="__('ui.sections.path_title')" />

                    @foreach($pathOrder as $part)
                        @if($part === 'experiences' && count($experiences))
                            <h3 class="flex items-center gap-2 text-sm text-cyan-300 mb-4"><x-icon name="briefcase" />{{ $fr ? 'Expériences' : 'Experience' }}</h3>
                            <div class="mb-12"><x-timeline :experiences="$experiences" /></div>
                        @endif

                        @if($part === 'educations' && count($educations))
                            <h3 class="flex items-center gap-2 text-sm text-cyan-300 mb-4"><x-icon name="graduation-cap" />{{ $fr ? 'Formation' : 'Education' }}</h3>
                            <div class="grid md:grid-cols-2 gap-4 mb-12">
                                @foreach($educations as $ed)
                                    <div class="glass p-6 hover:!transform-none">
                                        <div class="flex flex-wrap justify-between gap-2">
                                            <h3 class="font-semibold">{{ locale_pick($ed, 'degree') }}</h3>
                                            @if($ed->year)<span class="text-sm text-cyan-300">{{ $ed->year }}</span>@endif
                                        </div>
                                        <p class="text-sm text-slate-400 mt-1">{{ $ed->school }}</p>
                                        @php $desc = locale_pick($ed, 'desc'); @endphp
                                        @if($desc)<p class="text-slate-300 mt-3 text-[15px] leading-relaxed">{{ $desc }}</p>@endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </section>

            @elseif($block === 'extras')
                <section id="extras" class="max-w-6xl mx-auto px-4 py-20">
                    <x-section-head icon="award" :title="count($certifications) && count($languages) ? ($fr ? 'Certifications & langues' : 'Certifications & languages') : (count($certifications) ? 'Certifications' : ($fr ? 'Langues' : 'Languages'))" />
                    <div class="grid md:grid-cols-2 gap-6">
                        @if(count($certifications))
                            <div class="glass p-6 space-y-4 hover:!transform-none">
                                @foreach($certifications as $c)
                                    <div class="flex items-start gap-3">
                                        <span class="ic-chip" style="width:2.25rem;height:2.25rem"><x-icon name="award" /></span>
                                        <div>
                                            <p class="font-semibold">{{ locale_pick($c, 'title') }}</p>
                                            <p class="text-sm text-slate-400">{{ $c->issuer }}@if($c->year), {{ $c->year }}@endif</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                        @if(count($languages))
                            <div class="glass p-6 space-y-4 hover:!transform-none">
                                @foreach($languages as $lang)
                                    @php $level = locale_pick($lang, 'level'); @endphp
                                    <div class="flex items-center gap-3">
                                        <span class="ic-chip" style="width:2.25rem;height:2.25rem"><x-icon name="languages" /></span>
                                        <p><span class="font-semibold">{{ locale_pick($lang, 'name') }}</span>@if($level)<span class="text-slate-400">, {{ $level }}</span>@endif</p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </section>
            @endif
        @endforeach

        {{-- CONTACT --}}
        <section id="contact" class="max-w-6xl mx-auto px-4 py-20">
            <x-section-head icon="mail" :title="__('ui.sections.contact_title')" :intro="__('ui.sections.contact_intro')" />
            <div class="grid lg:grid-cols-[1fr_1.1fr] gap-10">
                <div class="glass p-6 self-start hover:!transform-none">
                    @php
                        $rows = array_values(array_filter([
                            ! empty($profile['email']) ? ['mail', $fr ? 'E-mail' : 'Email', $profile['email'], 'mailto:' . $profile['email']] : null,
                            ! empty($profile['phone']) ? ['phone', $fr ? 'Téléphone' : 'Phone', $profile['phone'], 'tel:' . preg_replace('/[^0-9+]/', '', $profile['phone'])] : null,
                            ! empty($profile['location']) ? ['map-pin', $fr ? 'Localisation' : 'Location', $profile['location'], null] : null,
                        ]));
                        foreach (($portfolio->links ?? []) as $link) { $rows[] = [$ico($link['label'], $link['url']), $link['label'], preg_replace('#^https?://(www\.)?#', '', $link['url']), $link['url']]; }
                    @endphp
                    @foreach($rows as [$ic, $label, $value, $href])
                        <div class="flex items-center gap-4 py-3 {{ $loop->first ? '' : 'border-t border-white/10' }}">
                            <span class="ic-chip" style="width:2.5rem;height:2.5rem"><x-icon :name="$ic" /></span>
                            <div class="min-w-0">
                                <p class="text-xs text-slate-400">{{ $label }}</p>
                                @if($href)<a href="{{ $href }}" @if(str_starts_with($href, 'http')) target="_blank" rel="noopener" @endif class="font-medium text-slate-100 hover:text-cyan-300 break-words">{{ $value }}</a>
                                @else<p class="font-medium text-slate-100">{{ $value }}</p>@endif
                            </div>
                        </div>
                    @endforeach
                    @if($whatsapp)
                        <a href="{{ $whatsapp['url'] }}" target="_blank" rel="noopener" class="btn-primary mt-5 w-full inline-flex items-center justify-center gap-2"><x-icon name="message-circle" />{{ $fr ? 'Écrire sur WhatsApp' : 'Message on WhatsApp' }}</a>
                    @endif
                </div>
                <div><livewire:contact-form :portfolio-id="$portfolio->id" :lang="$locale" /></div>
            </div>
        </section>
    </div>
@endsection