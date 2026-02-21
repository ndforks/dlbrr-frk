<?php

namespace App\Http\Controllers\Accountancy;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class JournalAccountancy extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/accountancy/journal/index.php');
    }
}
