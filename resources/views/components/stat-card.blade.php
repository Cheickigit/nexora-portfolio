@props(['icon', 'value', 'label'])

<div class="relative p-[1px] rounded-2xl bg-gradient-to-br from-white/10 to-transparent group overflow-hidden transition-all duration-300 hover:-translate-y-1 hover:shadow-[0_0_20px_rgba(0,212,255,0.15)]">
    <!-- Fond intérieur de la carte -->
    <div class="relative h-full w-full bg-[#0a0c16] rounded-2xl p-5 flex items-center gap-4 z-10">
        <!-- Icône lumineuse -->
        <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 group-hover:scale-110 group-hover:bg-cyan-500/20 transition-all duration-300">
            <x-icon :name="$icon" class="w-6 h-6" />
        </div>
        <!-- Texte -->
        <div>
            <div class="text-3xl font-bold text-white group-hover:text-cyan-300 transition-colors">
                <span data-count="{{ $value }}">0</span>
            </div>
            <div class="text-xs text-slate-400 uppercase tracking-widest mt-1 font-semibold">{{ $label }}</div>
        </div>
    </div>
    
    <!-- Effet de lumière au survol -->
    <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/0 via-cyan-500/10 to-cyan-500/0 opacity-0 group-hover:opacity-100 transition-opacity duration-500 z-0"></div>
</div>