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
        Schema::create('rendez_vous_ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicule_id')->nullable()->constrained('vehicules')->onDelete('set null');
            $table->string('marque_vehicule');
            $table->string('serie_vehicule');
            $table->string('annee_vehicule');
            $table->string('transmission_vehicule');
            $table->string('carburant_vehicule');
            $table->string('nb_places_vehicule');
            $table->string('image_vehicule');
            $table->string('prix_vehicule');
            $table->date('date_rdv');
            $table->time('heure_rdv');
            $table->string('nom_complet');
            $table->string('telephone');
            $table->string('email')->nullable();
            $table->text('notes')->nullable();
            $table->enum('statut', ['en_attente', 'confirme', 'en_negociation', 'annule', 'termine'])->default('en_attente');
            $table->enum('fin_rdv', ['achete', 'refuse'])->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rendez_vous_ventes');
    }
    
}; 