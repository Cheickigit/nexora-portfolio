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
        // On vérifie que l'utilisateur est connecté ET qu'il a un portfolio
        $portfolio = $request->user()?->portfolio;

        if ($portfolio) {
            URL::defaults(['portfolioSlug' => $portfolio->slug]);
            View::share('currentPortfolio', $portfolio);
        } else {
            // Si pas de portfolio, on partage null pour éviter les erreurs dans les vues
            View::share('currentPortfolio', null);
        }

        return $next($request);
    }
}
