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
        Schema::create('llx_product_attribute_value', function (Blueprint $table) {
            $table->id('rowid');
            $table->unsignedBigInteger('fk_product_attribute');
            $table->string('ref', 180)->nullable();
            $table->string('value', 255)->nullable();
            $table->integer('position')->default(0);
            $table->integer('entity')->default(1);
            
            $table->index(['fk_product_attribute', 'entity']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_product_attribute_value');
    }
};
