<?php

namespace Tests\Feature;

use App\Livewire\Admin\ProjectForm;
use App\Models\Portfolio;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProjectFormTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Portfolio $portfolio;

    protected function setUp(): void
    {
        parent::setUp();

        // On crée un admin et son portfolio
        $this->user = User::factory()->create();
        $this->portfolio = Portfolio::create([
            'user_id' => $this->user->id,
            'slug' => 'test-portfolio',
            'profile_type' => 'tech', // On utilise la filière "tech" pour avoir accès au champ "stack"
        ]);
    }

    public function test_un_utilisateur_peut_creer_un_projet()
    {
        $this->actingAs($this->user);

        Livewire::test(ProjectForm::class)
            ->set('title_fr', 'Mon Super Projet')
            ->set('title_en', 'My Super Project')
            ->set('slug', 'mon-super-projet')
            ->set('type', 'project')
            ->set('stack_input', 'Laravel, Livewire, MySQL')
            ->set('context_fr', 'Ceci est le contexte.')
            ->set('context_en', 'This is the context.')
            ->call('save')
            ->assertHasNoErrors();

        // On vérifie que le projet a bien été créé en base de données
        $this->assertDatabaseHas('projects', [
            'portfolio_id' => $this->portfolio->id,
            'title_fr' => 'Mon Super Projet',
            'slug' => 'mon-super-projet',
        ]);

        // On vérifie que le JSON de la stack a bien été transformé en tableau
        $project = Project::where('slug', 'mon-super-projet')->first();
        $this->assertEquals(['Laravel', 'Livewire', 'MySQL'], $project->stack);
    }

    public function test_un_utilisateur_peut_modifier_un_projet_existant()
    {
        $this->actingAs($this->user);

        // On crée un projet de base
        $project = Project::create([
            'portfolio_id' => $this->portfolio->id,
            'slug' => 'projet-existant',
            'type' => 'project',
            'title_fr' => 'Ancien titre',
            'title_en' => 'Old title',
        ]);

        Livewire::test(ProjectForm::class, ['project' => $project])
            ->set('title_fr', 'Nouveau titre')
            ->call('save')
            ->assertHasNoErrors();

        // On vérifie que le titre a bien été mis à jour en base
        $this->assertEquals('Nouveau titre', $project->fresh()->title_fr);
    }

    public function test_le_slug_doit_etre_unique_pour_ce_portfolio()
    {
        $this->actingAs($this->user);

        // On crée un premier projet avec un slug
        Project::create([
            'portfolio_id' => $this->portfolio->id,
            'slug' => 'slug-deja-pris',
            'type' => 'project',
            'title_fr' => 'Projet 1',
            'title_en' => 'Project 1',
        ]);

        // On essaie de créer un deuxième projet avec le même slug
        Livewire::test(ProjectForm::class)
            ->set('title_fr', 'Projet 2')
            ->set('title_en', 'Project 2')
            ->set('slug', 'slug-deja-pris')
            ->set('type', 'project')
            ->call('save')
            ->assertHasErrors(['slug' => 'unique']);
    }

    public function test_un_utilisateur_ne_peut_pas_modifier_le_projet_dun_autre()
    {
        // Un autre utilisateur avec son propre portfolio et projet
        $autreUser = User::factory()->create();
        $autrePortfolio = Portfolio::create([
            'user_id' => $autreUser->id,
            'slug' => 'autre-portfolio',
            'profile_type' => 'tech',
        ]);
        $projetDeLAutre = Project::create([
            'portfolio_id' => $autrePortfolio->id,
            'slug' => 'projet-prive',
            'type' => 'project',
            'title_fr' => 'Projet Privé',
            'title_en' => 'Private Project',
        ]);

        // On se connecte avec le premier utilisateur
        $this->actingAs($this->user);

        // On essaie d'accéder au formulaire du projet de l'autre
        Livewire::test(ProjectForm::class, ['project' => $projetDeLAutre])
            ->assertForbidden(); // Le système doit bloquer (Policy)
    }
}