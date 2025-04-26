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
        Schema::create('vehicules', function (Blueprint $table) {
            $table->id();
            $table->string('marque');
            $table->string('serie');
            $table->integer('annee');
            $table->string('immatriculation')->unique();
            $table->decimal('prix_vente', 10, 2)->nullable();
            $table->decimal('prix_location_interieur', 10, 2)->nullable();
            $table->decimal('prix_location_abidjan', 10, 2)->nullable();
            $table->string('image_principale');
            $table->string('image_secondaire');
            $table->string('image_tertiaire');
            $table->boolean('disponibilite')->default(true);
            $table->boolean('visibilite')->default(true);
            $table->string('type_vehicule');
            $table->string('carburant');
            $table->string('transmission');
            $table->integer('nb_places');
            $table->string('etat_vehicule');
            $table->string('fin_vehicule')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicules');
    }
};
