<?php

namespace App\Http\Controllers\Mrp;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class MrpIndex extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect('/mrp/list.php');
    }
}
