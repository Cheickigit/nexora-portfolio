<?php

namespace App\Livewire\Admin;

use App\Models\Portfolio;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfileForm extends Component
{
    use WithFileUploads;

    public string $slug = '';
    public string $title = '';
    public string $full_name = '';
    public string $email = '';
    public string $phone = '';
    public string $location = '';
    public string $tagline_fr = '';
    public string $tagline_en = '';
    public string $summary_fr = '';
    public string $summary_en = '';
    public string $profile_type = 'general';
    public string $primary_color = '#10b981';
    public bool $is_public = true;
    public array $links = [];

    public $photo = null;
    public ?string $currentPhoto = null;
    public $logo = null;
    public ?string $currentLogo = null;
    public ?string $flash = null;

    #[Computed]
    public function portfolio(): Portfolio
    {
        return Portfolio::where('user_id', auth()->id())->firstOrFail();
    }

    public function mount(): void
    {
        if (! Portfolio::where('user_id', auth()->id())->exists()) {
            $this->redirectRoute('admin.dashboard');

            return;
        }

        $p = $this->portfolio;

        $this->slug          = $p->slug;
        $this->title         = $p->title ?? '';
        $this->full_name     = $p->full_name ?? '';
        $this->email         = $p->email ?? '';
        $this->phone         = $p->phone ?? '';
        $this->location      = $p->location ?? '';
        $this->tagline_fr    = $p->tagline_fr ?? '';
        $this->tagline_en    = $p->tagline_en ?? '';
        $this->summary_fr    = $p->summary_fr ?? '';
        $this->summary_en    = $p->summary_en ?? '';
        $this->profile_type  = $p->profile_type ?: 'general';
        $this->primary_color = $p->primary_color ?: '#10b981';
        $this->is_public     = (bool) $p->is_public;
        $this->links         = $p->links ?? [];
        $this->currentPhoto  = $p->photo;
        $this->currentLogo   = $p->logo;
    }

    public function addLink(): void
    {
        if (count($this->links) < 8) {
            $this->links[] = ['label' => '', 'url' => ''];
        }
    }

    public function removeLink(int $index): void
    {
        unset($this->links[$index]);
        $this->links = array_values($this->links);
    }

    protected function rules(): array
    {
        return [
            'slug' => [
                'required', 'string', 'min:2', 'max:60',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('portfolios', 'slug')->ignore($this->portfolio->id),
                Rule::notIn(config('portfolio.reserved_slugs', [])),
            ],
            'title'         => 'nullable|string|max:150',
            'full_name'     => 'nullable|string|max:150',
            'email'         => 'nullable|email|max:255',
            'phone'         => 'nullable|string|max:50',
            'location'      => 'nullable|string|max:150',
            'tagline_fr'    => 'nullable|string|max:255',
            'tagline_en'    => 'nullable|string|max:255',
            'summary_fr'    => 'nullable|string|max:1500',
            'summary_en'    => 'nullable|string|max:1500',
            'profile_type'  => ['required', Rule::in(array_keys(config('portfolio_profiles')))],
            'primary_color' => ['required', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'is_public'     => 'boolean',
            'links'         => 'array|max:8',
            'links.*.label' => 'nullable|string|max:50|required_with:links.*.url',
            'links.*.url'   => 'nullable|url|max:255',
            'photo'         => 'nullable|image|max:2048',
            'logo'          => 'nullable|image|max:2048',
        ];
    }

    protected function messages(): array
    {
        return [
            'slug.regex'    => 'Le slug ne peut contenir que des minuscules, chiffres et tirets.',
            'slug.unique'   => 'Ce slug est déjà utilisé.',
            'slug.not_in'   => 'Ce slug est réservé.',
            'links.*.label.required_with' => 'Donnez un nom à ce lien.',
        ];
    }

    /**
     * Nettoie un résumé collé depuis un PDF : supprime les retours à la ligne
     * au milieu des phrases, garde les vrais paragraphes (lignes vides).
     */
    protected function cleanSummary(?string $text): ?string
    {
        $text = trim((string) $text);

        if ($text === '') {
            return null;
        }

        $text = str_replace(["\r\n", "\r"], "\n", $text);

        // Saut de ligne simple => espace ; ligne vide (paragraphe) conservée
        $text = preg_replace('/(?<!\n)\n(?!\n)/', ' ', $text);

        // Espaces multiples => un seul
        $text = preg_replace('/[ \t]{2,}/', ' ', $text);

        return $text;
    }

    public function save(): void
    {
        $this->validate();

        $p = $this->portfolio;

        $this->summary_fr = (string) $this->cleanSummary($this->summary_fr);
        $this->summary_en = (string) $this->cleanSummary($this->summary_en);

        $data = [
            'slug'          => $this->slug,
            'title'         => $this->title ?: null,
            'full_name'     => $this->full_name ?: null,
            'email'         => $this->email ?: null,
            'phone'         => $this->phone ?: null,
            'location'      => $this->location ?: null,
            'tagline_fr'    => $this->tagline_fr ?: null,
            'tagline_en'    => $this->tagline_en ?: null,
            'summary_fr'    => $this->summary_fr ?: null,
            'summary_en'    => $this->summary_en ?: null,
            'profile_type'  => $this->profile_type,
            'primary_color' => $this->primary_color,
            'is_public'     => $this->is_public,
            'links'         => collect($this->links)
                ->filter(fn ($l) => filled($l['url'] ?? null))
                ->map(fn ($l) => [
                    'label' => trim((string) ($l['label'] ?? '')),
                    'url'   => trim((string) $l['url']),
                ])
                ->values()
                ->all() ?: null,
        ];

        if ($this->photo) {
            $path = $this->photo->store('photos', 'public');

            if ($p->photo) {
                Storage::disk('public')->delete($p->photo);
            }

            $data['photo'] = $path;
            $this->currentPhoto = $path;
            $this->photo = null;
        }

        if ($this->logo) {
            $logoPath = $this->logo->store('logos', 'public');

            if ($p->logo) {
                Storage::disk('public')->delete($p->logo);
            }

            $data['logo'] = $logoPath;
            $this->currentLogo = $logoPath;
            $this->logo = null;
        }

        $p->update($data);

        $this->flash = 'Profil enregistré.';
    }

    public function render()
    {
        return view('livewire.admin.profile-form');
    }
}