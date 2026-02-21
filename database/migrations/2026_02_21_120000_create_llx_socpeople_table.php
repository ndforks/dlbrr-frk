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
        Schema::create('llx_socpeople', function (Blueprint $table) {
            $table->id('rowid');
            $table->dateTime('datec')->nullable();
            $table->timestamp('tms')->nullable();
            $table->unsignedBigInteger('fk_soc')->nullable();
            $table->integer('entity')->default(1);
            $table->string('ref_ext', 255)->nullable();
            $table->string('firstname', 50)->nullable();
            $table->string('lastname', 50)->nullable();
            $table->string('poste', 255)->nullable();
            $table->text('address')->nullable();
            $table->string('zip', 25)->nullable();
            $table->string('town', 50)->nullable();
            $table->unsignedBigInteger('fk_pays')->nullable();
            $table->date('birthday')->nullable();
            $table->string('email', 255)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('phone_perso', 30)->nullable();
            $table->string('phone_mobile', 30)->nullable();
            $table->string('fax', 30)->nullable();
            $table->text('socialnetworks')->nullable();
            $table->string('photo', 255)->nullable();
            $table->tinyInteger('priv')->default(0);
            $table->tinyInteger('statut')->default(1);
            $table->text('note_public')->nullable();
            $table->text('note_private')->nullable();
            $table->unsignedBigInteger('fk_user_creat')->nullable();
            $table->unsignedBigInteger('fk_user_modif')->nullable();
            $table->unsignedBigInteger('fk_stcommcontact')->nullable();
            $table->string('fk_prospectlevel', 12)->nullable();
            $table->string('import_key', 14)->nullable();
            $table->string('ip', 250)->nullable();
            
            $table->index('fk_soc');
            $table->index('fk_user_creat');
            $table->index('entity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_socpeople');
    }
};
