<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_id')->constrained()->cascadeOnDelete();
            $table->string('slug');
            $table->string('title_fr');
            $table->string('title_en');
            $table->text('context_fr')->nullable();
            $table->text('context_en')->nullable();
            $table->string('role_fr')->nullable();
            $table->string('role_en')->nullable();
            $table->json('stack')->nullable();
            $table->text('challenges_fr')->nullable();
            $table->text('challenges_en')->nullable();
            $table->text('result_fr')->nullable();
            $table->text('result_en')->nullable();
            $table->boolean('confidential')->default(false);
            $table->string('demo_url')->nullable();
            $table->string('repo_url')->nullable();
            $table->string('image')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();

            $table->unique(['portfolio_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};