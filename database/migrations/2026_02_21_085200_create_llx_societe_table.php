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
        Schema::create('llx_societe', function (Blueprint $table) {
            $table->id('rowid');
            $table->string('nom', 128)->nullable();
            $table->string('name_alias', 128)->nullable();
            $table->integer('entity')->default(1);
            $table->string('ref_ext', 255)->nullable();
            $table->string('ref_int', 255)->nullable();
            $table->tinyInteger('statut')->default(0);
            $table->integer('parent')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->string('code_client', 128)->nullable();
            $table->string('code_fournisseur', 128)->nullable();
            $table->string('code_compta', 32)->nullable();
            $table->string('code_compta_fournisseur', 32)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('zip', 25)->nullable();
            $table->string('town', 50)->nullable();
            $table->integer('fk_departement')->default(0);
            $table->integer('fk_pays')->default(0);
            $table->string('phone', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->string('url', 255)->nullable();
            $table->string('email', 128)->nullable();
            $table->string('socialnetworks', 255)->nullable();
            $table->integer('fk_effectif')->default(0);
            $table->integer('fk_typent')->nullable();
            $table->integer('fk_forme_juridique')->default(0);
            $table->string('siren', 128)->nullable();
            $table->string('siret', 128)->nullable();
            $table->string('ape', 128)->nullable();
            $table->string('idprof4', 128)->nullable();
            $table->string('idprof5', 128)->nullable();
            $table->string('idprof6', 128)->nullable();
            $table->string('tva_intra', 20)->nullable();
            $table->double('capital')->nullable();
            $table->tinyInteger('tva_assuj')->default(1);
            $table->integer('fk_stcomm')->default(0);
            $table->text('note_private')->nullable();
            $table->text('note_public')->nullable();
            $table->string('model_pdf', 255)->nullable();
            $table->string('prefix_comm', 5)->nullable();
            $table->tinyInteger('client')->default(0);
            $table->tinyInteger('fournisseur')->default(0);
            $table->string('supplier_account', 128)->nullable();
            $table->integer('fk_prospectlevel')->nullable();
            $table->integer('fk_incoterms')->nullable();
            $table->string('location_incoterms', 255)->nullable();
            $table->tinyInteger('customer_bad')->default(0);
            $table->double('customer_rate')->default(0);
            $table->double('supplier_rate')->default(0);
            $table->double('remise_client')->default(0);
            $table->double('remise_supplier')->default(0);
            $table->tinyInteger('mode_reglement')->nullable();
            $table->tinyInteger('cond_reglement')->nullable();
            $table->tinyInteger('mode_reglement_supplier')->nullable();
            $table->tinyInteger('cond_reglement_supplier')->nullable();
            $table->string('barcode', 180)->nullable();
            $table->integer('fk_barcode_type')->default(0);
            $table->double('outstanding_limit')->nullable();
            $table->string('default_lang', 6)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('canvas', 32)->nullable();
            $table->datetime('datec')->nullable();
            $table->timestamp('tms')->useCurrent();
            $table->integer('fk_user_creat')->nullable();
            $table->integer('fk_user_modif')->nullable();
            $table->string('import_key', 14)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_societe');
    }
};
