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
        Schema::create('llx_product_attribute', function (Blueprint $table) {
            $table->id('rowid');
            $table->string('ref', 255)->nullable();
            $table->string('ref_ext', 255)->nullable();
            $table->string('label', 255)->nullable();
            $table->integer('position')->default(0);
            $table->integer('entity')->default(1);
            
            $table->index('entity');
            $table->index('ref');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_product_attribute');
    }
};
