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
        Schema::create('llx_entrepot', function (Blueprint $table) {
            $table->id('rowid');
            $table->string('ref', 255)->nullable();
            $table->datetime('datec')->nullable();
            $table->timestamp('tms')->nullable();
            $table->integer('entity')->default(1);
            $table->integer('fk_parent')->default(0);
            $table->string('label', 255)->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('statut')->default(1);
            $table->string('lieu', 64)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('zip', 10)->nullable();
            $table->string('town', 50)->nullable();
            $table->integer('fk_departement')->nullable();
            $table->integer('fk_pays')->default(0);
            $table->string('phone', 20)->nullable();
            $table->string('fax', 20)->nullable();
            $table->integer('warehouse_usage')->nullable();
            $table->unsignedBigInteger('fk_project')->nullable();
            $table->string('model_pdf', 255)->nullable();
            $table->string('import_key', 14)->nullable();
            
            $table->unique('ref');
            $table->index('entity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_entrepot');
    }
};
