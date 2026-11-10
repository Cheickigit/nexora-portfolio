<?php

use App\Support\Locale;

if (! function_exists('locale_pick')) {
    function locale_pick($model, string $field, ?string $locale = null): string
    {
        return Locale::pick($model, $field, $locale);
    }
}