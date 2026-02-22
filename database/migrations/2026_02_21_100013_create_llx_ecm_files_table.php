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
        Schema::create('llx_ecm_files', function (Blueprint $table) {
            $table->id('rowid');
            $table->string('ref', 128)->nullable();
            $table->string('label', 255)->nullable();
            $table->string('share', 64)->nullable();
            $table->string('share_pass', 32)->nullable();
            $table->integer('entity')->default(1);
            $table->string('filepath', 255)->nullable();
            $table->string('filename', 255)->nullable();
            $table->string('src_object_type', 64)->nullable();
            $table->unsignedBigInteger('src_object_id')->nullable();
            $table->string('fullpath_orig', 750)->nullable();
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();
            $table->text('cover')->nullable();
            $table->integer('position')->nullable();
            $table->string('gen_or_uploaded', 12)->default('unknown');
            $table->text('extraparams')->nullable();
            $table->datetime('date_c')->nullable();
            $table->timestamp('tms')->nullable();
            $table->unsignedBigInteger('fk_user_c')->nullable();
            $table->unsignedBigInteger('fk_user_m')->nullable();
            $table->text('note_private')->nullable();
            $table->text('note_public')->nullable();
            $table->text('acl')->nullable();
            
            $table->index(['filepath', 'filename', 'entity']);
            $table->index('src_object_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_ecm_files');
    }
};
