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
        Schema::create('llx_holiday', function (Blueprint $table) {
            $table->id('rowid');
            $table->string('ref', 30)->nullable();
            $table->integer('entity')->default(1);
            $table->integer('fk_user');
            $table->integer('fk_user_create')->nullable();
            $table->integer('fk_user_modif')->nullable();
            $table->integer('fk_user_valid')->nullable();
            $table->integer('fk_user_cancel')->nullable();
            $table->integer('fk_type');
            $table->datetime('date_create')->nullable();
            $table->timestamp('tms')->nullable();
            $table->date('date_debut');
            $table->double('date_debut_gmt', 24, 8)->nullable();
            $table->date('date_fin');
            $table->double('date_fin_gmt', 24, 8)->nullable();
            $table->tinyInteger('halfday')->nullable();
            $table->integer('statut')->default(1);
            $table->double('fk_validator', 24, 8);
            $table->datetime('date_valid')->nullable();
            $table->datetime('date_approval')->nullable();
            $table->datetime('date_cancel')->nullable();
            $table->datetime('date_refuse')->nullable();
            $table->integer('fk_user_refuse')->nullable();
            $table->text('detail_refuse')->nullable();
            $table->text('note_private')->nullable();
            $table->text('note_public')->nullable();
            $table->string('import_key', 14)->nullable();
            $table->text('extraparams')->nullable();
            
            $table->unique('ref');
            $table->index(['entity', 'statut']);
            $table->index('fk_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_holiday');
    }
};
