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
        Schema::create('llx_bookcal_availabilities', function (Blueprint $table) {
            $table->id('rowid');
            $table->unsignedBigInteger('fk_bookcal_calendar');
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->integer('duration')->nullable();
            $table->integer('status')->default(0);
            $table->integer('entity')->default(1);
            
            $table->index('fk_bookcal_calendar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_bookcal_availabilities');
    }
};
