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
        Schema::disableForeignKeyConstraints();

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->boolean('has_been_virement')->default(false);
            $table->string('numero_virement')->nullable();
            $table->string('motif_virement')->nullable();
            $table->foreignId('purchase_id')->constrained();
            $table->date('echeance_date')->nullable();
            $table->integer('taux_tva');
            $table->boolean('paiement_immediat');
            $table->enum('type', ["espece","cheque","virement","versement","effet"]);
            $table->enum('status', ["paid","pending"]);
            $table->text('proof_file')->nullable();
            $table->text('original_file_name')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
