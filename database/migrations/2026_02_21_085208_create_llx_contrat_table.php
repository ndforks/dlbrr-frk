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
        Schema::create('llx_contrat', function (Blueprint $table) {
            $table->id('rowid');
            $table->string('ref', 255)->nullable();
            $table->integer('entity')->default(1);
            $table->timestamp('tms')->nullable();
            $table->datetime('datec')->nullable();
            $table->date('date_contrat')->nullable();
            $table->date('statut')->default(0);
            $table->date('mise_en_service')->nullable();
            $table->date('fin_validite')->nullable();
            $table->date('date_cloture')->nullable();
            $table->unsignedBigInteger('fk_soc');
            $table->unsignedBigInteger('fk_projet')->nullable();
            $table->integer('fk_commercial_signature')->nullable();
            $table->integer('fk_commercial_suivi')->nullable();
            $table->integer('fk_user_author')->nullable();
            $table->integer('fk_user_modif')->nullable();
            $table->integer('fk_user_cloture')->nullable();
            $table->text('note_private')->nullable();
            $table->text('note_public')->nullable();
            $table->string('model_pdf', 255)->nullable();
            $table->string('import_key', 14)->nullable();
            $table->text('extraparams')->nullable();
            
            $table->unique('ref');
            $table->index(['entity', 'statut']);
            $table->index('fk_soc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_contrat');
    }
};
