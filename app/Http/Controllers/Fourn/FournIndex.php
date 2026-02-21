<?php

namespace App\Http\Controllers\Fourn;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class FournIndex extends Controller
{
    public function __invoke(): View
    {
        global $db, $user, $conf, $langs;

        $langs->loadLangs(array("suppliers", "orders", "companies"));

        $socid = GETPOSTINT("socid");
        if ($user->socid) {
            $socid = $user->socid;
        }
        restrictedArea($user, 'societe', $socid, '');

        $commandestatic = new \CommandeFournisseur($db);
        $facturestatic = new \FactureFournisseur($db);
        $companystatic = new \Societe($db);

        $data = [
            'title' => $langs->trans("SuppliersArea"),
            'commandestatic' => $commandestatic,
            'facturestatic' => $facturestatic,
            'companystatic' => $companystatic,
            'socid' => $socid,
        ];

        return view('fourn.index', $data);
    }
}
