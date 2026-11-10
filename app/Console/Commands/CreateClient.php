<?php

namespace App\Console\Commands;

use App\Models\Portfolio;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CreateClient extends Command
{
    protected $signature = 'portfolio:create-client
        {name : Nom complet du client}
        {email : Email de connexion}
        {--slug= : Adresse du portfolio (par défaut : dérivée du nom)}
        {--profile=general : Filière (general, tech, finance, law, marketing...)}
        {--plan=free : Plan de la licence}
        {--password= : Mot de passe (généré si absent)}';

    protected $description = 'Crée un client : compte, portfolio vide et licence';

    public function handle(): int
    {
        $name = trim($this->argument('name'));
        $email = strtolower(trim($this->argument('email')));
        $profile = (string) $this->option('profile');

        $validator = Validator::make(
            ['email' => $email, 'profile' => $profile],
            [
                'email' => 'required|email|unique:users,email',
                'profile' => 'in:'.implode(',', array_keys(config('portfolio_profiles'))),
            ],
            ['profile.in' => 'Filière inconnue. Valeurs possibles : '.implode(', ', array_keys(config('portfolio_profiles'))).'.']
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $slug = $this->uniqueSlug($this->option('slug') ?: $name);

        if (! $slug) {
            $this->error('Impossible de déduire un slug : utilisez --slug=mon-slug.');

            return self::FAILURE;
        }

        $password = $this->option('password') ?: Str::password(12, symbols: false);
        $licenseKey = strtoupper(Str::random(20));

        DB::transaction(function () use ($name, $email, $profile, $slug, $password, $licenseKey) {
            $user = User::create(['name' => $name, 'email' => $email, 'password' => $password]);
            $user->forceFill(['email_verified_at' => now()])->save();

            Portfolio::create([
                'user_id'      => $user->id,
                'slug'         => $slug,
                'full_name'    => $name,
                'email'        => $email,
                'profile_type' => $profile,
                'is_public'    => false,
            ]);

            DB::table('licenses')->insert([
                'user_id'    => $user->id,
                'key'        => $licenseKey,
                'plan'       => (string) $this->option('plan'),
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        $this->info('Client créé. À transmettre :');
        $this->table(['', ''], [
            ['Nom', $name],
            ['Email', $email],
            ['Mot de passe', $password],
            ['Connexion', url('/login')],
            ['Portfolio (une fois publié)', url("/{$slug}/fr")],
            ['Filière', $profile],
        ]);
        $this->line('Le portfolio est non public : le client le publie depuis son profil.');

        return self::SUCCESS;
    }

    private function uniqueSlug(string $base): ?string
    {
        $slug = Str::slug($base);

        if ($slug === '') {
            return null;
        }

        $reserved = config('portfolio.reserved_slugs', []);
        $candidate = $slug;
        $i = 2;

        while (in_array($candidate, $reserved, true) || Portfolio::where('slug', $candidate)->exists()) {
            $candidate = $slug.'-'.$i++;
        }

        return $candidate;
    }
}