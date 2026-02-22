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
        Schema::create('llx_don', function (Blueprint $table) {
            $table->id('rowid');
            $table->string('ref', 30)->nullable();
            $table->integer('entity')->default(1);
            $table->timestamp('tms')->nullable();
            $table->datetime('datec')->nullable();
            $table->datetime('datedon')->nullable();
            $table->double('amount', 24, 8)->default(0);
            $table->unsignedBigInteger('fk_soc')->nullable();
            $table->unsignedBigInteger('fk_projet')->nullable();
            $table->integer('fk_user_author')->nullable();
            $table->integer('fk_user_modif')->nullable();
            $table->smallInteger('fk_statut')->default(0);
            $table->string('firstname', 50)->nullable();
            $table->string('lastname', 50)->nullable();
            $table->string('societe', 50)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('zip', 30)->nullable();
            $table->string('town', 50)->nullable();
            $table->integer('country')->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('phone_mobile', 30)->nullable();
            $table->tinyInteger('public')->default(1);
            $table->integer('fk_payment')->nullable();
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
        Schema::dropIfExists('llx_don');
    }
};
