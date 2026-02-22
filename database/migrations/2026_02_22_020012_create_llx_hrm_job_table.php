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
        Schema::create('llx_hrm_job', function (Blueprint $table) {
            $table->id('rowid');
            $table->integer('entity')->default(1);
            $table->string('ref', 128)->nullable();
            $table->string('label', 255)->nullable();
            $table->text('description')->nullable();
            $table->text('note_public')->nullable();
            $table->text('note_private')->nullable();
            $table->datetime('date_creation')->nullable();
            $table->timestamp('tms')->nullable();
            $table->unsignedBigInteger('fk_user_creat')->nullable();
            $table->unsignedBigInteger('fk_user_modif')->nullable();
            $table->string('import_key', 14)->nullable();
            $table->string('model_pdf', 255)->nullable();
            $table->integer('status')->default(0);
            
            $table->unique('ref');
            $table->index('entity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_hrm_job');
    }
};
