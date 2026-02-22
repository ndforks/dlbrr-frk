<?php

namespace App\Http\Controllers\Fourn\Facture;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class FactureIndex extends Controller
{
    public function __invoke(): View
    {
        global $db, $user, $conf, $langs;

        $langs->loadLangs(['bills', 'boxes']);

        $socid = $request->integer('socid', 0);
        if (!empty($user->socid) && $user->socid > 0) {
            $socid = $user->socid;
        }

        $max = getDolGlobalInt('MAIN_SIZE_SHORTLIST_LIMIT', 5);
        $maxDraftCount = getDolGlobalInt('MAIN_MAXLIST_OVERLOAD', 500);
        $maxLatestEditCount = 5;
        $maxOpenCount = getDolGlobalInt('MAIN_MAXLIST_OVERLOAD', 500);

        restrictedArea($user, 'fournisseur', 0, '', 'facture');

        $data = [
            'title' => $langs->trans("SupplierInvoicesArea"),
            'socid' => $socid,
            'max' => $max,
            'maxDraftCount' => $maxDraftCount,
            'maxLatestEditCount' => $maxLatestEditCount,
            'maxOpenCount' => $maxOpenCount,
        ];

        return view('fourn.facture.index', $data);
    }
}
