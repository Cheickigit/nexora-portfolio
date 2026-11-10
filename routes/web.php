<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Models\Portfolio;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Portfolios publics (multi-clients) : /{slug}/{locale}
|--------------------------------------------------------------------------
*/

$reserved = implode('|', config('portfolio.reserved_slugs', []));
$slugPattern = '[a-z0-9]+(?:-[a-z0-9]+)*';

Route::get('/', function () {
    $slug = config('portfolio.default_slug')
        ?: Portfolio::where('is_public', true)->value('slug');

    return $slug ? redirect("/{$slug}/fr") : view('welcome');
});

// /{slug} -> /{slug}/fr (les slugs réservés sont exclus)
Route::get('/{portfolioSlug}', fn (string $portfolioSlug) => redirect("/{$portfolioSlug}/fr"))
    ->where('portfolioSlug', "(?!(?:{$reserved})$){$slugPattern}")
    ->name('portfolio.redirect');

Route::prefix('{portfolioSlug}/{locale}')
    ->where([
        'portfolioSlug' => $slugPattern,
        'locale' => 'fr|en',
    ])
    ->middleware('portfolio')
    ->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('/projets/{slug}', [ProjectController::class, 'show'])->name('projects.show');
    });

/*
|--------------------------------------------------------------------------
| Routes Breeze (auth)
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', fn () => redirect()->route('admin.dashboard'))->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth', 'license'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';