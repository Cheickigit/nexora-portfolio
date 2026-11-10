@php
    $label = 'block text-sm mb-1 text-slate-300';
    $profiles = config('portfolio_profiles');
@endphp

<div class="max-w-4xl mx-auto px-4 space-y-6">

    @if($flash)
        <div class="p-4 rounded-lg border border-cyan-400/30 bg-cyan-400/10 text-cyan-200">{{ $flash }}</div>
    @endif

    <div class="panel p-6 space-y-4">
        <h3 class="font-semibold text-lg text-gradient">Identité</h3>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="{{ $label }}">Nom complet</label>
                <input type="text" wire:model="full_name" class="field">
                @error('full_name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="{{ $label }}">Titre professionnel</label>
                <input type="text" wire:model="title" placeholder="Étudiant en finance" class="field">
                @error('title') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="{{ $label }}">Email de contact</label>
                <input type="email" wire:model="email" class="field">
                @error('email') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="{{ $label }}">Téléphone</label>
                <input type="text" wire:model="phone" class="field">
                @error('phone') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="{{ $label }}">Ville / pays</label>
                <input type="text" wire:model="location" class="field">
                @error('location') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="flex items-center gap-4">
            @if($photo)
                <img src="{{ $photo->temporaryUrl() }}" alt="" class="h-20 w-20 rounded-full object-cover border border-white/10">
            @elseif($currentPhoto)
                <img src="{{ asset('storage/' . $currentPhoto) }}" alt="" class="h-20 w-20 rounded-full object-cover border border-white/10">
            @endif
            <div>
                <label class="{{ $label }}">Photo (image, 2 Mo max)</label>
                <input type="file" wire:model="photo" accept="image/*" class="text-sm text-slate-300">
                <div wire:loading wire:target="photo" class="text-xs text-slate-400 mt-1">Chargement…</div>
                @error('photo') <span class="text-red-400 text-xs block">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="panel p-6 space-y-4">
        <h3 class="font-semibold text-lg text-gradient">Logo</h3>
        <p class="text-xs text-slate-400">Affiché dans l'en-tête et le pied de page de votre portfolio. Sans logo, vos initiales sont utilisées.</p>
        <div class="flex items-center gap-4">
            @if($logo)
                <img src="{{ $logo->temporaryUrl() }}" alt="" class="h-16 w-auto rounded bg-white/5 p-1">
            @elseif($currentLogo)
                <img src="{{ asset('storage/' . $currentLogo) }}" alt="" class="h-16 w-auto rounded bg-white/5 p-1">
            @endif
            <div>
                <input type="file" wire:model="logo" accept="image/*" class="text-sm text-slate-300">
                <div wire:loading wire:target="logo" class="text-xs text-slate-400 mt-1">Chargement…</div>
                @error('logo') <span class="text-red-400 text-xs block">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    
    <div class="panel p-6 space-y-4">
        <h3 class="font-semibold text-lg text-gradient">Accroche et résumé</h3>
        <div class="grid md:grid-cols-2 gap-4">
            @foreach(['fr', 'en'] as $l)
                <div>
                    <label class="{{ $label }}">Accroche ({{ strtoupper($l) }})</label>
                    <input type="text" wire:model="tagline_{{ $l }}" class="field">
                    @error('tagline_' . $l) <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
            @endforeach
            @foreach(['fr', 'en'] as $l)
                <div>
                    <label class="{{ $label }}">Résumé professionnel ({{ strtoupper($l) }})</label>
                    <textarea wire:model="summary_{{ $l }}" rows="5" class="field"></textarea>
                    @error('summary_' . $l) <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
            @endforeach
        </div>
    </div>

    <div class="panel p-6 space-y-4">
        <h3 class="font-semibold text-lg text-gradient">Liens</h3>
        <p class="text-xs text-slate-400">Ex. LinkedIn, GitHub, WhatsApp, site personnel.</p>

        @foreach($links as $i => $link)
            <div class="grid md:grid-cols-[1fr_2fr_auto] gap-3 items-start" wire:key="link-{{ $i }}">
                <div>
                    <input type="text" wire:model="links.{{ $i }}.label" placeholder="LinkedIn" class="field">
                    @error('links.' . $i . '.label') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <input type="url" wire:model="links.{{ $i }}.url" placeholder="https://..." class="field">
                    @error('links.' . $i . '.url') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
                <button type="button" wire:click="removeLink({{ $i }})" class="text-red-400 hover:underline text-sm py-2">Retirer</button>
            </div>
        @endforeach

        <button type="button" wire:click="addLink" class="btn-soft">+ Ajouter un lien</button>
    </div>

    <div class="panel p-6 space-y-4">
        <h3 class="font-semibold text-lg text-gradient">Paramètres du portfolio</h3>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="{{ $label }}">Filière</label>
                <select wire:model="profile_type" class="field">
                    @foreach($profiles as $key => $cfg)
                        <option value="{{ $key }}">{{ $cfg['label']['fr'] }}</option>
                    @endforeach
                </select>
                @error('profile_type') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="{{ $label }}">Couleur principale</label>
                <input type="color" wire:model="primary_color" class="h-10 w-full rounded bg-transparent">
                @error('primary_color') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
            <div class="md:col-span-2">
                <label class="{{ $label }}">Slug (adresse de votre portfolio)</label>
                <input type="text" wire:model="slug" class="field">
                <p class="text-xs text-slate-400 mt-1">Changer le slug change l'adresse publique : les anciens liens partagés ne marcheront plus.</p>
                @error('slug') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
        <label class="inline-flex items-center gap-2 text-slate-300">
            <input type="checkbox" wire:model="is_public" class="rounded border-white/20 bg-white/5 text-cyan-400 focus:ring-cyan-400">
            <span>Portfolio public</span>
        </label>
    </div>

    <div class="flex justify-end">
        <button type="button" wire:click="save" class="btn-primary">Enregistrer le profil</button>
    </div>
</div>