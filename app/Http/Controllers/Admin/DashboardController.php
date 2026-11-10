<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Portfolio;

class DashboardController extends Controller
{
    public function index()
    {
        $portfolio = Portfolio::where('user_id', auth()->id())
            ->withCount(['projects', 'skills', 'experiences', 'educations', 'languages', 'certifications'])
            ->first();

        return view('admin.dashboard', compact('portfolio'));
    }
}