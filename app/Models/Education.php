<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Education extends Model
{
    protected $table = 'educations';

    protected $fillable = [
        'portfolio_id', 'degree_fr', 'degree_en', 'school', 'year',
        'desc_fr', 'desc_en', 'order', 'include_in_cv',
    ];

    protected $casts = [
        'include_in_cv' => 'boolean',
    ];

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }
}