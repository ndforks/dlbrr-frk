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
        Schema::create('llx_bom_bom', function (Blueprint $table) {
            $table->id('rowid');
            $table->integer('entity')->default(1);
            $table->string('ref', 128)->nullable();
            $table->string('label', 255)->nullable();
            $table->unsignedBigInteger('fk_product');
            $table->text('description')->nullable();
            $table->text('note_public')->nullable();
            $table->text('note_private')->nullable();
            $table->unsignedBigInteger('fk_warehouse')->nullable();
            $table->double('qty', 24, 8)->default(1);
            $table->double('efficiency', 6, 3)->default(1);
            $table->datetime('date_creation')->nullable();
            $table->timestamp('tms')->nullable();
            $table->integer('fk_user_creat')->nullable();
            $table->integer('fk_user_modif')->nullable();
            $table->string('import_key', 14)->nullable();
            $table->string('model_pdf', 255)->nullable();
            $table->integer('status')->default(0);
            $table->unsignedBigInteger('fk_unit')->nullable();
            
            $table->unique('ref');
            $table->index(['entity', 'fk_product']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_bom_bom');
    }
};
