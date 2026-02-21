<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ContactIndex extends Controller
{
    /**
     * Handle the incoming request.
     * Since there's no index.php for contacts, redirect to the list
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/contact/list.php');
    }
}
