<?php

return [

    'general' => [
        'label' => ['fr' => 'Général', 'en' => 'General'],
        'sections' => ['experiences', 'educations', 'projects', 'skills', 'languages', 'certifications'],
        'projects_label' => ['fr' => 'Réalisations', 'en' => 'Achievements'],
        'project_types' => ['project', 'mission'],
        'meta_fields' => [],
    ],

    'tech' => [
        'label' => ['fr' => 'Informatique', 'en' => 'IT / Software'],
        'sections' => ['projects', 'skills', 'experiences', 'educations', 'certifications', 'languages'],
        'projects_label' => ['fr' => 'Projets', 'en' => 'Projects'],
        'project_types' => ['project', 'open_source', 'mission'],
        'meta_fields' => ['stack', 'repo_url', 'demo_url'],
    ],

    'finance' => [
        'label' => ['fr' => 'Finance / Comptabilité', 'en' => 'Finance / Accounting'],
        'sections' => ['experiences', 'educations', 'projects', 'skills', 'certifications', 'languages'],
        'projects_label' => ['fr' => 'Études de cas & missions', 'en' => 'Case studies & assignments'],
        'project_types' => ['case_study', 'internship_mission', 'thesis', 'analysis'],
        'meta_fields' => ['tools', 'methods', 'sector'],
    ],

    'law' => [
        'label' => ['fr' => 'Droit', 'en' => 'Law'],
        'sections' => ['educations', 'experiences', 'projects', 'skills', 'certifications', 'languages'],
        'projects_label' => ['fr' => 'Dossiers & publications', 'en' => 'Cases & publications'],
        'project_types' => ['case_study', 'thesis', 'publication', 'moot_court'],
        'meta_fields' => ['domain', 'jurisdiction'],
    ],

    'marketing' => [
        'label' => ['fr' => 'Marketing / Communication', 'en' => 'Marketing / Communication'],
        'sections' => ['projects', 'experiences', 'skills', 'educations', 'certifications', 'languages'],
        'projects_label' => ['fr' => 'Campagnes & projets', 'en' => 'Campaigns & projects'],
        'project_types' => ['campaign', 'project', 'mission'],
        'meta_fields' => ['channels', 'tools', 'kpis'],
    ],
];