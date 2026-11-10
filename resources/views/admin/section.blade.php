<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gradient">{{ config("portfolio_sections.{$section}.title") }}</h2>
    </x-slot>

    <livewire:admin.section-manager :section="$section" :key="$section" />
</x-admin-layout>