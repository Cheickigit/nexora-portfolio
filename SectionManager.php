<?php

namespace App\Livewire\Admin;

use App\Models\Portfolio;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SectionManager extends Component
{
    public string $section = '';
    public bool $showForm = false;
    public ?int $editingId = null;
    public array $form = [];
    public ?string $flash = null;

    public function mount(string $section): void
    {
        abort_unless(config("portfolio_sections.{$section}"), 404);
        $this->section = $section;
    }

    #[Computed]
    public function def(): array
    {
        return config("portfolio_sections.{$this->section}");
    }

    #[Computed]
    public function portfolio(): Portfolio
    {
        return Portfolio::where('user_id', auth()->id())->firstOrFail();
    }

    /** Toujours passer par le portfolio de l'utilisateur : isole les données de chaque client. */
    private function relation(): HasMany
    {
        return $this->portfolio->{$this->def['relation']}();
    }

    private function hasCvFlag(): bool
    {
        return ! empty($this->def['cv_flag']);
    }

    private function blankForm(): array
    {
        $form = ['order' => 0];

        foreach ($this->def['fields'] as $f) {
            $form[$f['name']] = '';
        }

        if ($this->hasCvFlag()) {
            $form['include_in_cv'] = true;
        }

        return $form;
    }

    public function create(): void
    {
        $this->resetValidation();
        $this->flash = null;
        $this->editingId = null;
        $this->form = $this->blankForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $this->resetValidation();
        $this->flash = null;

        $item = $this->relation()->whereKey($id)->firstOrFail();
        $form = $this->blankForm();

        foreach ($this->def['fields'] as $f) {
            $value = $item->{$f['name']};
            $form[$f['name']] = $f['type'] === 'tags'
                ? implode(', ', $value ?? [])
                : (string) ($value ?? '');
        }

        $form['order'] = (int) $item->order;

        if ($this->hasCvFlag()) {
            $form['include_in_cv'] = (bool) $item->include_in_cv;
        }

        $this->editingId = $item->id;
        $this->form = $form;
        $this->showForm = true;
    }

    public function cancel(): void
    {
        $this->resetValidation();
        $this->showForm = false;
        $this->editingId = null;
    }

    protected function rules(): array
    {
        $rules = ['form.order' => 'required|integer|min:1'];

        foreach ($this->def['fields'] as $f) {
            $rules['form.'.$f['name']] = $f['rules'];
        }

        if ($this->hasCvFlag()) {
            $rules['form.include_in_cv'] = 'boolean';
        }

        return $rules;
    }

    protected function validationAttributes(): array
    {
        $attributes = ['form.order' => 'ordre'];

        foreach ($this->def['fields'] as $f) {
            $attributes['form.'.$f['name']] = mb_strtolower($f['label']);
        }

        return $attributes;
    }

    public function save(): void
    {
        $this->validate();

        $data = [];

        foreach ($this->def['fields'] as $f) {
            $value = trim((string) ($this->form[$f['name']] ?? ''));

            $data[$f['name']] = $f['type'] === 'tags'
                ? collect(explode(',', $value))->map(fn ($s) => trim($s))->filter()->values()->all()
                : ($value === '' ? null : $value);
        }

        $data['order'] = (int) $this->form['order'];

        if ($this->hasCvFlag()) {
            $data['include_in_cv'] = (bool) $this->form['include_in_cv'];
        }

        if ($this->editingId) {
            $this->relation()->whereKey($this->editingId)->firstOrFail()->update($data);
            $this->flash = 'Modifications enregistrées.';
        } else {
            $this->relation()->create($data);
            $this->flash = 'Élément ajouté.';
        }

        $this->cancel();
    }

    public function delete(int $id): void
    {
        $this->relation()->whereKey($id)->firstOrFail()->delete();

        if ($this->editingId === $id) {
            $this->cancel();
        }

        $this->flash = 'Élément supprimé.';
    }

    public function render()
    {
        return view('livewire.admin.section-manager', [
            'items' => $this->relation()->get(),
        ]);
    }
}