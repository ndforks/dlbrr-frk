<?php

namespace App\Http\Controllers\Compta\Bank;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\View\View;

class ListBank extends Controller
{
    public function __invoke(): View
    {
        global $db, $langs, $user, $conf, $hookmanager;
        
        $langs->loadLangs(['banks', 'categories', 'accountancy', 'compta']);
        $hookmanager->initHooks(['bankaccountlist']);
        restrictedArea($user, 'banque');
        
        // Fetch all bank accounts
        $accounts = BankAccount::where('entity', '=', $conf->entity)
            ->orderBy('ref', 'asc')
            ->get();
        
        return view('bank.list', [
            'accounts' => $accounts,
            'langs' => $langs,
            'user' => $user,
        ]);
    }
}
