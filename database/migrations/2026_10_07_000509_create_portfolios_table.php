<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portfolios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('title')->nullable();
            $table->string('tagline_fr')->nullable();
            $table->string('tagline_en')->nullable();
            $table->string('theme')->default('futuristic');
            $table->string('primary_color')->default('#10b981');
            $table->boolean('is_public')->default(true);
            $table->string('custom_domain')->nullable();
            $table->string('photo')->nullable();
            $table->string('cv_file')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portfolios');
    }
};