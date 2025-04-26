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
        Schema::create('location_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('vehicule_id')->nullable()->constrained()->onDelete('set null');
            $table->string('marque_vehicule')->nullable();
            $table->string('serie_vehicule')->nullable();
            $table->foreignId('chauffeur_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('nom_chauffeur')->nullable();
            $table->string('prenom_chauffeur')->nullable();
            $table->string('numero_telephone_chauffeur')->nullable();
            $table->date('date_debut');
            $table->time('heure_depart');
            $table->date('date_fin');
            $table->string('lieu_depart');
            $table->string('lieu_arrivee');
            $table->string('point_depart');
            $table->decimal('prix_total', 10, 2);
            $table->enum('statut', ['en_attente', 'approuvee', 'refusee', 'terminee'])->default('en_attente');
            $table->string('mode_paiement');
            $table->string('numero_telephone');
            $table->string('email');
            $table->string('nom');
            $table->string('prenom');
            $table->string('reference_paiement');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_requests');
    }
};
