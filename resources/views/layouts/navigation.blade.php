<nav x-data="{ open: false }" class="sticky top-0 z-40 backdrop-blur bg-[#04050d]/80 border-b border-white/10 text-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex justify-between items-center">
        <div class="flex items-center gap-8">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <img src="{{ asset('images/nexora-mark.png') }}" alt="NEXORA" class="h-9 w-auto">
            </a>
            <a href="{{ route('admin.dashboard') }}" class="hidden sm:inline text-sm hover:text-cyan-300 transition">Dashboard</a>
            <a href="{{ route('profile.edit') }}" class="hidden sm:inline text-sm hover:text-cyan-300 transition {{ request()->routeIs('profile.edit') ? 'text-cyan-300' : '' }}">{{ __('Profile') }}</a>
        </div>

        <div class="hidden sm:flex items-center gap-4 text-sm">
            <span class="text-slate-400">{{ Auth::user()->name ?? '' }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-white">{{ __('Log Out') }}</button>
            </form>
        </div>

        <button @click="open = !open" class="sm:hidden p-2 text-slate-300" aria-label="Menu">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path x-show="!open" stroke-linecap="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16"/>
                <path x-show="open" x-cloak stroke-linecap="round" stroke-width="2" d="M6 6l12 12M18 6L6 18"/>
            </svg>
        </button>
    </div>

    <div x-show="open" x-cloak x-transition class="sm:hidden px-4 pb-4 space-y-3 text-sm">
        <a href="{{ route('admin.dashboard') }}" class="block">Dashboard</a>
        <a href="{{ route('profile.edit') }}" class="block">{{ __('Profile') }}</a>
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit" class="text-slate-400">{{ __('Log Out') }}</button></form>
    </div>
</nav>
