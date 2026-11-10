<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'NEXORA') }} — Connexion</title>
    <link rel="icon" type="image/png" href="{{ asset('images/nexora-mark.png') }}">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="public-site text-slate-100 antialiased overflow-hidden h-screen flex items-center justify-center relative bg-[#04050d]">

    {{-- FOND MATRIX ANIMÉ ET DYNAMIQUE --}}
    <x-matrix-background :columns="50" color="#00d4ff" />

    {{-- Orbes de lumière (Superposés au Matrix) --}}
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="orb w-[30rem] h-[30rem] bg-[#1f6bff] -top-24 -left-24 opacity-20"></div>
        <div class="orb w-96 h-96 bg-[#00d4ff] -bottom-20 -right-20 opacity-10" style="animation-delay: -3s"></div>
    </div>

    {{-- Conteneur principal --}}
    <div class="relative w-full sm:max-w-md px-6 z-10">
        
        {{-- Logo --}}
        <div class="flex justify-center mb-8">
            <a href="/" class="flex items-center gap-3 group">
                <img src="{{ asset('images/nexora-mark.png') }}" alt="NEXORA" class="h-12 w-auto logo-glow transition-transform duration-500 group-hover:scale-110">
                <img src="{{ asset('images/nexora-wordmark.png') }}" alt="NEXORA" class="h-5 w-auto hidden sm:block">
            </a>
        </div>

        {{-- Carte de connexion (Glassmorphism + Spotlight) --}}
        <div x-data="{ x: 0, y: 0 }" 
             @mousemove="
                 const rect = $el.getBoundingClientRect();
                 x = $event.clientX - rect.left;
                 y = $event.clientY - rect.top;
             }"
             :style="`--mouse-x: ${x}px; --mouse-y: ${y}px;`"
             class="relative p-8 sm:p-10 rounded-3xl bg-[#0a0c16]/80 backdrop-blur-2xl border border-white/10 shadow-2xl overflow-hidden">
             
            <!-- Effet spotlight -->
            <div class="absolute inset-0 z-0 pointer-events-none opacity-0 hover:opacity-100 transition-opacity duration-500" 
                 style="background: radial-gradient(600px circle at var(--mouse-x) var(--mouse-y), rgba(0, 212, 255, 0.15), transparent 40%);"></div>

            <div class="relative z-10">
                {{ $slot }}
            </div>
        </div>

        {{-- Lien retour --}}
        <div class="text-center mt-8">
            <a href="/" class="text-sm text-slate-400 hover:text-cyan-300 transition flex items-center justify-center gap-2">
                &larr; Retour au portfolio
            </a>
        </div>
    </div>

</body>
</html>