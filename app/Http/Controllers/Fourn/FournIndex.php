<?php

namespace App\Http\Controllers\Fourn;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class FournIndex extends Controller
{
    public function __invoke(Request $request): View
    {
        global $db, $user, $conf, $langs;

        $langs->loadLangs(array("suppliers", "orders", "companies"));

        $socid = $request->integer("socid", 0);
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
