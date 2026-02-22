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
        Schema::create('llx_loan', function (Blueprint $table) {
            $table->id('rowid');
            $table->integer('entity')->default(1);
            $table->datetime('datec')->nullable();
            $table->timestamp('tms')->nullable();
            $table->string('label', 80)->nullable();
            $table->unsignedBigInteger('fk_bank')->nullable();
            $table->double('capital', 24, 8)->default(0);
            $table->date('datestart')->nullable();
            $table->date('dateend')->nullable();
            $table->integer('nbterm')->nullable();
            $table->double('rate', 8, 4)->nullable();
            $table->text('note_private')->nullable();
            $table->text('note_public')->nullable();
            $table->double('capital_position', 24, 8)->default(0);
            $table->integer('fk_user_author')->nullable();
            $table->integer('fk_user_modif')->nullable();
            $table->integer('fk_projet')->nullable();
            $table->integer('fk_account_capital')->nullable();
            $table->integer('fk_account_interest')->nullable();
            $table->integer('fk_account_insurance')->nullable();
            $table->integer('paid')->default(0);
            $table->integer('accountancy_account_capital')->nullable();
            $table->integer('accountancy_account_interest')->nullable();
            $table->integer('accountancy_account_insurance')->nullable();
            
            $table->index('entity');
            $table->index('fk_bank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_loan');
    }
};
