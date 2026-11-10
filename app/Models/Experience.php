<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Experience extends Model
{
    protected $fillable = [
        'portfolio_id', 'role_fr', 'role_en', 'company',
        'period_fr', 'period_en', 'desc_fr', 'desc_en',
        'order', 'include_in_cv',
    ];

    protected $casts = [
        'include_in_cv' => 'boolean',
    ];

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
}