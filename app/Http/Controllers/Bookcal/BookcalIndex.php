<?php

namespace App\Http\Controllers\Bookcal;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class BookcalIndex extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        global $db, $langs, $user, $conf;
        
        $langs->loadLangs(array("agenda"));
        
        $socid = $request->integer('socid', 0);
        if (!empty($user->socid) && $user->socid > 0) {
            $socid = $user->socid;
        }
        
        $now = dol_now();
        $max = getDolGlobalInt('MAIN_SIZE_SHORTLIST_LIMIT', 5);
        
        $form = new \Form($db);
        $formfile = new \FormFile($db);
        
        return view('bookcal.index', [
            'langs' => $langs,
            'user' => $user,
            'conf' => $conf,
            'form' => $form,
            'formfile' => $formfile,
            'socid' => $socid,
            'max' => $max,
        ]);
    }
}
