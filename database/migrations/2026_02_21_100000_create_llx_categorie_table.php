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
        Schema::create('llx_categorie', function (Blueprint $table) {
            $table->id('rowid');
            $table->integer('entity')->default(1);
            $table->unsignedBigInteger('fk_parent')->default(0);
            $table->string('label', 180)->nullable();
            $table->integer('type')->default(1)->comment('0=product, 1=supplier, 2=customer, 3=member, 4=contact, 5=account, 6=project');
            $table->text('description')->nullable();
            $table->string('color', 8)->nullable();
            $table->integer('fk_soc')->default(0);
            $table->tinyInteger('visible')->default(1);
            $table->string('import_key', 14)->nullable();
            
            $table->index(['entity', 'fk_parent', 'type']);
            $table->index('label');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_categorie');
    }
};
