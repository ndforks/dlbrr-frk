<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table = 'llx_bank_account';
    
    protected $primaryKey = 'rowid';
    
    public $timestamps = false;
    
    protected $fillable = [
        'datec',
        'tms',
        'ref',
        'label',
        'entity',
        'fk_user_author',
        'fk_user_modif',
        'bank',
        'code_banque',
        'code_guichet',
        'number',
        'cle_rib',
        'bic',
        'iban_prefix',
        'country_iban',
        'cle_iban',
        'domiciliation',
        'state_id',
        'fk_pays',
        'proprio',
        'owner_address',
        'courant',
        'clos',
        'rappro',
        'url',
        'account_number',
        'fk_accountancy_journal',
        'currency_code',
        'min_allowed',
        'min_desired',
        'comment',
        'note_public',
        'model_pdf',
        'import_key',
        'extraparams',
        'fk_user_validator',
    ];
    
    protected $casts = [
        'datec' => 'datetime',
        'tms' => 'datetime',
        'entity' => 'integer',
        'fk_user_author' => 'integer',
        'fk_user_modif' => 'integer',
        'fk_pays' => 'integer',
        'courant' => 'integer',
        'clos' => 'integer',
        'rappro' => 'integer',
        'min_allowed' => 'float',
        'min_desired' => 'float',
    ];
    
    /**
     * Get the user who created the account
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'fk_user_author', 'rowid');
    }
}
