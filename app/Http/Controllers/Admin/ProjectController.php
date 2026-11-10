<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;
use App\Models\Project;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    public function index()
    {
        $portfolio = Portfolio::where('user_id', auth()->id())
            ->with('projects')
            ->first();

        if (! $portfolio) {
            return redirect()
                ->route('admin.dashboard')
                ->with('warning', 'Aucun portfolio associé à votre compte.');
        }

        $projects = $portfolio->projects()->orderBy('order')->get();

        return view('admin.projects.index', compact('portfolio', 'projects'));
    }

    public function create()
    {
        if (! Portfolio::where('user_id', auth()->id())->exists()) {
            return redirect()->route('admin.dashboard');
        }

        Gate::authorize('create', Project::class);

        return view('admin.projects.create');
    }

    public function edit(Project $project)
    {
        Gate::authorize('update', $project);

        return view('admin.projects.edit', compact('project'));
    }
}