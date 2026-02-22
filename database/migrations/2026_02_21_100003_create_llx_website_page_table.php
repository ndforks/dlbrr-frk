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
        Schema::create('llx_website_page', function (Blueprint $table) {
            $table->id('rowid');
            $table->unsignedBigInteger('fk_website');
            $table->string('pageurl', 255)->nullable();
            $table->string('aliasalt', 255)->nullable();
            $table->string('title', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('keywords', 255)->nullable();
            $table->string('lang', 8)->nullable();
            $table->unsignedBigInteger('fk_page')->nullable();
            $table->integer('allowed_in_frames')->default(0);
            $table->text('htmlheader')->nullable();
            $table->longText('content')->nullable();
            $table->integer('status')->default(1);
            $table->string('grabbed_from', 255)->nullable();
            $table->string('type_container', 16)->default('page');
            $table->unsignedBigInteger('fk_user_creat')->nullable();
            $table->unsignedBigInteger('fk_user_modif')->nullable();
            $table->string('author_alias', 64)->nullable();
            $table->datetime('date_creation')->nullable();
            $table->timestamp('tms')->nullable();
            $table->string('import_key', 14)->nullable();
            
            $table->index(['fk_website', 'status']);
            $table->index('pageurl');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_website_page');
    }
};
