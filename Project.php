<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Project extends Model
{
    /**
     * Valeurs par défaut des attributs.
     *
     * @var array
     */
    protected $attributes = [
        'order' => 0,
        'confidential' => false,
        'include_in_cv' => true,
    ];

    protected $fillable = [
        'portfolio_id', 'slug', 'type', 'title_fr', 'title_en',
        'context_fr', 'context_en', 'role_fr', 'role_en',
        'stack', 'meta', 'challenges_fr', 'challenges_en',
        'result_fr', 'result_en', 'confidential', 'include_in_cv',
        'demo_url', 'repo_url', 'image', 'order',
    ];

    protected $casts = [
        'stack' => 'array',
        'meta' => 'array',
        'confidential' => 'boolean',
        'include_in_cv' => 'boolean',
    ];

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
}