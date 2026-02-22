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
        Schema::create('llx_adherent', function (Blueprint $table) {
            $table->id('rowid');
            $table->integer('entity')->default(1);
            $table->string('ref_ext', 128)->nullable();
            $table->tinyInteger('civility')->nullable();
            $table->string('lastname', 50)->nullable();
            $table->string('firstname', 50)->nullable();
            $table->string('login', 50)->nullable();
            $table->string('pass', 50)->nullable();
            $table->string('pass_crypted', 128)->nullable();
            $table->integer('fk_adherent_type');
            $table->string('morphy', 3)->nullable();
            $table->unsignedBigInteger('fk_soc')->nullable();
            $table->integer('statut')->default(0);
            $table->tinyInteger('public')->default(0);
            $table->string('address', 255)->nullable();
            $table->string('zip', 30)->nullable();
            $table->string('town', 50)->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('country')->nullable();
            $table->string('email', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->string('socialnetworks', 255)->nullable();
            $table->date('birth')->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('phone_perso', 30)->nullable();
            $table->string('phone_mobile', 30)->nullable();
            $table->string('fax', 30)->nullable();
            $table->datetime('first_subscription_date')->nullable();
            $table->date('first_subscription_date_start')->nullable();
            $table->date('first_subscription_date_end')->nullable();
            $table->double('first_subscription_amount', 24, 8)->nullable();
            $table->datetime('last_subscription_date')->nullable();
            $table->date('last_subscription_date_start')->nullable();
            $table->date('last_subscription_date_end')->nullable();
            $table->double('last_subscription_amount', 24, 8)->nullable();
            $table->text('note_private')->nullable();
            $table->text('note_public')->nullable();
            $table->datetime('datevalid')->nullable();
            $table->datetime('datec')->nullable();
            $table->timestamp('tms')->nullable();
            $table->integer('fk_user_author')->nullable();
            $table->integer('fk_user_mod')->nullable();
            $table->integer('fk_user_valid')->nullable();
            $table->string('canvas', 32)->nullable();
            $table->string('import_key', 14)->nullable();
            $table->string('model_pdf', 255)->nullable();
            
            $table->index(['entity', 'statut']);
            $table->index('fk_soc');
            $table->index('fk_adherent_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_adherent');
    }
};
