<?php

namespace Tests\Feature;

use App\Livewire\Admin\SectionManager;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class SectionManagerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Portfolio $portfolio;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->portfolio = Portfolio::create([
            'user_id' => $this->user->id,
            'slug' => 'test-portfolio',
        ]);
    }

    public function test_un_utilisateur_peut_ajouter_une_experience()
    {
        $this->actingAs($this->user);

        Livewire::test(SectionManager::class, ['section' => 'experiences'])
            ->call('create')
            ->set('form.role_fr', 'Développeur')
            ->set('form.role_en', 'Developer')
            ->set('form.company', 'Nexora')
            ->set('form.period_fr', '2023 - 2024')
            ->set('form.order', 1)
            ->set('form.include_in_cv', true)
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('experiences', [
            'portfolio_id' => $this->portfolio->id,
            'role_fr' => 'Développeur',
            'company' => 'Nexora',
            'order' => 1,
        ]);
    }

    public function test_une_competence_est_bien_convertie_en_tableau_json()
    {
        $this->actingAs($this->user);

        Livewire::test(SectionManager::class, ['section' => 'skills'])
            ->call('create')
            ->set('form.category', 'backend')
            ->set('form.label_fr', 'Back-end')
            ->set('form.label_en', 'Back-end')
            ->set('form.items', 'PHP, Laravel, MySQL') // Le champ texte avec des virgules
            ->set('form.order', 1)
            ->call('save')
            ->assertHasNoErrors();

        $skill = $this->portfolio->skills()->first();
        
        $this->assertNotNull($skill);
        // On vérifie que la chaîne de caractères a bien été transformée en tableau
        $this->assertEquals(['PHP', 'Laravel', 'MySQL'], $skill->items);
    }

    public function test_la_methode_delete_supprime_bien_element()
    {
        $this->actingAs($this->user);

        // On crée d'abord une expérience
        $experience = $this->portfolio->experiences()->create([
            'role_fr' => 'A supprimer',
            'role_en' => 'To delete',
            'company' => 'Test',
            'order' => 1,
            'include_in_cv' => true,
        ]);

        // On la supprime via le composant
        Livewire::test(SectionManager::class, ['section' => 'experiences'])
            ->call('delete', $experience->id);

        // On vérifie qu'elle n'existe plus en base
        $this->assertDatabaseMissing('experiences', [
            'id' => $experience->id,
        ]);
    }

    public function test_on_ne_peut_pas_acceder_a_une_section_inexistante()
    {
        $this->actingAs($this->user);

        Livewire::test(SectionManager::class, ['section' => 'section-qui-nexiste-pas'])
            ->assertStatus(404);
    }
}