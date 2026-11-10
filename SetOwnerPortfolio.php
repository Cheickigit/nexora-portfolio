<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class SetOwnerPortfolio
{
    public function handle(Request $request, Closure $next)
    {
        $portfolio = $request->user()?->portfolio;

        if ($portfolio) {
            URL::defaults(['portfolioSlug' => $portfolio->slug]);
            View::share('currentPortfolio', $portfolio);
        } else {
            // Évite les erreurs de vue si un layout admin s'affiche sans portfolio
            View::share('currentPortfolio', null);
        }

        return $next($request);
    }
}