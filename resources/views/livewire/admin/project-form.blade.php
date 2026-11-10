@php
    $label = 'block text-sm mb-1 text-slate-300';
    $sections = [
        'Contexte' => [['context', 'Contexte', 'textarea', 3], ['role', 'Rôle', 'input']],
        'Technique' => [['challenges', 'Défis & solutions', 'textarea', 3], ['result', 'Résultat', 'textarea', 2]],
    ];
@endphp

<div class="max-w-4xl mx-auto px-4 space-y-6">

    <div class="panel p-6 space-y-4">
        <h3 class="font-semibold text-lg text-gradient">Identité</h3>
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="{{ $label }}">Slug (URL) *</label>
                <input type="text" wire:model="slug" placeholder="mon-super-projet" class="field">
                @error('slug') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="{{ $label }}">Ordre d'affichage</label>
                <input type="number" wire:model="order" min="0" class="field">
            </div>
        </div>
        <div class="grid md:grid-cols-2 gap-4">
            @foreach(['fr', 'en'] as $l)
                <div>
                    <label class="{{ $label }}">Titre ({{ strtoupper($l) }}) *</label>
                    <input type="text" wire:model="title_{{ $l }}" class="field">
                    @error('title_' . $l) <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
            @endforeach
        </div>
    </div>

        <div class="panel p-6 space-y-4">
        @php
            $typeLabels = [
                'project' => 'Projet', 'open_source' => 'Open source', 'mission' => 'Mission',
                'case_study' => '&Eacute;tude de cas', 'internship_mission' => 'Mission de stage',
                'thesis' => 'M&eacute;moire', 'analysis' => 'Analyse', 'publication' => 'Publication',
                'moot_court' => 'Concours de plaidoirie', 'campaign' => 'Campagne',
            ];
        @endphp
        <h3 class="font-semibold text-lg text-gradient">Type de r&eacute;alisation</h3>
        <select wire:model="type" class="field">
            @foreach($this->projectTypes as $t)
                <option value="{{ $t }}">{!! $typeLabels[$t] ?? e($t) !!}</option>
            @endforeach
        </select>
        @error('type') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
    </div>

@foreach($sections as $title => $fields)
        <div class="panel p-6 space-y-4">
            <h3 class="font-semibold text-lg text-gradient">{{ $title }}</h3>

            @if($title === 'Technique' && $this->showStack)
                <div>
                    <label class="{{ $label }}">Stack (séparée par virgules)</label>
                    <input type="text" wire:model="stack_input" placeholder="Laravel, Livewire, MySQL" class="field">
                </div>
            @endif

            @foreach($fields as $f)
                <div class="grid md:grid-cols-2 gap-4">
                    @foreach(['fr', 'en'] as $l)
                        <div>
                            <label class="{{ $label }}">{{ $f[1] }} ({{ strtoupper($l) }})</label>
                            @if($f[2] === 'textarea')
                                <textarea wire:model="{{ $f[0] }}_{{ $l }}" rows="{{ $f[3] }}" class="field"></textarea>
                            @else
                                <input type="text" wire:model="{{ $f[0] }}_{{ $l }}" class="field">
                            @endif
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endforeach

        @if(count($this->metaFields))
        @php
            $metaLabels = [
                'tools' => 'Outils', 'methods' => 'M&eacute;thodes', 'sector' => 'Secteur',
                'domain' => 'Domaine', 'jurisdiction' => 'Juridiction',
                'channels' => 'Canaux', 'kpis' => 'Indicateurs (KPI)',
            ];
        @endphp
        <div class="panel p-6 space-y-4">
            <h3 class="font-semibold text-lg text-gradient">D&eacute;tails sp&eacute;cifiques</h3>
            <div class="grid md:grid-cols-2 gap-4">
                @foreach($this->metaFields as $key)
                    <div>
                        <label class="{{ $label }}">{!! $metaLabels[$key] ?? e($key) !!}</label>
                        <input type="text" wire:model="meta.{{ $key }}" class="field">
                        @error('meta.' . $key) <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                @endforeach
            </div>
        </div>
    @endif

        {{-- NOUVEAU PANNEAU : IMAGE DU PROJET --}}
    <div class="panel p-6 space-y-4">
        <h3 class="font-semibold text-lg text-gradient">Image de couverture</h3>
        
        <div class="flex items-center gap-6">
            <!-- Aperçu -->
            <div class="shrink-0 w-40 h-24 bg-white/5 rounded border border-white/10 overflow-hidden relative">
                @if($new_image)
                    <img src="{{ $new_image->temporaryUrl() }}" class="w-full h-full object-cover">
                @elseif($currentImage)
                    <img src="{{ asset('storage/' . $currentImage) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-500 text-xs text-center p-2">
                        Aucune image
                    </div>
                @endif
            </div>

            <!-- Bouton d'upload -->
            <div class="flex-1">
                <label class="{{ $label }}">Nouvelle image (Max 2 Mo)</label>
                <input type="file" wire:model="new_image" accept="image/*" class="text-sm text-slate-300">
                <div wire:loading wire:target="new_image" class="text-xs text-slate-400 mt-1">Chargement en cours…</div>
                @error('new_image') <span class="text-red-400 text-xs block mt-1">{{ $message }}</span> @enderror
            </div>
        </div>
    </div>

    <div class="panel p-6 space-y-4">
        <h3 class="font-semibold text-lg text-gradient">Liens & options</h3>
        
        @if($this->showUrls)
<div class="grid md:grid-cols-2 gap-4">
            <div>
                <label class="{{ $label }}">URL démo</label>
                <input type="url" wire:model="demo_url" placeholder="https://..." class="field">
                @error('demo_url') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="{{ $label }}">URL dépôt</label>
                <input type="url" wire:model="repo_url" placeholder="https://..." class="field">
                @error('repo_url') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
            </div>
        </div>
        @endif
        <label class="inline-flex items-center gap-2 text-slate-300">
            <input type="checkbox" wire:model="confidential" class="rounded border-white/20 bg-white/5 text-cyan-400 focus:ring-cyan-400">
            <span>Projet confidentiel (badge 🔒)</span>
        </label>
        <div>
            <label class="inline-flex items-center gap-2 text-slate-300">
                <input type="checkbox" wire:model="include_in_cv" class="rounded border-white/20 bg-white/5 text-cyan-400 focus:ring-cyan-400">
                <span>Inclure dans le CV</span>
            </label>
        </div>
    </div>

    <div class="flex justify-between items-center gap-3">
        <a href="{{ route('admin.projects.index') }}" class="text-slate-400 hover:text-white">← Annuler</a>
        <div class="flex gap-3">
            @if($project && $project->exists)
                <button type="button" wire:click="delete" wire:confirm="Supprimer ce projet définitivement ?" class="btn-danger">Supprimer</button>
            @endif
            <button type="button" wire:click="save" wire:loading.attr="disabled" class="btn-primary !py-2 disabled:opacity-50">
                <span wire:loading.remove>Enregistrer</span><span wire:loading>Enregistrement…</span>
            </button>
        </div>
    </div>
</div>
