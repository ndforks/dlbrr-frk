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
        Schema::create('llx_asset', function (Blueprint $table) {
            $table->id('rowid');
            $table->string('ref', 128)->nullable();
            $table->integer('entity')->default(1);
            $table->string('label', 255)->nullable();
            $table->double('amount_ht', 24, 8)->nullable();
            $table->double('amount_vat', 24, 8)->nullable();
            $table->unsignedBigInteger('fk_asset_type')->nullable();
            $table->text('description')->nullable();
            $table->text('note_public')->nullable();
            $table->text('note_private')->nullable();
            $table->date('date_creation')->nullable();
            $table->timestamp('tms')->nullable();
            $table->integer('fk_user_creat')->nullable();
            $table->integer('fk_user_modif')->nullable();
            $table->string('import_key', 14)->nullable();
            $table->integer('status')->default(0);
            $table->date('date_acquisition')->nullable();
            $table->date('date_start')->nullable();
            $table->integer('acquisition_type')->nullable();
            $table->string('acquisition_type_label', 255)->nullable();
            $table->date('date_disposal')->nullable();
            $table->double('amount_ht_disposal', 24, 8)->nullable();
            $table->integer('disposal_type')->nullable();
            $table->string('disposal_type_label', 255)->nullable();
            $table->unsignedBigInteger('fk_disposal_asset')->nullable();
            $table->integer('disposal_depreciated')->nullable();
            $table->integer('disposal_subject_to_vat')->nullable();
            $table->string('model_pdf', 255)->nullable();
            $table->integer('last_main_doc')->nullable();
            $table->unsignedBigInteger('fk_asset_model')->nullable();
            $table->double('reversal_amount_ht', 24, 8)->nullable();
            $table->date('reversal_date')->nullable();
            $table->unsignedBigInteger('fk_asset_account_depreciation')->nullable();
            $table->unsignedBigInteger('fk_asset_accountancy_codes_depreciation_asset')->nullable();
            $table->unsignedBigInteger('fk_asset_accountancy_codes_depreciation_expense')->nullable();
            $table->text('extraparams')->nullable();
            
            $table->unique('ref');
            $table->index('entity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('llx_asset');
    }
};
