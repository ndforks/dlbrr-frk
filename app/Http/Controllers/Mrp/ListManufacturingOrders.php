<?php

namespace App\Http\Controllers\Mrp;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class ListManufacturingOrders extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        return redirect('/htdocs/mrp/mo_list.php');
    }
}
