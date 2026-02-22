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
        Schema::create('llx_ecm_directories', function (Blueprint $table) {
            $table->id('rowid');
            $table->string('label', 255)->nullable();
            $table->integer('entity')->default(1);
            $table->integer('fk_parent')->nullable();
            $table->text('description')->nullable();
            $table->integer('cachenbofdoc')->default(0);
            $table->string('fullrelativename', 750)->nullable();
            $table->text('extraparams')->nullable();
            $table->datetime('date_c')->nullable();
            $table->timestamp('tms')->nullable();
            $table->unsignedBigInteger('fk_user_c')->nullable();
            $table->unsignedBigInteger('fk_user_m')->nullable();
            $table->text('note_private')->nullable();
            $table->text('note_public')->nullable();
            $table->text('acl')->nullable();
            
            $table->index(['entity', 'fk_parent']);
            $table->index('fullrelativename');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_ecm_directories');
    }
};
