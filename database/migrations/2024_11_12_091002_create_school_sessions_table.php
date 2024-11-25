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
        Schema::create('school_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('intitule');
            $table->date('date');
            $table->integer('period'); // Durée en minutes, par exemple
            $table->foreignId('enseignant_id')->constrained('enseignants')->onDelete('cascade'); // Référence à la table enseignants
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_sessions');
    }
};
