<?php

namespace App\Http\Controllers\Compta\Bank;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ListBank extends Controller
{
    public function __invoke(): View
    {
        global $db, $langs, $user, $conf, $hookmanager;
        
        require_once DOL_DOCUMENT_ROOT.'/compta/bank/class/account.class.php';
        
        $langs->loadLangs(['banks', 'categories', 'accountancy', 'compta']);
        $hookmanager->initHooks(['bankaccountlist']);
        restrictedArea($user, 'banque');
        
        return view('bank.list');
    }
}
