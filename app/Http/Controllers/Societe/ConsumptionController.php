<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ConsumptionController extends Controller
{
    public function __invoke(Request $request, int $id): View
    {
        $societe = Societe::findOrFail($id);
        
        // Consumption tracking would typically involve product/service usage data
        // For now, return a simple view
        return view('societe.consumption', [
            'societe' => $societe,
        ]);
    }
}
