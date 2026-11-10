<?php

namespace App\Livewire\Admin;

use App\Models\Portfolio;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProjectForm extends Component
{
    use WithFileUploads;

    public ?Project $project = null;

    public string $slug = '';
    public string $type = '';
    public string $title_fr = '';
    public string $title_en = '';
    public string $context_fr = '';
    public string $context_en = '';
    public string $role_fr = '';
    public string $role_en = '';
    public string $stack_input = '';
    public string $challenges_fr = '';
    public string $challenges_en = '';
    public string $result_fr = '';
    public string $result_en = '';
    public bool $confidential = false;
    public bool $include_in_cv = true;
    public string $demo_url = '';
    public string $repo_url = '';
    public int $order = 0;
    public array $meta = [];

    // Gestion de l'image
    public $new_image = null;
    public ?string $currentImage = null;

    private const COLUMN_FIELDS = ['stack', 'repo_url', 'demo_url'];

    #[Computed]
    public function portfolio(): Portfolio
    {
        return Portfolio::where('user_id', auth()->id())->firstOrFail();
    }

    #[Computed]
    public function profile(): array
    {
        return $this->portfolio->profileConfig();
    }

    #[Computed]
    public function projectTypes(): array
    {
        $types = $this->profile['project_types'];
        if ($this->project?->type && ! in_array($this->project->type, $types, true)) {
            $types[] = $this->project->type;
        }
        return $types;
    }

    #[Computed]
    public function metaFields(): array
    {
        return array_values(array_diff($this->profile['meta_fields'], self::COLUMN_FIELDS));
    }

    #[Computed]
    public function showStack(): bool
    {
        return in_array('stack', $this->profile['meta_fields'], true);
    }

    #[Computed]
    public function showUrls(): bool
    {
        return in_array('repo_url', $this->profile['meta_fields'], true)
            || in_array('demo_url', $this->profile['meta_fields'], true);
    }

    public function mount(?Project $project = null): void
    {
        if (! Portfolio::where('user_id', auth()->id())->exists()) {
            $this->redirectRoute('admin.dashboard');
            return;
        }

        $this->type = $this->projectTypes[0] ?? 'project';

        if ($project && $project->exists) {
            Gate::authorize('update', $project);

            $this->project = $project;
            $this->type          = $project->type ?: $this->type;
            $this->slug          = $project->slug;
            $this->title_fr      = $project->title_fr;
            $this->title_en      = $project->title_en;
            $this->context_fr    = $project->context_fr ?? '';
            $this->context_en    = $project->context_en ?? '';
            $this->role_fr       = $project->role_fr ?? '';
            $this->role_en       = $project->role_en ?? '';
            $this->stack_input   = implode(', ', $project->stack ?? []);
            $this->challenges_fr = $project->challenges_fr ?? '';
            $this->challenges_en = $project->challenges_en ?? '';
            $this->result_fr     = $project->result_fr ?? '';
            $this->result_en     = $project->result_en ?? '';
            $this->confidential  = (bool) $project->confidential;
            $this->include_in_cv = (bool) $project->include_in_cv;
            $this->demo_url      = $project->demo_url ?? '';
            $this->repo_url      = $project->repo_url ?? '';
            $this->order         = (int) ($project->order ?? 0);
            $this->meta          = $project->meta ?? [];
            $this->currentImage  = $project->image;
        } else {
            foreach ($this->metaFields as $key) {
                $this->meta[$key] = '';
            }
        }
    }

    protected function rules(): array
    {
        return [
            'type'          => ['required', Rule::in($this->projectTypes)],
            'slug'          => [
                'required', 'string', 'min:2', 'max:100', 'regex:/^[a-z0-9-]+$/',
                Rule::unique('projects', 'slug')
                    ->where('portfolio_id', $this->portfolio->id)
                    ->ignore($this->project?->id),
            ],
            'title_fr'      => 'required|string|min:2|max:150',
            'title_en'      => 'required|string|min:2|max:150',
            'context_fr'    => 'nullable|string|max:1000',
            'context_en'    => 'nullable|string|max:1000',
            'role_fr'       => 'nullable|string|max:150',
            'role_en'       => 'nullable|string|max:150',
            'stack_input'   => 'nullable|string|max:500',
            'challenges_fr' => 'nullable|string|max:2000',
            'challenges_en' => 'nullable|string|max:2000',
            'result_fr'     => 'nullable|string|max:2000',
            'result_en'     => 'nullable|string|max:2000',
            'confidential'  => 'boolean',
            'include_in_cv' => 'boolean',
            'demo_url'      => 'nullable|url|max:255',
            'repo_url'      => 'nullable|url|max:255',
            'order'         => 'integer|min:0',
            'meta'          => 'array',
            'meta.*'        => 'nullable|string|max:500',
            'new_image'     => 'nullable|image|max:2048', // Max 2Mo
        ];
    }

    protected function messages(): array
    {
        return [
            'slug.regex'  => 'Le slug ne peut contenir que des minuscules, chiffres et tirets.',
            'slug.unique' => 'Vous avez déjà une réalisation avec ce slug.',
            'new_image.image' => 'Le fichier doit être une image.',
            'new_image.max'   => 'L\'image ne doit pas dépasser 2 Mo.',
        ];
    }

    public function save(): void
    {
        $validated = $this->validate();

        if ($this->showStack) {
            $validated['stack'] = collect(explode(',', $this->stack_input))
                ->map(fn ($s) => trim($s))->filter()->values()->all();
        }
        unset($validated['stack_input']);

        if ($this->showUrls) {
            $validated['demo_url'] = $validated['demo_url'] ?: null;
            $validated['repo_url'] = $validated['repo_url'] ?: null;
        } else {
            unset($validated['demo_url'], $validated['repo_url']);
        }

        $kept = collect($this->project?->meta ?? [])->except($this->metaFields)->all();
        $new = collect($this->meta)->only($this->metaFields)->map(fn ($v) => trim((string) $v))->filter()->all();
        $validated['meta'] = array_merge($kept, $new) ?: null;

        // Gestion de l'image
        if ($this->new_image) {
            $path = $this->new_image->store('projects', 'public');
            
            // On supprime l'ancienne image si elle existe
            if ($this->project && $this->project->image) {
                Storage::disk('public')->delete($this->project->image);
            }
            
            $validated['image'] = $path;
            $this->currentImage = $path;
            $this->new_image = null;
        } else {
            // On ne touche pas à l'image existante si aucune nouvelle n'est uploadée
            unset($validated['new_image']);
        }

        if ($this->project && $this->project->exists) {
            Gate::authorize('update', $this->project);
            $this->project->update($validated);
            session()->flash('success', 'Réalisation mise à jour.');
        } else {
            Gate::authorize('create', Project::class);
            $validated['portfolio_id'] = $this->portfolio->id;
            Project::create($validated);
            session()->flash('success', 'Réalisation créée.');
        }

        $this->redirectRoute('admin.projects.index');
    }

    public function delete(): void
    {
        if ($this->project && $this->project->exists) {
            Gate::authorize('delete', $this->project);
            
            if ($this->project->image) {
                Storage::disk('public')->delete($this->project->image);
            }
            
            $this->project->delete();
            session()->flash('success', 'Réalisation supprimée.');
        }

        $this->redirectRoute('admin.projects.index');
    }

    public function render()
    {
        return view('livewire.admin.project-form');
    }
}