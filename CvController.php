<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CvController extends Controller
{
    public function __invoke(Request $request, string $locale)
    {
        abort_unless(in_array($locale, ['fr', 'en'], true), 404);

        $portfolio = $request->user()->portfolio ?? abort(404);

        $photo = null;
        if ($portfolio->photo && Storage::disk('public')->exists($portfolio->photo)) {
            $photo = 'data:'.Storage::disk('public')->mimeType($portfolio->photo)
                .';base64,'.base64_encode(Storage::disk('public')->get($portfolio->photo));
        }

        $pdf = Pdf::loadView('cv.template', [
            'locale'         => $locale,
            'portfolio'      => $portfolio,
            'photo'          => $photo,
            'experiences'    => $portfolio->experiences()->where('include_in_cv', true)->get(),
            'educations'     => $portfolio->educations()->where('include_in_cv', true)->get(),
            'projects'       => $portfolio->projects()->where('include_in_cv', true)->limit(5)->get(),
            'certifications' => $portfolio->certifications()->where('include_in_cv', true)->get(),
            'skills'         => $portfolio->skills,
            'languages'      => $portfolio->languages,
        ])->setPaper('a4');

        $file = 'CV-'.Str::slug($portfolio->displayName() ?: $portfolio->slug).'-'.$locale.'.pdf';

        return $request->boolean('download') ? $pdf->download($file) : $pdf->stream($file);
    }
}