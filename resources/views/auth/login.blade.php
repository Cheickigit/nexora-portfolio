<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-3xl font-bold text-white mb-2">Accès Admin</h2>
        <p class="text-sm text-slate-400">Connectez-vous pour gérer votre portfolio.</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-cyan-300" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6">
        @csrf

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-1.5">Adresse Email</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-cyan-400 transition-colors">
                    <x-icon name="mail" class="w-5 h-5" />
                </div>
                <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" 
                       class="w-full pl-11 pr-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:bg-[#0a0c16] focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/50 outline-none transition-all"
                       placeholder="vous@exemple.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-400 text-xs" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-slate-300 mb-1.5">Mot de passe</label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-cyan-400 transition-colors">
                    <x-icon name="lock" class="w-5 h-5" />
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" 
                       class="w-full pl-11 pr-4 py-3 rounded-xl bg-white/5 border border-white/10 text-white placeholder-slate-500 focus:bg-[#0a0c16] focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/50 outline-none transition-all"
                       placeholder="••••••••">
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-400 text-xs" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" name="remember" 
                       class="rounded border-white/20 bg-white/5 text-cyan-500 focus:ring-cyan-500/50 focus:ring-offset-0 transition-colors cursor-pointer">
                <span class="ml-2 text-sm text-slate-400 group-hover:text-slate-200 transition">Se souvenir de moi</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-slate-400 hover:text-cyan-300 transition" href="{{ route('password.request') }}">
                    Mot de passe oublié ?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div>
            <button type="submit" class="w-full relative group overflow-hidden rounded-xl bg-cyan-500 hover:bg-cyan-400 text-[#04050d] font-bold py-3.5 px-4 transition-all duration-300 shadow-[0_0_20px_rgba(0,212,255,0.3)] hover:shadow-[0_0_30px_rgba(0,212,255,0.5)]">
                <span class="relative z-10 flex items-center justify-center gap-2">
                    Se connecter
                    <x-icon name="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform" />
                </span>
            </button>
        </div>
    </form>
</x-guest-layout>