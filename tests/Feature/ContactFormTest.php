<?php

namespace Tests\Feature;

use App\Livewire\ContactForm;
use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class ContactFormTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected Portfolio $portfolio;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. On crée un utilisateur de test
        $this->user = User::factory()->create(['email' => 'proprietaire@example.com']);

        // 2. On lui crée un portfolio avec un email de contact
        $this->portfolio = Portfolio::create([
            'user_id' => $this->user->id,
            'slug' => 'test-portfolio',
            'email' => 'contact@example.com',
            'is_public' => true,
        ]);
    }

    public function test_il_envoie_un_message_avec_succes()
    {
        Mail::fake(); // On empêche l'envoi réel de l'email

        Livewire::test(ContactForm::class, ['portfolioId' => $this->portfolio->id])
            ->set('name', 'Jean Dupont')
            ->set('email', 'jean@example.com')
            ->set('message', 'Bonjour, ceci est un message de test valide.')
            ->call('send')
            ->assertSet('sent', true)
            ->assertSet('failed', false);

        // On vérifie que l'email a bien été "envoyé" à la bonne adresse
        Mail::assertSent(\App\Mail\ContactMessage::class, function ($mail) {
            return $mail->hasTo('contact@example.com') &&
                   $mail->senderName === 'Jean Dupont' &&
                   $mail->senderEmail === 'jean@example.com';
        });
    }

    public function test_le_honeypot_bloque_les_robots_spammeurs()
    {
        Mail::fake();

        Livewire::test(ContactForm::class, ['portfolioId' => $this->portfolio->id])
            ->set('name', 'Robot')
            ->set('email', 'robot@spam.com')
            ->set('message', 'Achetez mes produits !')
            ->set('website', 'http://spam.com') // Le robot remplit le champ caché
            ->call('send')
            ->assertSet('sent', true); // Le formulaire fait semblant que c'est un succès

        // Mais en réalité, aucun email n'a été envoyé
        Mail::assertNothingSent();
    }

    public function test_les_champs_sont_valides()
    {
        Mail::fake();

        Livewire::test(ContactForm::class, ['portfolioId' => $this->portfolio->id])
            ->set('name', '') // Nom vide
            ->set('email', 'pas-un-email') // Email invalide
            ->set('message', 'Court') // Message trop court (min:10)
            ->call('send')
            ->assertHasErrors(['name', 'email', 'message']);

        Mail::assertNothingSent();
    }

    public function test_le_rate_limiting_bloque_apres_3_envois()
    {
        Mail::fake();

        $component = Livewire::test(ContactForm::class, ['portfolioId' => $this->portfolio->id]);

        // On simule 3 envois réussis
        for ($i = 0; $i < 3; $i++) {
            $component->set('name', 'Jean')
                ->set('email', 'jean@example.com')
                ->set('message', 'Message numéro ' . $i)
                ->call('send')
                ->assertSet('sent', true);
            
            // On réinitialise le formulaire pour le prochain envoi
            $component->set('sent', false);
        }

        // Le 4ème envoi doit être bloqué par le RateLimiter
        $component->set('name', 'Jean')
            ->set('email', 'jean@example.com')
            ->set('message', 'Message de trop')
            ->call('send')
            ->assertSet('limited', 'Trop de messages envoyés. Réessayez dans quelques minutes.');

        // Seuls les 3 premiers mails ont été envoyés
        Mail::assertSent(\App\Mail\ContactMessage::class, 3);
    }
}