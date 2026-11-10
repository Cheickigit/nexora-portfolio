<?php

/*
 * Sections de type "liste" gérées par Admin\SectionManager.
 * type : text | textarea | tags (valeurs séparées par des virgules)
 * full : le champ occupe toute la largeur
 */
return [

    'experiences' => [
        'relation' => 'experiences',
        'title' => 'Expériences',
        'cv_flag' => true,
        'list' => ['role_fr' => 'Poste', 'company' => 'Organisation', 'period_fr' => 'Période'],
        'fields' => [
            ['name' => 'role_fr', 'label' => 'Poste (FR)', 'type' => 'text', 'rules' => 'required|string|max:255'],
            ['name' => 'role_en', 'label' => 'Poste (EN)', 'type' => 'text', 'rules' => 'required|string|max:255'],
            ['name' => 'company', 'label' => 'Entreprise / organisation', 'type' => 'text', 'full' => true, 'rules' => 'required|string|max:255'],
            ['name' => 'period_fr', 'label' => 'Période (FR)', 'type' => 'text', 'placeholder' => 'Jan 2024 – Présent', 'rules' => 'nullable|string|max:255'],
            ['name' => 'period_en', 'label' => 'Période (EN)', 'type' => 'text', 'placeholder' => 'Jan 2024 – Present', 'rules' => 'nullable|string|max:255'],
            ['name' => 'desc_fr', 'label' => 'Description (FR)', 'type' => 'textarea', 'rules' => 'nullable|string|max:2000'],
            ['name' => 'desc_en', 'label' => 'Description (EN)', 'type' => 'textarea', 'rules' => 'nullable|string|max:2000'],
        ],
    ],

    'educations' => [
        'relation' => 'educations',
        'title' => 'Formations',
        'cv_flag' => true,
        'list' => ['degree_fr' => 'Diplôme', 'school' => 'Établissement', 'year' => 'Année'],
        'fields' => [
            ['name' => 'degree_fr', 'label' => 'Diplôme (FR)', 'type' => 'text', 'rules' => 'required|string|max:255'],
            ['name' => 'degree_en', 'label' => 'Diplôme (EN)', 'type' => 'text', 'rules' => 'required|string|max:255'],
            ['name' => 'school', 'label' => 'Établissement', 'type' => 'text', 'full' => true, 'rules' => 'required|string|max:255'],
            ['name' => 'year', 'label' => 'Année / période', 'type' => 'text', 'full' => true, 'placeholder' => '2021 – 2024', 'rules' => 'nullable|string|max:255'],
            ['name' => 'desc_fr', 'label' => 'Description (FR)', 'type' => 'textarea', 'rules' => 'nullable|string|max:2000'],
            ['name' => 'desc_en', 'label' => 'Description (EN)', 'type' => 'textarea', 'rules' => 'nullable|string|max:2000'],
        ],
    ],

    'skills' => [
        'relation' => 'skills',
        'title' => 'Compétences',
        'cv_flag' => false,
        'list' => ['label_fr' => 'Groupe', 'category' => 'Catégorie'],
        'fields' => [
            ['name' => 'category', 'label' => 'Catégorie (identifiant court)', 'type' => 'text', 'full' => true, 'placeholder' => 'backend', 'rules' => 'required|string|max:100'],
            ['name' => 'label_fr', 'label' => 'Titre du groupe (FR)', 'type' => 'text', 'rules' => 'required|string|max:255'],
            ['name' => 'label_en', 'label' => 'Titre du groupe (EN)', 'type' => 'text', 'rules' => 'required|string|max:255'],
            ['name' => 'items', 'label' => 'Compétences (séparées par des virgules)', 'type' => 'tags', 'full' => true, 'placeholder' => 'Excel, Sage, IFRS', 'rules' => 'nullable|string|max:1000'],
        ],
    ],

    'languages' => [
        'relation' => 'languages',
        'title' => 'Langues',
        'cv_flag' => false,
        'list' => ['name_fr' => 'Langue', 'level_fr' => 'Niveau'],
        'fields' => [
            ['name' => 'name_fr', 'label' => 'Langue (FR)', 'type' => 'text', 'placeholder' => 'Français', 'rules' => 'required|string|max:255'],
            ['name' => 'name_en', 'label' => 'Langue (EN)', 'type' => 'text', 'placeholder' => 'French', 'rules' => 'required|string|max:255'],
            ['name' => 'level_fr', 'label' => 'Niveau (FR)', 'type' => 'text', 'placeholder' => 'Langue maternelle', 'rules' => 'nullable|string|max:255'],
            ['name' => 'level_en', 'label' => 'Niveau (EN)', 'type' => 'text', 'placeholder' => 'Native', 'rules' => 'nullable|string|max:255'],
        ],
    ],

    'certifications' => [
        'relation' => 'certifications',
        'title' => 'Certifications',
        'cv_flag' => true,
        'list' => ['title_fr' => 'Certification', 'issuer' => 'Organisme', 'year' => 'Année'],
        'fields' => [
            ['name' => 'title_fr', 'label' => 'Intitulé (FR)', 'type' => 'text', 'rules' => 'required|string|max:255'],
            ['name' => 'title_en', 'label' => 'Intitulé (EN)', 'type' => 'text', 'rules' => 'required|string|max:255'],
            ['name' => 'issuer', 'label' => 'Organisme', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
            ['name' => 'year', 'label' => 'Année', 'type' => 'text', 'rules' => 'nullable|string|max:255'],
        ],
    ],
];