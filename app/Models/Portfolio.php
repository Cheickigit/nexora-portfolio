<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Portfolio extends Model
{
    protected $fillable = [
        'user_id', 'slug', 'title', 'full_name', 'email', 'phone', 'location', 'links',
        'tagline_fr', 'tagline_en', 'summary_fr', 'summary_en',
        'theme', 'profile_type', 'primary_color', 'is_public', 'custom_domain',
        'photo', 'logo', 'cv_file',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'links' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class)->orderBy('order');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class)->orderBy('order');
    }

    public function experiences(): HasMany
    {
        return $this->hasMany(Experience::class)->orderBy('order');
    }

    public function educations(): HasMany
    {
        return $this->hasMany(Education::class)->orderBy('order');
    }

    public function languages(): HasMany
    {
        return $this->hasMany(Language::class)->orderBy('order');
    }

    public function certifications(): HasMany
    {
        return $this->hasMany(Certification::class)->orderBy('order');
    }

    /** Configuration de la filière (libellés, sections, champs). */
    public function profileConfig(): array
    {
        return config('portfolio_profiles.'.$this->profile_type)
            ?? config('portfolio_profiles.general');
    }

    public function displayName(): string
    {
        return $this->full_name ?: ($this->user->name ?? '');
    }

    /** Retrouve un lien par libellé (ex. "github", "linkedin"). */
    public function link(string $needle): ?string
    {
        foreach ($this->links ?? [] as $link) {
            if (stripos($link['label'] ?? '', $needle) !== false) {
                return $link['url'] ?? null;
            }
        }

        return null;
    }
}