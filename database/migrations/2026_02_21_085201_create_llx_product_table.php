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
        Schema::create('llx_product', function (Blueprint $table) {
            $table->id('rowid');
            $table->string('ref', 128);
            $table->integer('entity')->default(1);
            $table->string('ref_ext', 128)->nullable();
            $table->datetime('datec')->nullable();
            $table->timestamp('tms')->useCurrent();
            $table->integer('fk_parent')->default(0);
            $table->string('label', 255);
            $table->text('description')->nullable();
            $table->text('note_public')->nullable();
            $table->text('note')->nullable();
            $table->string('customcode', 32)->nullable();
            $table->integer('fk_country')->nullable();
            $table->integer('fk_state')->nullable();
            $table->double('price')->default(0);
            $table->double('price_ttc')->nullable();
            $table->double('price_min')->nullable();
            $table->double('price_min_ttc')->nullable();
            $table->string('price_base_type', 3)->default('HT');
            $table->double('cost_price')->nullable();
            $table->string('default_vat_code', 10)->nullable();
            $table->double('tva_tx')->nullable();
            $table->double('recuperableonly')->default(0);
            $table->double('localtax1_tx')->default(0);
            $table->integer('localtax1_type')->default(0);
            $table->double('localtax2_tx')->default(0);
            $table->integer('localtax2_type')->default(0);
            $table->integer('fk_user_author')->nullable();
            $table->integer('fk_user_modif')->nullable();
            $table->tinyInteger('tosell')->default(1);
            $table->tinyInteger('tobuy')->default(1);
            $table->tinyInteger('onportal')->default(0);
            $table->tinyInteger('tobatch')->default(0);
            $table->tinyInteger('sell_or_eat_by_mandatory')->default(0);
            $table->string('batch_mask', 32)->nullable();
            $table->tinyInteger('fk_product_type')->default(0);
            $table->double('duration')->nullable();
            $table->string('duration_unit', 1)->nullable();
            $table->integer('seuil_stock_alerte')->nullable();
            $table->string('url', 255)->nullable();
            $table->string('barcode', 180)->nullable();
            $table->integer('fk_barcode_type')->nullable();
            $table->string('accountancy_code_sell', 32)->nullable();
            $table->string('accountancy_code_sell_intra', 32)->nullable();
            $table->string('accountancy_code_sell_export', 32)->nullable();
            $table->string('accountancy_code_buy', 32)->nullable();
            $table->string('accountancy_code_buy_intra', 32)->nullable();
            $table->string('accountancy_code_buy_export', 32)->nullable();
            $table->string('partnumber', 32)->nullable();
            $table->double('weight')->nullable();
            $table->integer('weight_units')->nullable();
            $table->double('length')->nullable();
            $table->integer('length_units')->nullable();
            $table->double('width')->nullable();
            $table->integer('width_units')->nullable();
            $table->double('height')->nullable();
            $table->integer('height_units')->nullable();
            $table->double('surface')->nullable();
            $table->integer('surface_units')->nullable();
            $table->double('volume')->nullable();
            $table->integer('volume_units')->nullable();
            $table->integer('net_measure')->nullable();
            $table->integer('net_measure_units')->nullable();
            $table->string('canvas', 32)->nullable();
            $table->tinyInteger('finished')->nullable();
            $table->tinyInteger('hidden')->default(0);
            $table->string('import_key', 14)->nullable();
            $table->string('model_pdf', 255)->nullable();
            $table->integer('fk_price_expression')->nullable();
            $table->double('desiredstock')->default(0);
            $table->integer('fk_unit')->nullable();
            $table->double('price_autogen')->default(0);
            $table->integer('fk_project')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_product');
    }
};
