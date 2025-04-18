<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('candidate_skill', function (Blueprint $table) {
            $table->foreignId('candidate_id')->constrained();
            $table->foreignId('skill_id')->constrained();
            $table->primary(['candidate_id', 'skill_id']); 
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('candidate_skill');
    }
};
