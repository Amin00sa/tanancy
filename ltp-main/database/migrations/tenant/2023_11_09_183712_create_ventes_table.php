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

        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->string('numero');
            $table->foreignId('client_id')->constrained();
            $table->foreignId('marche_id')->nullable()->constrained();
            $table->text('objet');
            $table->decimal('total', 20, 2);
            $table->text('total_text');
            $table->integer('taux_tva');
            $table->date('date')->nullable();
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
        Schema::dropIfExists('ventes');
    }
};
