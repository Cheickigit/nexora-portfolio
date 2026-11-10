<div class="glass p-6 hover:!transform-none">
    @if($sent)
        <div class="rounded-lg border border-cyan-400/30 bg-cyan-400/10 text-cyan-200 p-4">{{ __('ui.contact.success') }}</div>
    @else
        @if($failed)
            <div class="mb-4 rounded-lg border border-red-400/30 bg-red-400/10 text-red-200 p-4">
                {{ $lang === 'fr' ? "Votre message n'a pas pu être envoyé. Réessayez plus tard." : 'Your message could not be sent. Please try again later.' }}
            </div>
        @endif
        @if($limited)
            <div class="mb-4 rounded-lg border border-amber-400/30 bg-amber-400/10 text-amber-200 p-4">{{ $limited }}</div>
        @endif

        <form wire:submit="send" class="space-y-4">
            <div class="hidden" aria-hidden="true">
                <input type="text" wire:model="website" tabindex="-1" autocomplete="off">
            </div>

            @foreach(['name' => 'text', 'email' => 'email'] as $f => $type)
                <div>
                    <label class="block text-sm mb-1 text-slate-300">{{ __('ui.contact.' . $f) }}</label>
                    <input type="{{ $type }}" wire:model="{{ $f }}" class="field">
                    @error($f) <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
            @endforeach
            <div>
                <label class="block text-sm mb-1 text-slate-300">{{ __('ui.contact.message') }}</label>
                <textarea wire:model="message" rows="5" class="field"></textarea>
                @error('message') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="btn-primary" wire:loading.attr="disabled">
                <span wire:loading.remove>{{ __('ui.contact.send') }}</span><span wire:loading>…</span>
            </button>
        </form>
    @endif
</div>