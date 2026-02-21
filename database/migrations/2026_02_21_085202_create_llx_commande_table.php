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
        Schema::create('llx_commande', function (Blueprint $table) {
            $table->integer('rowid')->autoIncrement()->primary();
            $table->string('ref', 30);
            $table->integer('entity')->default(1);
            $table->string('ref_ext', 255)->nullable();
            $table->string('ref_client', 255)->nullable();
            $table->integer('fk_soc')->nullable();
            $table->integer('fk_projet')->nullable();
            $table->datetime('date_creation')->nullable();
            $table->datetime('date_valid')->nullable();
            $table->datetime('date_cloture')->nullable();
            $table->date('date_commande')->nullable();
            $table->integer('fk_user_author')->nullable();
            $table->integer('fk_user_modif')->nullable();
            $table->integer('fk_user_valid')->nullable();
            $table->integer('fk_user_cloture')->nullable();
            $table->integer('source')->nullable();
            $table->smallInteger('billed')->nullable();
            $table->timestamp('tms')->useCurrent();
            $table->double('total_ht')->default(0);
            $table->double('total_tva')->default(0);
            $table->double('localtax1')->default(0);
            $table->double('localtax2')->default(0);
            $table->double('total_ttc')->default(0);
            $table->string('note_private', 65000)->nullable();
            $table->string('note_public', 65000)->nullable();
            $table->string('model_pdf', 255)->nullable();
            $table->string('last_main_doc', 255)->nullable();
            $table->integer('fk_account')->nullable();
            $table->integer('fk_currency')->nullable();
            $table->integer('fk_cond_reglement')->nullable();
            $table->integer('deposit_percent')->nullable();
            $table->integer('fk_mode_reglement')->nullable();
            $table->date('date_livraison')->nullable();
            $table->integer('fk_shipping_method')->nullable();
            $table->integer('fk_warehouse')->nullable();
            $table->integer('fk_availability')->nullable();
            $table->integer('fk_input_reason')->nullable();
            $table->string('import_key', 14)->nullable();
            $table->text('extraparams')->nullable();
            $table->smallInteger('fk_incoterms')->nullable();
            $table->string('location_incoterms', 255)->nullable();
            $table->integer('fk_multicurrency')->nullable();
            $table->string('multicurrency_code', 3)->nullable();
            $table->double('multicurrency_tx')->nullable();
            $table->double('multicurrency_total_ht')->nullable();
            $table->double('multicurrency_total_tva')->nullable();
            $table->double('multicurrency_total_ttc')->nullable();
            $table->string('module_source', 32)->nullable();
            $table->string('pos_source', 32)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_commande');
    }
};
