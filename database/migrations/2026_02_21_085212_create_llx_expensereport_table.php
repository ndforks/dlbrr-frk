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
        Schema::create('llx_expensereport', function (Blueprint $table) {
            $table->id('rowid');
            $table->string('ref', 50)->nullable();
            $table->integer('entity')->default(1);
            $table->string('ref_ext', 50)->nullable();
            $table->integer('ref_number_int')->nullable();
            $table->integer('fk_user_author');
            $table->integer('fk_user_modif')->nullable();
            $table->integer('fk_user_valid')->nullable();
            $table->integer('fk_user_approve')->nullable();
            $table->integer('fk_user_cancel')->nullable();
            $table->integer('fk_statut')->default(0);
            $table->integer('fk_c_paiement')->nullable();
            $table->tinyInteger('paid')->default(0);
            $table->datetime('date_create')->nullable();
            $table->datetime('date_valid')->nullable();
            $table->datetime('date_approve')->nullable();
            $table->datetime('date_refuse')->nullable();
            $table->datetime('date_cancel')->nullable();
            $table->timestamp('tms')->nullable();
            $table->date('date_debut');
            $table->date('date_fin');
            $table->double('total_ht', 24, 8)->default(0);
            $table->double('total_tva', 24, 8)->default(0);
            $table->double('localtax1', 24, 8)->default(0);
            $table->double('localtax2', 24, 8)->default(0);
            $table->double('total_ttc', 24, 8)->default(0);
            $table->text('note_private')->nullable();
            $table->text('note_public')->nullable();
            $table->string('detail_cancel', 255)->nullable();
            $table->string('detail_refuse', 255)->nullable();
            $table->integer('fk_user_validator')->nullable();
            $table->string('import_key', 14)->nullable();
            $table->string('model_pdf', 255)->nullable();
            $table->text('extraparams')->nullable();
            
            $table->unique('ref');
            $table->index(['entity', 'fk_statut']);
            $table->index('fk_user_author');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_expensereport');
    }
};
