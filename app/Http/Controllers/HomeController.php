<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $portfolio = $request->attributes->get('portfolio');

        $portfolio->load([
            'user', 'projects', 'skills', 'experiences',
            'educations', 'languages', 'certifications',
        ]);

        return view('home', [
            'portfolio' => $portfolio,
            'profile' => [
                'name'       => $portfolio->displayName(),
                'title_fr'   => $portfolio->title,
                'title_en'   => $portfolio->title,
                'tagline_fr' => $portfolio->tagline_fr,
                'tagline_en' => $portfolio->tagline_en,
                'summary_fr' => $portfolio->summary_fr,
                'summary_en' => $portfolio->summary_en,
                'email'      => $portfolio->email ?: $portfolio->user->email,
                'phone'      => $portfolio->phone,
                'location'   => $portfolio->location,
                'github'     => $portfolio->link('github') ?? '',
                'linkedin'   => $portfolio->link('linkedin') ?? '',
                'whatsapp'   => $portfolio->link('whatsapp') ?? '',
                'cv_url'     => $portfolio->cv_file ? asset('storage/'.$portfolio->cv_file) : '/cv.pdf',
            ],
            'projects'       => $portfolio->projects,
            'skills'         => $portfolio->skills,
            'experiences'    => $portfolio->experiences,
            'educations'     => $portfolio->educations,
            'languages'      => $portfolio->languages,
            'certifications' => $portfolio->certifications,
        ]);
    }
}