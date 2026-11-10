@php
    $profile = $portfolio->profileConfig();
    $plabel = $profile['projects_label']['fr'] ?? 'Projets';
    $showStack = in_array('stack', $profile['meta_fields'], true);
    $typeLabels = [
        'project' => 'Projet', 'open_source' => 'Open source', 'mission' => 'Mission',
        'case_study' => 'Étude de cas', 'internship_mission' => 'Mission de stage',
        'thesis' => 'Mémoire', 'analysis' => 'Analyse', 'publication' => 'Publication',
        'moot_court' => 'Concours de plaidoirie', 'campaign' => 'Campagne',
    ];
@endphp

<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center gap-4">
            <h2 class="font-bold text-2xl text-gradient">Mes {{ mb_strtolower($plabel) }}</h2>
            <a href="{{ route('admin.projects.create') }}" class="btn-primary !py-2">+ Nouveau</a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4 py-4">
        @if(session('success'))
            <div class="mb-4 p-4 rounded-lg border border-cyan-400/30 bg-cyan-400/10 text-cyan-200">{{ session('success') }}</div>
        @endif

        <div class="panel overflow-x-auto">
            @if($projects->isEmpty())
                <div class="p-8 text-center text-slate-400">
                    Rien pour le moment.
                    <a href="{{ route('admin.projects.create') }}" class="text-cyan-300 underline">Créez votre premier élément</a>
                </div>
            @else
                <table class="w-full text-left">
                    <thead class="border-b border-white/10 text-xs uppercase tracking-widest text-slate-400">
                        <tr>
                            <th class="px-4 py-3">#</th>
                            <th class="px-4 py-3">Titre (FR)</th>
                            <th class="px-4 py-3">Type</th>
                            <th class="px-4 py-3">{{ $showStack ? 'Stack' : 'Détails' }}</th>
                            <th class="px-4 py-3">Confidentiel</th>
                            <th class="px-4 py-3">CV</th>
                            <th class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($projects as $project)
                            <tr class="border-b border-white/5 hover:bg-white/5 transition">
                                <td class="px-4 py-3 text-slate-500">{{ $project->order }}</td>
                                <td class="px-4 py-3 font-medium">{{ $project->title_fr }}</td>
                                <td class="px-4 py-3 text-sm text-slate-300">{{ $typeLabels[$project->type] ?? $project->type }}</td>
                                <td class="px-4 py-3">
                                    @if($showStack)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach(($project->stack ?? []) as $tech)
                                                <span class="text-xs px-2 py-0.5 rounded-full bg-white/5 border border-white/10 text-cyan-200">{{ $tech }}</span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-sm text-slate-300">{{ collect($project->meta ?? [])->filter()->implode(' · ') }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($project->confidential)
                                        <span class="text-xs px-2 py-0.5 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/30">🔒 Oui</span>
                                    @else
                                        <span class="text-xs text-slate-500">Non</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">{!! $project->include_in_cv ? '<span class="text-cyan-300">&#10003;</span>' : '<span class="text-slate-500">&mdash;</span>' !!}</td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.projects.edit', $project) }}" class="text-cyan-300 hover:underline text-sm">Éditer</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-admin-layout>