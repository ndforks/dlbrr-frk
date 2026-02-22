<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DocumentsController extends Controller
{
    public function __invoke(Request $request, int $id): View
    {
        $societe = Societe::findOrFail($id);
        
        // Get document directory path
        $uploadDir = getDolGlobalString('SOCIETE_OUTPUTDIR') ?: 'societe';
        $documentPath = "{$uploadDir}/{$societe->id}";
        
        return view('societe.documents', [
            'societe' => $societe,
            'documentPath' => $documentPath,
        ]);
    }
}
