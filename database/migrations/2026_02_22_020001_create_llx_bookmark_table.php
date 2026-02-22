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
        Schema::create('llx_bookmark', function (Blueprint $table) {
            $table->id('rowid');
            $table->integer('entity')->default(1);
            $table->unsignedBigInteger('fk_user')->default(0)->comment('User ID or 0 for public bookmark');
            $table->datetime('dateb')->nullable();
            $table->text('url')->nullable();
            $table->string('target', 16)->default('_self');
            $table->string('title', 64)->nullable();
            $table->text('favicon')->nullable();
            $table->integer('position')->default(0);
            
            $table->index(['entity', 'fk_user']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_bookmark');
    }
};
