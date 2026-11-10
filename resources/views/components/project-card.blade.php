@props(['project', 'featured' => false, 'icon' => 'layers'])

@php
    $locale = app()->getLocale();
    $typeLabels = [
        'open_source'        => ['fr' => 'Open source', 'en' => 'Open source'],
        'mission'            => ['fr' => 'Mission', 'en' => 'Assignment'],
        'case_study'         => ['fr' => 'Étude de cas', 'en' => 'Case study'],
        'internship_mission' => ['fr' => 'Mission de stage', 'en' => 'Internship assignment'],
        'thesis'             => ['fr' => 'Mémoire', 'en' => 'Thesis'],
        'analysis'           => ['fr' => 'Analyse', 'en' => 'Analysis'],
        'publication'        => ['fr' => 'Publication', 'en' => 'Publication'],
        'moot_court'         => ['fr' => 'Plaidoirie', 'en' => 'Moot court'],
        'campaign'           => ['fr' => 'Campagne', 'en' => 'Campaign'],
    ];
    $typeLabel = $typeLabels[$project->type ?? ''][$locale] ?? null;
    
    $stack = $project->stack ?? [];
    $badges = collect($stack)->merge(collect($project->meta ?? [])->flatMap(fn ($v) => explode(',', (string) $v)))->map(fn ($v) => trim($v))->filter()->take(5);
    
    $img = $project->image ?? null;
    if ($img && ! preg_match('#^(https?:)?//|^/#', $img)) {
        $img = file_exists(public_path($img)) ? asset($img) : asset('storage/' . $img);
    }
@endphp

<div class="{{ $featured ? 'lg:col-span-2' : '' }} h-full" 
     x-show="tag === 'all' || {{ \Illuminate\Support\Js::from($stack) }}.includes(tag)" 
     x-transition.opacity.duration.300ms>
    
    <!-- CARTE PREMIUM AVEC EFFET SPOTLIGHT -->
    <a href="{{ route('projects.show', ['locale' => $locale, 'slug' => $project->slug]) }}" 
       x-data="{ x: 0, y: 0 }"
       @mousemove="
           const rect = $el.getBoundingClientRect();
           x = $event.clientX - rect.left;
           y = $event.clientY - rect.top;
       }"
       :style="`--mouse-x: ${x}px; --mouse-y: ${y}px;`"
       class="group relative flex flex-col h-full bg-[#0a0c16] rounded-3xl border border-white/5 transition-all duration-500 hover:border-white/15 hover:shadow-2xl overflow-hidden block">
        
        <!-- L'EFFET DE PROJECTEUR (Suit la souris) -->
        <div class="absolute inset-0 z-0 pointer-events-none opacity-0 group-hover:opacity-100 transition-opacity duration-500" 
             style="background: radial-gradient(600px circle at var(--mouse-x) var(--mouse-y), rgba(0, 212, 255, 0.1), transparent 40%);"></div>

        <!-- EN-TÊTE : IMAGE DE COUVERTURE -->
        <div class="relative w-full {{ $featured ? 'h-64' : 'h-48' }} overflow-hidden z-10 shrink-0">
            @if($img)
                <img src="{{ $img }}" alt="{{ locale_pick($project, 'title') }}" loading="lazy" 
                     class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110">
            @else
                <div class="w-full h-full bg-[#0f1423] flex items-center justify-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-grid opacity-20"></div>
                    <x-icon :name="$icon" class="w-20 h-20 text-white/5" />
                </div>
            @endif
            
            <!-- Dégradé pour une transition douce avec le texte -->
            <div class="absolute inset-0 bg-gradient-to-t from-[#0a0c16] via-[#0a0c16]/40 to-transparent"></div>
            
            <!-- Badge Type (Glassmorphism) -->
            @if($typeLabel)
                <div class="absolute top-4 left-4 z-20">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[11px] uppercase tracking-wider font-bold rounded-full bg-black/40 backdrop-blur-md border border-white/10 text-cyan-300 shadow-lg">
                        <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                        {{ $typeLabel }}
                    </span>
                </div>
            @endif

            <!-- Badge Confidentiel -->
            @if($project->confidential)
                <div class="absolute top-4 right-4 z-20">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-[11px] font-bold rounded-full bg-amber-500/20 backdrop-blur-md border border-amber-500/30 text-amber-300 shadow-lg">
                        <x-icon name="lock" class="w-3 h-3" />
                        {{ $locale === 'fr' ? 'NDA' : 'NDA' }}
                    </span>
                </div>
            @endif
        </div>

        <!-- CORPS DE LA CARTE -->
        <div class="relative z-10 p-6 flex flex-col flex-1 -mt-6">
            <h3 class="text-xl font-bold text-white mb-2 leading-tight group-hover:text-cyan-300 transition-colors">
                {{ locale_pick($project, 'title') }}
            </h3>
            
            <p class="text-sm text-slate-400 line-clamp-2 leading-relaxed mb-4">
                {{ locale_pick($project, 'context') }}
            </p>
            
            @if(locale_pick($project, 'result'))
                <div class="flex gap-2 items-start p-3 rounded-xl bg-cyan-500/5 border border-cyan-500/10 mb-5">
                    <x-icon name="trending-up" class="w-4 h-4 text-cyan-400 shrink-0 mt-0.5" />
                    <span class="text-xs text-cyan-100/80 line-clamp-2 leading-relaxed">{{ locale_pick($project, 'result') }}</span>
                </div>
            @endif
            
            <!-- Technologies -->
            <div class="flex flex-wrap gap-1.5 mt-auto mb-5">
                @foreach($badges as $b)
                    <span class="text-[11px] px-2 py-1 rounded-md bg-white/5 border border-white/5 text-slate-300 font-mono">
                        {{ $b }}
                    </span>
                @endforeach
            </div>
            
            <!-- Footer : Lien -->
            <div class="mt-auto pt-4 border-t border-white/5 flex items-center justify-between">
                <span class="text-sm font-semibold text-cyan-400 group-hover:text-cyan-300">
                    {{ $locale === 'fr' ? 'Voir le projet' : 'View project' }}
                </span>
                <div class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center border border-white/5 transition-all duration-300 group-hover:bg-cyan-500/20 group-hover:border-cyan-500/30 group-hover:text-cyan-300 text-slate-400">
                    <x-icon name="arrow-right" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-0.5" />
                </div>
            </div>
        </div>
    </a>
</div>