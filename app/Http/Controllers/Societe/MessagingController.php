<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessagingController extends Controller
{
    public function __invoke(Request $request, int $id): View
    {
        $societe = Societe::findOrFail($id);
        
        return view('societe.messaging', [
            'societe' => $societe,
        ]);
    }
}
