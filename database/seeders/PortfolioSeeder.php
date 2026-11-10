<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Portfolio;
use App\Models\Project;
use App\Models\Skill;
use App\Models\Experience;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        // Valeurs récupérées depuis le .env, avec des valeurs par défaut si absentes
        $email = env('PORTFOLIO_SEED_EMAIL', 'demo@nexora.test');
        $name  = env('PORTFOLIO_SEED_NAME', 'Madi Demo');

        // 1. Créer (ou récupérer) l'utilisateur
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name'     => $name,
                'password' => bcrypt(env('PORTFOLIO_SEED_PASSWORD', 'password')),
            ]
        );

        // 2. Créer ou mettre à jour le portfolio avec TOUS les champs
        $portfolio = Portfolio::updateOrCreate(
            ['user_id' => $user->id],
            [
                'slug'          => 'madi',
                'title'         => 'Développeur Web Laravel / Livewire',
                'full_name'     => $name,
                'email'         => $email,
                'phone'         => null,
                'location'      => null,
                'profile_type'  => 'tech',
                'tagline_fr'    => 'Je conçois des applications web robustes, du besoin métier à la mise en production.',
                'tagline_en'    => 'I build robust web applications, from business needs to production.',
                'summary_fr'    => "Développeur Laravel/Livewire avec plusieurs années d'expérience en environnement bancaire et en freelance.",
                'summary_en'    => 'Laravel/Livewire developer with several years of experience in banking and freelance.',
                'theme'         => 'futuristic',
                'primary_color' => '#10b981',
                'is_public'     => true,
                'links'         => [
                    ['label' => 'GitHub', 'url' => 'https://github.com/example'],
                    ['label' => 'LinkedIn', 'url' => 'https://linkedin.com/in/example'],
                ],
            ]
        );

        // 3. Projets (avec 'type', 'include_in_cv' et 'demo_url' quand pertinent)
        $projects = [
            [
                'slug'         => 'myincident',
                'type'         => 'project',
                'title_fr'     => 'MyIncident — Suivi des incidents',
                'title_en'     => 'MyIncident — Incident tracking',
                'context_fr'   => 'Application de suivi et de gestion des incidents pour un environnement bancaire.',
                'context_en'   => 'Incident tracking and management application for a banking environment.',
                'role_fr'      => 'Développeur + Product Owner',
                'role_en'      => 'Developer + Product Owner',
                'stack'        => ['Laravel', 'Livewire', 'MySQL', 'Tailwind'],
                'challenges_fr'=> 'Centraliser les incidents, automatiser les notifications, garantir la traçabilité.',
                'challenges_en'=> 'Centralize incidents, automate notifications, ensure traceability.',
                'result_fr'    => 'Réduction du temps de traitement et meilleure visibilité pour les équipes.',
                'result_en'    => 'Reduced processing time and better visibility for teams.',
                'confidential' => true,
                'include_in_cv'=> true,
                'order'        => 1,
            ],
            [
                'slug'         => 'caisse',
                'type'         => 'project',
                'title_fr'     => 'Gestion de caisse',
                'title_en'     => 'Cash management',
                'context_fr'   => 'Application de gestion de caisse pour agence bancaire.',
                'context_en'   => 'Cash management application for a banking branch.',
                'role_fr'      => 'Développeur',
                'role_en'      => 'Developer',
                'stack'        => ['Laravel', 'MySQL', 'Bootstrap'],
                'challenges_fr'=> 'Sécuriser les transactions et fiabiliser les clôtures.',
                'challenges_en'=> 'Secure transactions and make closings reliable.',
                'result_fr'    => 'Clôtures fiabilisées et gains de temps quotidiens.',
                'result_en'    => 'Reliable closings and daily time savings.',
                'confidential' => true,
                'include_in_cv'=> true,
                'order'        => 2,
            ],
            [
                'slug'         => 'dashboardrisques',
                'type'         => 'project',
                'title_fr'     => 'Dashboard Risques opérationnels',
                'title_en'     => 'Operational Risk Dashboard',
                'context_fr'   => 'Tableau de bord de pilotage des risques opérationnels.',
                'context_en'   => 'Operational risk monitoring dashboard.',
                'role_fr'      => 'Développeur + Product Owner',
                'role_en'      => 'Developer + Product Owner',
                'stack'        => ['Laravel', 'Livewire', 'Chart.js', 'Tailwind'],
                'challenges_fr'=> 'Visualiser des indicateurs complexes de manière claire.',
                'challenges_en'=> 'Visualize complex indicators clearly.',
                'result_fr'    => 'Décisions plus rapides grâce à une lecture claire des risques.',
                'result_en'    => 'Faster decisions through clear risk reading.',
                'confidential' => true,
                'include_in_cv'=> true,
                'order'        => 3,
            ],
            [
                'slug'         => 'smartrestaurant',
                'type'         => 'project',
                'title_fr'     => 'SmartRestaurant',
                'title_en'     => 'SmartRestaurant',
                'context_fr'   => 'Application de gestion de restaurant : commandes, tables, stock.',
                'context_en'   => 'Restaurant management app: orders, tables, stock.',
                'role_fr'      => 'Développeur full-stack',
                'role_en'      => 'Full-stack developer',
                'stack'        => ['Laravel', 'Livewire', 'Tailwind', 'MySQL'],
                'challenges_fr'=> 'Temps réel sans rechargement, modélisation des tables et commandes.',
                'challenges_en'=> 'Real-time without reload, modeling tables and orders.',
                'result_fr'    => 'Interface fluide et responsive, démo en ligne.',
                'result_en'    => 'Smooth responsive interface, live demo.',
                'confidential' => false,
                'include_in_cv'=> true,
                'demo_url'     => 'https://example.com/smartrestaurant',
                'order'        => 4,
            ],
            [
                'slug'         => 'erp-freelance',
                'type'         => 'mission',
                'title_fr'     => 'ERP sur mesure (freelance)',
                'title_en'     => 'Custom ERP (freelance)',
                'context_fr'   => 'ERP développé sur mesure pour un client.',
                'context_en'   => 'Custom ERP built for a client.',
                'role_fr'      => 'Développeur + chef de projet',
                'role_en'      => 'Developer + project lead',
                'stack'        => ['Laravel', 'Livewire', 'MySQL', 'Tailwind'],
                'challenges_fr'=> 'Comprendre le besoin métier et livrer dans les délais.',
                'challenges_en'=> 'Understand business needs and deliver on time.',
                'result_fr'    => 'Client satisfait, processus métier digitalisé.',
                'result_en'    => 'Satisfied client, digitized business process.',
                'confidential' => true,
                'include_in_cv'=> true,
                'order'        => 5,
            ],
        ];

        foreach ($projects as $p) {
            Project::updateOrCreate(
                ['portfolio_id' => $portfolio->id, 'slug' => $p['slug']],
                array_merge($p, ['portfolio_id' => $portfolio->id])
            );
        }

        // 4. Compétences
        $skills = [
            [
                'category' => 'backend',
                'label_fr' => 'Back-end',
                'label_en' => 'Back-end',
                'items'    => ['PHP', 'Laravel', 'Livewire', 'API REST', 'MySQL'],
                'order'    => 1,
            ],
            [
                'category' => 'frontend',
                'label_fr' => 'Front-end',
                'label_en' => 'Front-end',
                'items'    => ['HTML', 'CSS', 'JavaScript', 'Tailwind', 'Bootstrap', 'Alpine.js'],
                'order'    => 2,
            ],
            [
                'category' => 'tools',
                'label_fr' => 'Outils & méthodes',
                'label_en' => 'Tools & methods',
                'items'    => ['Git', 'GitHub', 'Scrum', 'Product Ownership', 'Docker (bases)'],
                'order'    => 3,
            ],
        ];

        foreach ($skills as $s) {
            Skill::updateOrCreate(
                ['portfolio_id' => $portfolio->id, 'category' => $s['category']],
                array_merge($s, ['portfolio_id' => $portfolio->id])
            );
        }

        // 5. Expériences
        $experiences = [
            [
                'role_fr'   => 'Développeur Web / Product Owner',
                'role_en'   => 'Web Developer / Product Owner',
                'company'   => 'Secteur bancaire',
                'period_fr' => '2022 — aujourd’hui',
                'period_en' => '2022 — present',
                'desc_fr'   => 'Développement d’applications métier (incidents, risques, caisse) et pilotage produit.',
                'desc_en'   => 'Development of business applications (incidents, risks, cash) and product ownership.',
                'include_in_cv' => true,
                'order'     => 1,
            ],
            [
                'role_fr'   => 'Développeur freelance',
                'role_en'   => 'Freelance developer',
                'company'   => 'Clients divers',
                'period_fr' => '2021 — aujourd’hui',
                'period_en' => '2021 — present',
                'desc_fr'   => 'Conception et développement d’ERP et applications sur mesure.',
                'desc_en'   => 'Design and development of custom ERPs and applications.',
                'include_in_cv' => true,
                'order'     => 2,
            ],
        ];

        foreach ($experiences as $e) {
            Experience::updateOrCreate(
                ['portfolio_id' => $portfolio->id, 'company' => $e['company']],
                array_merge($e, ['portfolio_id' => $portfolio->id])
            );
        }

        $this->command->info("✅ Portfolio de {$user->name} créé avec {$portfolio->projects()->count()} projets.");
    }
}