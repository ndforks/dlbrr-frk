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
        Schema::create('llx_website', function (Blueprint $table) {
            $table->id('rowid');
            $table->integer('entity')->default(1);
            $table->string('ref', 128)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('lang', 8)->nullable();
            $table->text('otherlang')->nullable();
            $table->integer('status')->default(1);
            $table->unsignedBigInteger('fk_default_home')->nullable();
            $table->string('virtualhost', 255)->nullable();
            $table->unsignedBigInteger('fk_user_creat')->nullable();
            $table->unsignedBigInteger('fk_user_modif')->nullable();
            $table->datetime('date_creation')->nullable();
            $table->timestamp('tms')->nullable();
            $table->string('import_key', 14)->nullable();
            $table->integer('position')->default(0);
            
            $table->unique('ref');
            $table->index('entity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_website');
    }
};
