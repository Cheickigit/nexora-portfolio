<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gradient">Tableau de bord — Portfolio</h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 px-4 space-y-6 py-4">
        @if(! $portfolio)
            <div class="panel p-6 border-amber-500/40 text-amber-300">
                Aucun portfolio n'est associé à votre compte. Contactez l'administrateur.
            </div>
        @else
            @php
                $projectsLabel = $portfolio->profileConfig()['projects_label']['fr'] ?? 'Projets';
            @endphp

            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach([
                    [$projectsLabel, $portfolio->projects_count],
                    ['Compétences', $portfolio->skills_count],
                    ['Expériences', $portfolio->experiences_count],
                    ['Formations', $portfolio->educations_count],
                    ['Langues', $portfolio->languages_count],
                    ['Certifications', $portfolio->certifications_count],
                ] as [$label, $n])
                    <div class="glass p-6">
                        <div class="text-sm text-slate-400">{{ $label }}</div>
                        <div class="text-4xl font-bold text-gradient mt-1"><span data-count="{{ $n }}">0</span></div>
                    </div>
                @endforeach
            </div>

            @php
                $steps = [
                    ['Compléter mon profil (nom, résumé, contact)', filled($portfolio->full_name) && filled($portfolio->summary_fr), route('admin.profile')],
                    ['Ajouter une formation', $portfolio->educations_count > 0, route('admin.sections', 'educations')],
                    ['Ajouter une expérience', $portfolio->experiences_count > 0, route('admin.sections', 'experiences')],
                    ['Ajouter mes compétences', $portfolio->skills_count > 0, route('admin.sections', 'skills')],
                    ['Ajouter : ' . $projectsLabel, $portfolio->projects_count > 0, route('admin.projects.index')],
                    ['Publier mon portfolio', (bool) $portfolio->is_public, route('admin.profile')],
                ];
                $done = collect($steps)->filter(fn ($s) => $s[1])->count();
            @endphp

            @if($done < count($steps))
                <div class="panel p-6 space-y-3">
                    <h3 class="font-semibold text-lg">Guide de démarrage — {{ $done }}/{{ count($steps) }}</h3>
                    <div class="h-2 rounded-full bg-white/10 overflow-hidden">
                        <div class="h-2 bg-cyan-400" style="width: {{ round($done / count($steps) * 100) }}%"></div>
                    </div>
                    <ul class="space-y-2 pt-2">
                        @foreach($steps as [$text, $ok, $url])
                            <li class="flex items-center gap-3">
                                @if($ok)
                                    <span class="text-cyan-300">&#10003;</span>
                                    <span class="text-slate-500 line-through">{{ $text }}</span>
                                @else
                                    <span class="text-slate-500">&#9675;</span>
                                    <a href="{{ $url }}" class="text-cyan-300 hover:underline">{{ $text }}</a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            
            <div class="panel p-6 space-y-4">
                <h3 class="font-semibold text-lg">Actions rapides</h3>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.profile') }}" class="btn-primary !py-2">Éditer mon profil</a>
                    <a href="{{ route('admin.projects.index') }}" class="btn-soft">Gérer : {{ $projectsLabel }}</a>
                    <a href="{{ route('admin.sections', 'experiences') }}" class="btn-soft">Gérer mes expériences</a>
                    <a href="{{ route('admin.sections', 'educations') }}" class="btn-soft">Gérer mes formations</a>
                    <a href="{{ route('admin.sections', 'skills') }}" class="btn-soft">Gérer mes compétences</a>
                </div>
            </div>

            <div class="panel p-6 space-y-3">
                <h3 class="font-semibold text-lg">Mon CV</h3>
                <p class="text-sm text-slate-400">Généré à partir de votre profil. Seuls les éléments cochés « Inclure dans le CV » apparaissent.</p>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('admin.cv', 'fr') }}" target="_blank" class="btn-primary !py-2">Aperçu FR</a>
                    <a href="{{ route('admin.cv', 'en') }}" target="_blank" class="btn-primary !py-2">Aperçu EN</a>
                    <a href="{{ route('admin.cv', ['locale' => 'fr', 'download' => 1]) }}" class="btn-soft">Télécharger FR</a>
                    <a href="{{ route('admin.cv', ['locale' => 'en', 'download' => 1]) }}" class="btn-soft">Télécharger EN</a>
                </div>
            </div>

            
            <div class="panel p-6">
                <a href="{{ route('home', ['locale' => 'fr']) }}" target="_blank" class="text-cyan-300 hover:underline">→ Voir mon portfolio public</a>
            </div>
        @endif
    </div>
</x-admin-layout>