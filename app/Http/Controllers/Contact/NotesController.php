<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Concerns\ManagesNotes;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Model;

class NotesController extends Controller
{
    use ManagesNotes;
    
    protected function getModel(int $id): Model
    {
        return Contact::findOrFail($id);
    }
    
    protected function getViewName(): string
    {
        return 'contact.notes';
    }
    
    protected function getRouteName(): string
    {
        return 'contact.notes';
    }
}
