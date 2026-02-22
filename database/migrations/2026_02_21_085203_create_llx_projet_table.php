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
        Schema::create('llx_projet', function (Blueprint $table) {
            $table->id('rowid');
            $table->unsignedBigInteger('fk_soc')->nullable();
            $table->datetime('datec')->nullable();
            $table->timestamp('tms')->nullable();
            $table->date('dateo')->nullable()->comment('Start date');
            $table->date('datee')->nullable()->comment('End date');
            $table->string('ref', 50)->nullable();
            $table->integer('entity')->default(1);
            $table->string('title', 255)->nullable();
            $table->text('description')->nullable();
            $table->integer('fk_user_creat')->nullable();
            $table->integer('fk_user_modif')->nullable();
            $table->tinyInteger('public')->default(0);
            $table->integer('fk_statut')->default(0);
            $table->unsignedBigInteger('fk_opp_status')->nullable();
            $table->double('opp_percent', 5, 2)->nullable();
            $table->double('opp_amount', 24, 8)->nullable();
            $table->double('budget_amount', 24, 8)->nullable();
            $table->double('usage_bill_time', 24, 8)->default(0);
            $table->string('model_pdf', 255)->nullable();
            $table->text('note_public')->nullable();
            $table->text('note_private')->nullable();
            $table->date('date_close')->nullable();
            $table->integer('fk_user_close')->nullable();
            $table->string('import_key', 14)->nullable();
            
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
        Schema::dropIfExists('llx_projet');
    }
};
