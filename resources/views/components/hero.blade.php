@props(['profile'])
@php
    $locale = app()->getLocale();
    $words = $locale === 'fr'
        ? ['sites web', 'applications', 'automatisations', 'solutions IA']
        : ['websites', 'applications', 'automations', 'AI solutions'];
@endphp

<section class="relative min-h-screen flex items-center overflow-hidden pt-24">
    <div class="absolute inset-0 bg-grid"></div>
    <canvas id="particles" class="absolute inset-0 w-full h-full pointer-events-none"></canvas>
    <div class="orb w-96 h-96 bg-[#1f6bff] -top-24 -left-24"></div>
    <div class="orb w-80 h-80 bg-[#00d4ff] bottom-0 right-0" style="animation-delay:-4s"></div>

    <div class="relative max-w-5xl mx-auto px-4 w-full grid md:grid-cols-2 gap-10 items-center">
        <div>
            <p class="reveal eyebrow mb-5">● {{ $locale === 'fr' ? 'Disponible pour de nouveaux projets' : 'Available for new projects' }}</p>

            <h1 class="reveal text-4xl md:text-6xl font-bold leading-tight" style="transition-delay:100ms">
                {{ $locale === 'fr' ? 'Je conçois des' : 'I build' }}
                <span class="text-gradient block min-h-[1.2em]"
                      x-data="{ words: @js($words), i: 0, txt: '', del: false }"
                      x-init="setInterval(() => {
                          const w = words[i];
                          txt = del ? w.slice(0, txt.length - 1) : w.slice(0, txt.length + 1);
                          if (!del && txt === w) { del = true; }
                          else if (del && txt === '') { del = false; i = (i + 1) % words.length; }
                      }, 110)"
                      x-text="txt + '|'">&nbsp;</span>
            </h1>

            <p class="reveal mt-6 text-slate-400 max-w-md" style="transition-delay:200ms">
                {{ config('portfolio.profile.tagline_' . $locale) }}
            </p>

            <div class="reveal mt-10 flex flex-wrap gap-4" style="transition-delay:300ms">
                <a href="#projects" class="btn-primary">{{ $locale === 'fr' ? 'Voir mes projets' : 'See my work' }}</a>
                <a href="#contact" class="btn-ghost">{{ $locale === 'fr' ? 'Me contacter' : 'Contact me' }}</a>
            </div>
        </div>

        <div class="reveal flex justify-center" style="transition-delay:200ms">
            <img src="{{ asset('images/nexora-mark.png') }}" alt="NEXORA" class="float logo-glow w-56 md:w-80 h-auto">
        </div>
    </div>
</section>
