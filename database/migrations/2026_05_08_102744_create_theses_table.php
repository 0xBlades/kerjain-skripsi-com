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
        Schema::create('theses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('topic')->nullable();
            $table->text('summary')->nullable();
            $table->string('status')->default('ideation');
            $table->unsignedTinyInteger('progress')->default(0);
            $table->date('target_completion_date')->nullable();
            $table->string('supervisor_name')->nullable();
            $table->text('research_question')->nullable();
            $table->string('methodology')->nullable();
            $table->json('keywords')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('theses');
    }
};
