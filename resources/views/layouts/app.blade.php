@php
    // URL de la page courante dans une autre langue (garde le slug du portfolio et le slug du projet)
    $localeUrl = function (string $loc) {
        $route = request()->route();

        if (! $route || ! $route->getName()) {
            return url('/');
        }

        return route($route->getName(), array_merge($route->parameters(), ['locale' => $loc]));
    };
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('portfolio.profile.name'))</title>
    <meta name="description" content="@yield('description', config('portfolio.profile.tagline_' . app()->getLocale()))">
    <meta name="theme-color" content="#04050d">
    <link rel="icon" type="image/png" href="{{ asset('images/nexora-mark.png') }}">

    @php
        $currentLocale = app()->getLocale();
        $rawPath = request()->path();
        $pathWithoutLocale = preg_replace('#^(fr|en)(/|$)#', '', $rawPath);
        $suffix = $pathWithoutLocale ? '/' . $pathWithoutLocale : '';
    @endphp

    <link rel="alternate" hreflang="fr" href="{{ $localeUrl('fr') }}" />
    <link rel="alternate" hreflang="en" href="{{ $localeUrl('en') }}" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="public-site text-slate-100 antialiased">

    <div id="scroll-progress"></div>
    <div class="cursor-glow"></div>

    <header x-data="{ open: false }" class="fixed top-3 inset-x-3 z-50">
        <div class="glass !rounded-full max-w-5xl mx-auto px-5 h-14 flex items-center justify-between hover:!transform-none">
            <a href="{{ route('home', ['locale' => $currentLocale]) }}" class="flex items-center gap-2">
                <img src="{{ asset('images/nexora-mark.png') }}" alt="NEXORA" class="h-8 w-auto">
                <img src="{{ asset('images/nexora-wordmark.png') }}" alt="" class="h-4 w-auto hidden sm:block">
            </a>

            <nav class="hidden md:flex items-center gap-7 text-sm text-slate-300">
                <a href="#projects" class="hover:text-cyan-300 transition">{{ __('ui.nav.projects') }}</a>
                <a href="#skills" class="hover:text-cyan-300 transition">{{ __('ui.nav.skills') }}</a>
                <a href="#path" class="hover:text-cyan-300 transition">{{ __('ui.nav.path') }}</a>
                <a href="#contact" class="hover:text-cyan-300 transition">{{ __('ui.nav.contact') }}</a>
            </nav>

            <div class="flex items-center gap-3 text-sm">
                <a href="{{ $localeUrl('fr') }}" class="{{ $currentLocale === 'fr' ? 'text-white font-semibold' : 'text-slate-400 hover:text-white' }}">FR</a>
                <span class="text-slate-600">|</span>
                <a href="{{ $localeUrl('en') }}" class="{{ $currentLocale === 'en' ? 'text-white font-semibold' : 'text-slate-400 hover:text-white' }}">EN</a>

                @auth
                    <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline-block ml-2 px-3 py-1.5 rounded-full btn-primary !py-1.5 !px-4 text-xs">Admin</a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-block ml-2 text-slate-300 hover:text-white">Login</a>
                @endauth

                <button @click="open = !open" class="md:hidden ml-1 p-1 text-slate-300" aria-label="Menu">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path x-show="!open" stroke-linecap="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/>
                        <path x-show="open" x-cloak stroke-linecap="round" stroke-width="2" d="M6 6l12 12M18 6L6 18"/>
                    </svg>
                </button>
            </div>
        </div>

        <div x-show="open" x-cloak x-transition @click="open = false"
             class="md:hidden glass max-w-5xl mx-auto mt-2 p-4 flex flex-col gap-3 text-slate-200 hover:!transform-none">
            <a href="#projects">{{ __('ui.nav.projects') }}</a>
            <a href="#skills">{{ __('ui.nav.skills') }}</a>
            <a href="#path">{{ __('ui.nav.path') }}</a>
            <a href="#contact">{{ __('ui.nav.contact') }}</a>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="relative border-t border-white/10 mt-24 py-10">
        <div class="max-w-5xl mx-auto px-4 flex flex-col items-center gap-4 text-center">
            <img src="{{ asset('images/nexora-logo.png') }}" alt="NEXORA" class="h-24 w-auto opacity-90">
            <p class="text-sm text-slate-500">
                © {{ date('Y') }} NEXORA — {{ config('portfolio.profile.name') }} — {{ __('ui.footer.rights') }}
            </p>
        </div>
    </footer>

    @livewireScriptConfig
</body>
</html>
