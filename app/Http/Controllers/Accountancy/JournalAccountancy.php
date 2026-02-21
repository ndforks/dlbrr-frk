<?php

namespace App\Http\Controllers\Accountancy;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class JournalAccountancy extends Controller
{
    public function __invoke(Request $request): View
    {
        global $db, $langs, $user, $conf, $hookmanager;
        
        $langs->loadLangs(array("compta", "accountancy", "banks"));
        
        $hookmanager->initHooks(array('journalindex'));
        
        if ($user->socid > 0) {
            accessforbidden();
        }
        if (!isModEnabled('accounting')) {
            accessforbidden();
        }
        if (!$user->hasRight('accounting', 'mouvements', 'lire')) {
            accessforbidden();
        }
        
        return view('accountancy.journal.index', [
            'journals' => [
                ['name' => 'SellsJournal', 'url' => '/accountancy/journal/sellsjournal.php'],
                ['name' => 'PurchasesJournal', 'url' => '/accountancy/journal/purchasesjournal.php'],
                ['name' => 'BankJournal', 'url' => '/accountancy/journal/bankjournal.php'],
                ['name' => 'TreasuryJournal', 'url' => '/accountancy/journal/treasuryjournal.php'],
                ['name' => 'ExpenseReportsJournal', 'url' => '/accountancy/journal/expensereportsjournal.php'],
                ['name' => 'VariousJournal', 'url' => '/accountancy/journal/variousjournal.php'],
            ],
        ]);
    }
}
