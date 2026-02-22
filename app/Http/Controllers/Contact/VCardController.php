<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Response;

class VCardController extends Controller
{
    public function __invoke(int $id): Response
    {
        $contact = Contact::with('societe')->findOrFail($id);
        
        // Generate VCard content
        $vcard = $this->generateVCard($contact);
        
        $filename = trim("{$contact->firstname} {$contact->lastname}");
        $filename = $filename ?: "contact-{$contact->id}";
        
        return response($vcard, 200)
            ->header('Content-Type', 'text/vcard')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}.vcf\"");
    }
    
    private function generateVCard(Contact $contact): string
    {
        $vcard = "BEGIN:VCARD\r\n";
        $vcard .= "VERSION:3.0\r\n";
        $vcard .= "FN:{$contact->firstname} {$contact->lastname}\r\n";
        $vcard .= "N:{$contact->lastname};{$contact->firstname};;;\r\n";
        
        if ($contact->societe) {
            $vcard .= "ORG:{$contact->societe->nom}\r\n";
        }
        
        if ($contact->poste) {
            $vcard .= "TITLE:{$contact->poste}\r\n";
        }
        
        if ($contact->address) {
            $vcard .= "ADR:;;{$contact->address};{$contact->town};;{$contact->zip};{$contact->country}\r\n";
        }
        
        if ($contact->phone) {
            $vcard .= "TEL;TYPE=WORK,VOICE:{$contact->phone}\r\n";
        }
        
        if ($contact->phone_mobile) {
            $vcard .= "TEL;TYPE=CELL:{$contact->phone_mobile}\r\n";
        }
        
        if ($contact->email) {
            $vcard .= "EMAIL;TYPE=INTERNET:{$contact->email}\r\n";
        }
        
        $vcard .= "END:VCARD\r\n";
        
        return $vcard;
    }
}
