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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('N_ticket')->nullable();
            $table->string('probleme_declare');
            $table->string('commentaires');
            $table->date('date_de_qualification')->nullable();
            $table->string('qualifie_par')->nullable();
            $table->enum('type_probleme', ['Materiel', 'Reseaux', 'Application', 'Systeme_d_exploitation'])->nullable();
            $table->enum('type_materiel', ['Imprimante', 'Ordinateur', 'Scanner', 'Autre'])->nullable();
            $table->string('marque')->nullable();
            $table->enum('status', ['Ouvert', 'Qualifie', 'Repare', 'Cloture'])->default('Ouvert');
            $table->enum('priorite', ['Faible', 'Moyenne', 'Eleve', 'Urgent']);
            
            $table->string('probleme_rencontre')->nullable();
            $table->date('date_de_reparation')->nullable();
            $table->string('repare_par')->nullable();
            $table->string('lieu_de_reparation')->nullable();
            $table->date('date_de_cloture')->nullable();
            $table->string('travaux_effectues')->nullable();
            $table->string('image_path')->nullable();
            $table->foreignId('user_id')->nullable()->constrained(table:'users');
            $table->foreignId('technicien_id')
            ->nullable()
            ->constrained(table:'users');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
