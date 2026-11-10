<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->string('type')->default('project')->after('slug');
            $table->json('meta')->nullable()->after('stack');
            $table->boolean('include_in_cv')->default(true)->after('confidential');
        });

        Schema::table('experiences', function (Blueprint $table) {
            $table->boolean('include_in_cv')->default(true)->after('desc_en');
        });

        Schema::table('educations', function (Blueprint $table) {
            $table->boolean('include_in_cv')->default(true)->after('desc_en');
        });
    }

    public function down(): void
    {
        Schema::table('projects', fn (Blueprint $t) => $t->dropColumn(['type', 'meta', 'include_in_cv']));
        Schema::table('experiences', fn (Blueprint $t) => $t->dropColumn('include_in_cv'));
        Schema::table('educations', fn (Blueprint $t) => $t->dropColumn('include_in_cv'));
    }
};