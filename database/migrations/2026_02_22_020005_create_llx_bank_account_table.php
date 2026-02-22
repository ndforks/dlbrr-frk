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
        Schema::create('llx_bank_account', function (Blueprint $table) {
            $table->id('rowid');
            $table->datetime('datec')->nullable();
            $table->timestamp('tms')->nullable();
            $table->string('ref', 12)->nullable();
            $table->string('label', 128)->nullable();
            $table->integer('entity')->default(1);
            $table->unsignedBigInteger('fk_user_author')->nullable();
            $table->unsignedBigInteger('fk_user_modif')->nullable();
            $table->string('bank', 60)->nullable();
            $table->string('code_banque', 128)->nullable();
            $table->string('code_guichet', 6)->nullable();
            $table->string('number', 255)->nullable();
            $table->string('cle_rib', 5)->nullable();
            $table->string('bic', 11)->nullable();
            $table->string('iban_prefix', 34)->nullable();
            $table->string('country_iban', 2)->nullable();
            $table->string('cle_iban', 2)->nullable();
            $table->string('domiciliation', 255)->nullable();
            $table->integer('state_id')->nullable();
            $table->integer('fk_pays')->default(0);
            $table->string('proprio', 255)->nullable();
            $table->text('owner_address')->nullable();
            $table->smallInteger('courant')->default(0);
            $table->smallInteger('clos')->default(0);
            $table->smallInteger('rappro')->default(1);
            $table->string('url', 128)->nullable();
            $table->string('account_number', 32)->nullable();
            $table->integer('fk_accountancy_journal')->nullable();
            $table->string('currency_code', 3)->nullable();
            $table->double('min_allowed', 24, 8)->default(0);
            $table->double('min_desired', 24, 8)->default(0);
            $table->text('comment')->nullable();
            $table->text('note_public')->nullable();
            $table->string('model_pdf', 255)->nullable();
            $table->string('import_key', 14)->nullable();
            $table->text('extraparams')->nullable();
            $table->unsignedBigInteger('fk_user_validator')->nullable();
            
            $table->unique('ref');
            $table->index('entity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_bank_account');
    }
};
