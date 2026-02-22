<?php

namespace App\Http\Controllers\Fourn\Commande;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class CommandeIndex extends Controller
{
    public function __invoke(): View
    {
        global $db, $user, $conf, $langs, $hookmanager;

        $langs->loadLangs(array("suppliers", "orders"));
        $hookmanager->initHooks(array('orderssuppliersindex'));

        $max = getDolGlobalInt('MAIN_SIZE_SHORTLIST_LIMIT', 5);
        $socid = $request->integer('socid', 0);

        if ($user->socid) {
            $socid = $user->socid;
        }

        restrictedArea($user, 'fournisseur', 0, '', 'commande');

        $commandestatic = new \CommandeFournisseur($db);
        $userstatic = new \User($db);
        $formfile = new \FormFile($db);

        $data = [
            'title' => $langs->trans("SuppliersOrdersArea"),
            'commandestatic' => $commandestatic,
            'userstatic' => $userstatic,
            'formfile' => $formfile,
            'socid' => $socid,
            'max' => $max,
        ];

        return view('fourn.commande.index', $data);
    }
}
