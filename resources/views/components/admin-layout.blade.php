@props(['header' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin — NEXORA</title>
    <link rel="icon" type="image/png" href="{{ asset('images/nexora-mark.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="admin-body antialiased text-slate-100">
    <div id="scroll-progress"></div>

    <nav class="sticky top-0 z-40 backdrop-blur bg-[#04050d]/80 border-b border-white/10">
        <div class="max-w-7xl mx-auto px-4 py-3 flex flex-wrap gap-y-2 justify-between items-center">
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                    <img src="{{ asset('images/nexora-mark.png') }}" alt="NEXORA" class="h-8 w-auto">
                    <span class="font-bold text-lg">Admin</span>
                </a>
                <a href="{{ route('admin.dashboard') }}" class="text-sm hover:text-cyan-300 transition {{ request()->routeIs('admin.dashboard') ? 'text-cyan-300' : 'text-slate-300' }}">Dashboard</a>
                <a href="{{ route('admin.projects.index') }}" class="text-sm hover:text-cyan-300 transition {{ request()->routeIs('admin.projects.*') ? 'text-cyan-300' : 'text-slate-300' }}">{{ isset($currentPortfolio) ? ($currentPortfolio->profileConfig()['projects_label']['fr'] ?? 'Projets') : 'Projets' }}</a>
                <a href="{{ route('admin.profile') }}" class="text-sm hover:text-cyan-300 transition {{ request()->routeIs('admin.profile') ? 'text-cyan-300' : 'text-slate-300' }}">Profil</a>
                @foreach(['experiences' => 'Expériences', 'educations' => 'Formations', 'skills' => 'Compétences', 'languages' => 'Langues', 'certifications' => 'Certifications'] as $key => $text)
                    <a href="{{ route('admin.sections', $key) }}" class="text-sm hover:text-cyan-300 transition {{ request()->route('section') === $key ? 'text-cyan-300' : 'text-slate-300' }}">{{ $text }}</a>
                @endforeach
            </div>
            <div class="flex gap-4 items-center text-sm">
                <a href="{{ route('home', ['locale' => 'fr']) }}" target="_blank" class="hover:text-cyan-300 transition">Voir le site →</a>
                <span class="text-slate-500 hidden sm:inline">{{ auth()->user()->name ?? '' }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-slate-400 hover:text-white">Déconnexion</button>
                </form>
            </div>
        </div>
    </nav>

    @if ($header)
        <header class="border-b border-white/10">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">{{ $header }}</div>
        </header>
    @endif

    <main class="py-8">{{ $slot }}</main>

    @livewireScriptConfig
</body>
</html>
