<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureLicenseIsValid
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        // 1. Si l'utilisateur n'est pas connecté, on laisse le middleware 'auth' gérer ça
        if (! $user) {
            return $next($request);
        }

        // 2. On récupère la licence de l'utilisateur
        $license = $user->license; // La relation sera créée à l'étape 3

        // 3. On vérifie si la licence est valide
        $isValid = $license 
            && $license->is_active 
            && ($license->expires_at === null || $license->expires_at->isFuture());

        // 4. Si la licence n'est pas valide, on le déconnecte
        if (! $isValid) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Votre licence a expiré ou a été désactivée. Veuillez contacter l\'administrateur.',
            ]);
        }

        return $next($request);
    }
}