<?php

namespace App\Http\Controllers\Societe;

use App\Http\Controllers\Controller;
use App\Models\Societe;
use Illuminate\Http\Response;

class VCardController extends Controller
{
    public function __invoke(int $id): Response
    {
        $societe = Societe::findOrFail($id);
        
        // Generate VCard content
        $vcard = $this->generateVCard($societe);
        
        return response($vcard, 200)
            ->header('Content-Type', 'text/vcard')
            ->header('Content-Disposition', "attachment; filename=\"{$societe->nom}.vcf\"");
    }
    
    private function generateVCard(Societe $societe): string
    {
        $vcard = "BEGIN:VCARD\r\n";
        $vcard .= "VERSION:3.0\r\n";
        $vcard .= "FN:{$societe->nom}\r\n";
        $vcard .= "ORG:{$societe->nom}\r\n";
        
        if ($societe->address) {
            $vcard .= "ADR:;;{$societe->address};{$societe->town};;{$societe->zip};{$societe->country}\r\n";
        }
        
        if ($societe->phone) {
            $vcard .= "TEL;TYPE=WORK,VOICE:{$societe->phone}\r\n";
        }
        
        if ($societe->email) {
            $vcard .= "EMAIL;TYPE=INTERNET:{$societe->email}\r\n";
        }
        
        if ($societe->url) {
            $vcard .= "URL:{$societe->url}\r\n";
        }
        
        $vcard .= "END:VCARD\r\n";
        
        return $vcard;
    }
}
