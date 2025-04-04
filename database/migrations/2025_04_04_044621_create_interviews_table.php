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
        Schema::create('interviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->foreignId('interviewer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('round', ['cv_screening', 'hr_interview', 'technical_interview', 'skill_test', 'final_interview']);
            $table->dateTime('interview_date');
            $table->string('interview_result')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interviews');
    }
};
