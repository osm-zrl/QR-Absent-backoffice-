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
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_sessions_id')->constrained('school_sessions')->onDelete('cascade'); // Référence à la table sessions
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Référence à la table etudiants
            $table->timestamp('timestamp'); // Heure de l'enregistrement
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendance_records');
    }
};
