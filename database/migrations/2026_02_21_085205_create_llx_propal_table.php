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
        Schema::create('llx_propal', function (Blueprint $table) {
            $table->id('rowid');
            $table->string('ref', 30)->nullable();
            $table->integer('entity')->default(1);
            $table->string('ref_ext', 255)->nullable();
            $table->string('ref_client', 255)->nullable();
            $table->unsignedBigInteger('fk_soc')->nullable();
            $table->unsignedBigInteger('fk_projet')->nullable();
            $table->timestamp('tms')->nullable();
            $table->datetime('datec')->nullable();
            $table->date('datep')->nullable();
            $table->date('fin_validite')->nullable();
            $table->date('date_valid')->nullable();
            $table->date('date_signature')->nullable();
            $table->date('date_cloture')->nullable();
            $table->integer('fk_user_author')->nullable();
            $table->integer('fk_user_modif')->nullable();
            $table->integer('fk_user_valid')->nullable();
            $table->integer('fk_user_signature')->nullable();
            $table->integer('fk_user_close')->nullable();
            $table->smallInteger('fk_statut')->default(0);
            $table->double('price', 24, 8)->default(0);
            $table->double('remise_percent', 6, 3)->default(0);
            $table->double('remise_absolue', 24, 8)->default(0);
            $table->double('remise', 24, 8)->default(0);
            $table->double('total_ht', 24, 8)->default(0);
            $table->double('total_tva', 24, 8)->default(0);
            $table->double('localtax1', 24, 8)->default(0);
            $table->double('localtax2', 24, 8)->default(0);
            $table->double('total_ttc', 24, 8)->default(0);
            $table->integer('fk_account')->nullable();
            $table->string('fk_currency', 3)->nullable();
            $table->integer('fk_cond_reglement')->nullable();
            $table->integer('fk_mode_reglement')->nullable();
            $table->text('note_private')->nullable();
            $table->text('note_public')->nullable();
            $table->string('model_pdf', 255)->nullable();
            $table->date('date_livraison')->nullable();
            $table->integer('fk_shipping_method')->nullable();
            $table->string('import_key', 14)->nullable();
            $table->text('extraparams')->nullable();
            $table->integer('fk_multicurrency')->nullable();
            $table->string('multicurrency_code', 3)->nullable();
            $table->double('multicurrency_tx', 24, 8)->nullable();
            $table->double('multicurrency_total_ht', 24, 8)->nullable();
            $table->double('multicurrency_total_tva', 24, 8)->nullable();
            $table->double('multicurrency_total_ttc', 24, 8)->nullable();
            
            $table->unique('ref');
            $table->index(['entity', 'fk_statut']);
            $table->index('fk_soc');
            $table->index('fk_projet');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_propal');
    }
};
