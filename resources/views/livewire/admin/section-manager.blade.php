@php
    $def = $this->def;
    $label = 'block text-sm mb-1 text-slate-300';
@endphp

<div class="max-w-5xl mx-auto px-4 space-y-6">

    @if($flash)
        <div class="p-4 rounded-lg border border-cyan-400/30 bg-cyan-400/10 text-cyan-200">{{ $flash }}</div>
    @endif

    <div class="flex justify-between items-center gap-4">
        <h2 class="font-bold text-2xl text-gradient">{{ $def['title'] }}</h2>
        <button type="button" wire:click="create" class="btn-primary !py-2">+ Ajouter</button>
    </div>

    @if($showForm)
        <div class="panel p-6 space-y-4">
            <h3 class="font-semibold text-lg text-gradient">{{ $editingId ? 'Modifier' : 'Ajouter' }}</h3>

            <div class="grid md:grid-cols-2 gap-4">
                @foreach($def['fields'] as $f)
                    <div class="{{ ! empty($f['full']) ? 'md:col-span-2' : '' }}">
                        <label class="{{ $label }}">{{ $f['label'] }}</label>
                        @if($f['type'] === 'textarea')
                            <textarea wire:model="form.{{ $f['name'] }}" rows="3" class="field"></textarea>
                        @else
                            <input type="text" wire:model="form.{{ $f['name'] }}" placeholder="{{ $f['placeholder'] ?? '' }}" class="field">
                        @endif
                        @error('form.' . $f['name']) <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                @endforeach

                <div>
                    <label class="{{ $label }}">Ordre d'affichage</label>
                    <input type="number" wire:model="form.order" min="0" class="field">
                    @error('form.order') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            @if(! empty($def['cv_flag']))
                <label class="inline-flex items-center gap-2 text-slate-300">
                    <input type="checkbox" wire:model="form.include_in_cv" class="rounded border-white/20 bg-white/5 text-cyan-400 focus:ring-cyan-400">
                    <span>Inclure dans le CV</span>
                </label>
            @endif

            <div class="flex gap-3">
                <button type="button" wire:click="save" class="btn-primary !py-2">Enregistrer</button>
                <button type="button" wire:click="cancel" class="btn-soft">Annuler</button>
            </div>
        </div>
    @endif

    <div class="panel overflow-x-auto">
        @if($items->isEmpty())
            <div class="p-8 text-center text-slate-400">Aucun élément pour le moment.</div>
        @else
            <table class="w-full text-left">
                <thead class="border-b border-white/10 text-xs uppercase tracking-widest text-slate-400">
                    <tr>
                        <th class="px-4 py-3">#</th>
                        @foreach($def['list'] as $text)
                            <th class="px-4 py-3">{{ $text }}</th>
                        @endforeach
                        @if(! empty($def['cv_flag']))
                            <th class="px-4 py-3">CV</th>
                        @endif
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $item)
                        <tr wire:key="item-{{ $item->id }}" class="border-b border-white/5 hover:bg-white/5 transition">
                            <td class="px-4 py-3 text-slate-500">{{ $item->order }}</td>
                            @foreach($def['list'] as $col => $text)
                                <td class="px-4 py-3">{{ $item->{$col} }}</td>
                            @endforeach
                            @if(! empty($def['cv_flag']))
                                <td class="px-4 py-3">{!! $item->include_in_cv ? '<span class="text-cyan-300">&#10003;</span>' : '<span class="text-slate-500">&mdash;</span>' !!}</td>
                            @endif
                            <td class="px-4 py-3 text-right space-x-3">
                                <button type="button" wire:click="edit({{ $item->id }})" class="text-cyan-300 hover:underline text-sm">Éditer</button>
                                <button type="button" wire:click="delete({{ $item->id }})" wire:confirm="Supprimer cet élément ?" class="text-red-400 hover:underline text-sm">Supprimer</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>