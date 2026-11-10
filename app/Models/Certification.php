<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Certification extends Model
{
    protected $fillable = [
        'portfolio_id', 'title_fr', 'title_en', 'issuer', 'year',
        'include_in_cv', 'order',
    ];

    protected $casts = [
        'include_in_cv' => 'boolean',
    ];

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
}