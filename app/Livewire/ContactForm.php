<?php

namespace App\Livewire;

use App\Mail\ContactMessage;
use App\Models\Portfolio;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Locked;
use Livewire\Component;

class ContactForm extends Component
{
    #[Locked]
    public int $portfolioId = 0;

    #[Locked]
    public string $lang = 'fr';

    public string $name = '';
    public string $email = '';
    public string $message = '';
    public string $website = ''; // piège anti-robots : doit rester vide

    public bool $sent = false;
    public bool $failed = false;
    public ?string $limited = null;

    public function mount(int $portfolioId, string $lang = 'fr'): void
    {
        $this->portfolioId = $portfolioId;
        $this->lang = in_array($lang, ['fr', 'en'], true) ? $lang : 'fr';
        app()->setLocale($this->lang);
    }

    // Les requêtes Livewire n'ont pas /{locale} dans l'URL : on restaure la langue de la page
    public function hydrate(): void
    {
        app()->setLocale($this->lang);
    }

    protected function rules(): array
    {
        return [
            'name'    => 'required|string|min:2|max:100',
            'email'   => 'required|email|max:150',
            'message' => 'required|string|min:10|max:2000',
        ];
    }

    public function send(): void
    {
        $this->failed = false;
        $this->limited = null;

        if ($this->website !== '') {
            // Un robot a rempli le champ caché : on fait comme si c'était parti
            $this->reset('name', 'email', 'message', 'website');
            $this->sent = true;

            return;
        }

        $key = 'contact:'.$this->portfolioId.':'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 3)) {
            $this->limited = $this->lang === 'fr'
                ? 'Trop de messages envoyés. Réessayez dans quelques minutes.'
                : 'Too many messages sent. Please try again in a few minutes.';

            return;
        }

        $validated = $this->validate();

        $portfolio = Portfolio::with('user')->findOrFail($this->portfolioId);
        $to = $portfolio->email ?: $portfolio->user->email;

        if (! filter_var($to, FILTER_VALIDATE_EMAIL)) {
            Log::error('Contact form : pas d\'email destinataire', ['portfolio_id' => $portfolio->id]);
            $this->failed = true;

            return;
        }

        RateLimiter::hit($key, 600);

        try {
            Mail::to($to)->send(new ContactMessage(
                preg_replace('/[\r\n]+/', ' ', $validated['name']),
                $validated['email'],
                $validated['message'],
                $portfolio->displayName(),
            ));
        } catch (\Throwable $e) {
            Log::error('Contact form : envoi impossible', [
                'portfolio_id' => $portfolio->id,
                'error' => $e->getMessage(),
            ]);
            $this->failed = true;

            return;
        }

        $this->reset('name', 'email', 'message');
        $this->sent = true;
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}