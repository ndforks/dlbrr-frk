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
        Schema::create('llx_fichinter', function (Blueprint $table) {
            $table->id('rowid');
            $table->integer('fk_soc');
            $table->unsignedBigInteger('fk_projet')->nullable();
            $table->unsignedBigInteger('fk_contrat')->nullable();
            $table->string('ref', 30)->nullable();
            $table->integer('entity')->default(1);
            $table->timestamp('tms')->nullable();
            $table->datetime('datec')->nullable();
            $table->date('date_valid')->nullable();
            $table->datetime('datei')->nullable();
            $table->datetime('dateo')->nullable();
            $table->datetime('datee')->nullable();
            $table->integer('fk_user_author')->nullable();
            $table->integer('fk_user_modif')->nullable();
            $table->integer('fk_user_valid')->nullable();
            $table->smallInteger('fk_statut')->default(0);
            $table->double('duree', 24, 8)->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('llx_fichinter');
    }
};
