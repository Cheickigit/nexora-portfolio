<?php

namespace App\Support;

use Illuminate\Database\Eloquent\Model;

class Locale
{
    /**
     * Retourne la valeur d'un champ traduit avec fallback sur l'autre locale.
     * Ex : locale_pick($project, 'title') -> $project->title_fr (ou title_en).
     */
    public static function pick(?Model $model, string $field, ?string $locale = null): string
    {
        if (! $model) {
            return '';
        }

        $locale ??= app()->getLocale();
        $other = $locale === 'fr' ? 'en' : 'fr';

        return (string) (
            $model->{$field.'_'.$locale}
            ?: ($model->{$field.'_'.$other} ?? '')
        );
    }

    /**
     * Version closure pratique pour les vues.
     */
    public static function picker(?string $locale = null): \Closure
    {
        $locale ??= app()->getLocale();

        return fn (?Model $m, string $f) => self::pick($m, $f, $locale);
    }
}