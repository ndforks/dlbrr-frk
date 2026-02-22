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
        Schema::create('llx_expedition', function (Blueprint $table) {
            $table->id('rowid');
            $table->timestamp('tms')->nullable();
            $table->string('ref', 30)->nullable();
            $table->integer('entity')->default(1);
            $table->unsignedBigInteger('fk_soc');
            $table->unsignedBigInteger('fk_projet')->nullable();
            $table->string('ref_ext', 255)->nullable();
            $table->string('ref_customer', 255)->nullable();
            $table->datetime('date_creation')->nullable();
            $table->integer('fk_user_author')->nullable();
            $table->integer('fk_user_modif')->nullable();
            $table->date('date_valid')->nullable();
            $table->integer('fk_user_valid')->nullable();
            $table->date('date_delivery')->nullable();
            $table->date('date_expedition')->nullable();
            $table->integer('fk_address')->nullable();
            $table->smallInteger('fk_shipping_method')->nullable();
            $table->string('tracking_number', 50)->nullable();
            $table->string('tracking_url', 255)->nullable();
            $table->integer('fk_statut')->default(0);
            $table->tinyInteger('billed')->default(0);
            $table->double('height', 10, 3)->nullable();
            $table->double('width', 10, 3)->nullable();
            $table->integer('size_units')->nullable();
            $table->double('size', 10, 3)->nullable();
            $table->double('weight_units', 10, 3)->nullable();
            $table->double('weight', 10, 3)->nullable();
            $table->text('note_private')->nullable();
            $table->text('note_public')->nullable();
            $table->string('model_pdf', 255)->nullable();
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
        Schema::dropIfExists('llx_expedition');
    }
};
