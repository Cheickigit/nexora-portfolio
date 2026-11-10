<x-admin-layout>
    <x-slot name="header"><h2 class="font-bold text-2xl text-gradient">Éditer : {{ $project->title_fr }}</h2></x-slot>
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-4"><livewire:admin.project-form :project="$project" /></div>
</x-admin-layout>
