<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ManagesNotes;
use App\Models\Societe;
use Illuminate\Database\Eloquent\Model;

class NotesController extends Controller
{
    use ManagesNotes;
    
    protected function getModel(int $id): Model
    {
        return Societe::findOrFail($id);
    }
    
    protected function getViewName(): string
    {
        return 'societe.notes';
    }
    
    protected function getRouteName(): string
    {
        return 'societe.notes';
    }
}
