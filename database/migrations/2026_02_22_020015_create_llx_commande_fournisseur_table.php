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
        Schema::create('llx_commande_fournisseur', function (Blueprint $table) {
            $table->id('rowid');
            $table->string('ref', 180)->nullable();
            $table->string('ref_supplier', 180)->nullable();
            $table->integer('entity')->default(1);
            $table->string('ref_ext', 255)->nullable();
            $table->unsignedBigInteger('fk_soc');
            $table->unsignedBigInteger('fk_projet')->nullable();
            $table->timestamp('tms')->nullable();
            $table->datetime('date_creation')->nullable();
            $table->datetime('date_valid')->nullable();
            $table->datetime('date_approve')->nullable();
            $table->datetime('date_approve2')->nullable();
            $table->date('date_commande')->nullable();
            $table->unsignedBigInteger('fk_user_author')->nullable();
            $table->unsignedBigInteger('fk_user_modif')->nullable();
            $table->unsignedBigInteger('fk_user_valid')->nullable();
            $table->unsignedBigInteger('fk_user_approve')->nullable();
            $table->unsignedBigInteger('fk_user_approve2')->nullable();
            $table->integer('source')->nullable();
            $table->smallInteger('fk_statut')->default(0);
            $table->smallInteger('billed')->default(0);
            $table->double('amount_ht', 24, 8)->default(0);
            $table->double('remise_percent', 6, 3)->default(0);
            $table->double('remise', 24, 8)->default(0);
            $table->double('total_ht', 24, 8)->default(0);
            $table->double('total_tva', 24, 8)->default(0);
            $table->double('localtax1', 24, 8)->default(0);
            $table->double('localtax2', 24, 8)->default(0);
            $table->double('total_ttc', 24, 8)->default(0);
            $table->text('note_private')->nullable();
            $table->text('note_public')->nullable();
            $table->string('model_pdf', 255)->nullable();
            $table->date('date_livraison')->nullable();
            $table->unsignedBigInteger('fk_account')->nullable();
            $table->unsignedBigInteger('fk_cond_reglement')->nullable();
            $table->unsignedBigInteger('fk_mode_reglement')->nullable();
            $table->unsignedBigInteger('fk_input_reason')->nullable();
            $table->integer('fk_multicurrency')->nullable();
            $table->string('multicurrency_code', 3)->nullable();
            $table->double('multicurrency_tx', 24, 8)->nullable();
            $table->double('multicurrency_total_ht', 24, 8)->nullable();
            $table->double('multicurrency_total_tva', 24, 8)->nullable();
            $table->double('multicurrency_total_ttc', 24, 8)->nullable();
            $table->string('import_key', 14)->nullable();
            $table->text('extraparams')->nullable();
            
            $table->unique('ref');
            $table->index(['entity', 'fk_statut']);
            $table->index('fk_soc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_commande_fournisseur');
    }
};
