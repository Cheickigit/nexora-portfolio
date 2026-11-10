<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->string('profile_type')->default('general')->after('theme');
            $table->string('full_name')->nullable()->after('title');
            $table->string('email')->nullable()->after('full_name');
            $table->string('phone')->nullable()->after('email');
            $table->string('location')->nullable()->after('phone');
            $table->json('links')->nullable()->after('location');
            $table->text('summary_fr')->nullable()->after('tagline_en');
            $table->text('summary_en')->nullable()->after('summary_fr');
        });
    }

    public function down(): void
    {
        Schema::table('portfolios', function (Blueprint $table) {
            $table->dropColumn([
                'profile_type', 'full_name', 'email', 'phone',
                'location', 'links', 'summary_fr', 'summary_en',
            ]);
        });
    }
};