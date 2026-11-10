<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->route('locale');

        if (! in_array($locale, ['fr', 'en'], true)) {
            $locale = 'fr';
        }

        app()->setLocale($locale);

        return $next($request);
    }
}