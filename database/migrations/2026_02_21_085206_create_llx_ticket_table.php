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
        Schema::create('llx_ticket', function (Blueprint $table) {
            $table->id('rowid');
            $table->integer('entity')->default(1);
            $table->string('ref', 128)->nullable();
            $table->string('track_id', 128)->nullable();
            $table->unsignedBigInteger('fk_soc')->nullable();
            $table->unsignedBigInteger('fk_project')->nullable();
            $table->string('origin_email', 128)->nullable();
            $table->unsignedBigInteger('fk_user_create')->nullable();
            $table->unsignedBigInteger('fk_user_assign')->nullable();
            $table->string('subject', 255)->nullable();
            $table->text('message')->nullable();
            $table->integer('fk_statut')->nullable();
            $table->string('resolution', 32)->nullable();
            $table->double('progress', 5, 2)->default(0);
            $table->string('timing', 20)->nullable();
            $table->integer('type_code')->nullable();
            $table->integer('category_code')->nullable();
            $table->integer('severity_code')->nullable();
            $table->datetime('datec')->nullable();
            $table->datetime('date_read')->nullable();
            $table->datetime('date_last_msg_sent')->nullable();
            $table->datetime('date_close')->nullable();
            $table->timestamp('tms')->nullable();
            $table->text('note_private')->nullable();
            $table->text('note_public')->nullable();
            $table->string('model_pdf', 255)->nullable();
            $table->string('import_key', 14)->nullable();
            $table->text('extraparams')->nullable();
            
            $table->unique('ref');
            $table->unique('track_id');
            $table->index(['entity', 'fk_statut']);
            $table->index('fk_soc');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_ticket');
    }
};
