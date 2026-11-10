<?php

use App\Http\Controllers\Admin\CvController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProjectController;
use Illuminate\Support\Facades\Route;

// ICI : Ajout de 'license' dans le tableau
Route::middleware(['auth', 'verified', 'license'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::view('/profil', 'admin.profile')->name('profile');

        Route::get('/cv/{locale}', CvController::class)
            ->whereIn('locale', ['fr', 'en'])
            ->name('cv');

        Route::get('/sections/{section}', fn (string $section) => view('admin.section', compact('section')))
            ->whereIn('section', array_keys(config('portfolio_sections')))
            ->name('sections');

        Route::get('/projets', [ProjectController::class, 'index'])->name('projects.index');
        Route::get('/projets/nouveau', [ProjectController::class, 'create'])->name('projects.create');
        Route::get('/projets/{project}/editer', [ProjectController::class, 'edit'])->name('projects.edit');
    });