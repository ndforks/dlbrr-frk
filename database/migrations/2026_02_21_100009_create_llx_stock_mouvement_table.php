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
        Schema::create('llx_stock_mouvement', function (Blueprint $table) {
            $table->id('rowid');
            $table->timestamp('tms')->nullable();
            $table->datetime('datem')->nullable();
            $table->unsignedBigInteger('fk_product');
            $table->string('batch', 128)->nullable();
            $table->date('eatby')->nullable();
            $table->date('sellby')->nullable();
            $table->unsignedBigInteger('fk_entrepot');
            $table->double('value', 24, 8);
            $table->double('price', 24, 8)->default(0);
            $table->smallInteger('type_mouvement')->nullable();
            $table->unsignedBigInteger('fk_user_author')->nullable();
            $table->string('label', 255)->nullable();
            $table->string('inventorycode', 128)->nullable();
            $table->unsignedBigInteger('fk_project')->nullable();
            $table->unsignedBigInteger('fk_origin')->nullable();
            $table->string('origintype', 32)->nullable();
            $table->string('model_pdf', 255)->nullable();
            $table->unsignedBigInteger('fk_projet')->nullable();
            
            $table->index(['fk_product', 'fk_entrepot']);
            $table->index('datem');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_stock_mouvement');
    }
};
