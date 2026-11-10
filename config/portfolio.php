<?php

return [
    'default_slug' => env('PORTFOLIO_DEFAULT_SLUG'),

    'allow_registration' => env('PORTFOLIO_ALLOW_REGISTRATION', false),

    // Utilisé par les vues publiques (layouts/app.blade.php)
    'profile' => [
        'name'     => env('PORTFOLIO_PROFILE_NAME', 'NEXORA'),
        'tagline_fr' => env('PORTFOLIO_PROFILE_TAGLINE_FR', ''),
        'tagline_en' => env('PORTFOLIO_PROFILE_TAGLINE_EN', ''),
    ],

    'reserved_slugs' => [
        'admin', 'login', 'logout', 'register', 'dashboard', 'profile',
        'password', 'forgot-password', 'reset-password', 'verify-email',
        'confirm-password', 'email', 'livewire', 'storage', 'up', 'api',
        'cv', 'fr', 'en', 'www', 'app', 'assets', 'build', 'vendor',
    ],
];