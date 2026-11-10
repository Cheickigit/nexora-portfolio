<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function show(Request $request, string $locale, string $slug)
    {
        $portfolio = $request->attributes->get('portfolio');
        $project = $portfolio->projects()->where('slug', $slug)->firstOrFail();

        return view('projects.show', compact('project'));
    }
}