<?php

namespace App\Http\Middleware;

use App\Models\Portfolio;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;

class ResolvePortfolio
{
    public function handle(Request $request, Closure $next)
    {
        $slug = $request->route('portfolioSlug');

        abort_if(! is_string($slug) || $slug === '', 404);

        abort_if(in_array($slug, config('portfolio.reserved_slugs', []), true), 404);

        $portfolio = Portfolio::where('slug', $slug)->firstOrFail();

        // Un portfolio non public reste visible par son propriétaire (prévisualisation)
        $isOwner = $request->user()?->id === $portfolio->user_id;
        abort_unless($portfolio->is_public || $isOwner, 404);

        $request->attributes->set('portfolio', $portfolio);
        View::share('currentPortfolio', $portfolio);

        URL::defaults(['portfolioSlug' => $portfolio->slug]);

        // Les contrôleurs gardent leur signature (locale, slug...)
        $request->route()->forgetParameter('portfolioSlug');

        return $next($request);
    }
}