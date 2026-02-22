<?php

namespace App\Http\Controllers\Cron;

use App\Http\Controllers\Controller;
use App\Modules\Cron\class\Cronjob;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CronInfoController extends Controller
{
    public function __invoke(Request $request): View
    {
        global $db, $langs, $user;

        $langs->loadLangs(['admin', 'cron']);

        // Security check
        if (!$user->hasRight('cron', 'read')) {
            abort(403);
        }

        $id = $request->integer('id', 0);
        $object = new Cronjob($db);
        
        $object->fetch($id);
        $object->info($id);

        return view('cron.info', [
            'object' => $object,
            'id' => $id,
        ]);
    }
}
