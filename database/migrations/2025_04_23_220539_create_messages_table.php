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
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->string('nomcomplet_sender');
            $table->string('email_sender');
            $table->string('telephone_sender');
            $table->string('sujet');
            $table->text('message');
            $table->enum('statut', ['non_lu', 'lu'])->default('non_lu');
            $table->foreignId('sender_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('receiver_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
