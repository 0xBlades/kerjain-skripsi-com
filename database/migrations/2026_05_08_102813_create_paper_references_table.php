<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('paper_references', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_id')->constrained()->cascadeOnDelete();
            $table->string('external_id')->nullable();
            $table->string('title');
            $table->string('authors')->nullable();
            $table->text('abstract')->nullable();
            $table->string('publisher')->nullable();
            $table->string('publication_type')->nullable();
            $table->date('published_at')->nullable();
            $table->string('url')->nullable();
            $table->json('metadata')->nullable();
            $table->boolean('is_favorited')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paper_references');
    }
};
