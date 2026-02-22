<?php

namespace App\Http\Controllers\Contact;

use App\Http\Controllers\Controller;
use App\Services\ContactService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListContacts extends Controller
{
    public function __construct(private readonly ContactService $service)
    {
    }

    public function __invoke(Request $request): View
    {
        $page = $request->integer('page', 0);
        $limit = $request->integer('limit', 25);

        $filters = [
            'all' => $request->input('search_all'),
            'lastname' => $request->input('search_lastname'),
            'firstname' => $request->input('search_firstname'),
            'societe' => $request->input('search_societe'),
            'email' => $request->input('search_email'),
            'phone' => $request->input('search_phone'),
        ];

        $data = $this->service->list($filters, $page, $limit);

        return view('contact.list', $data);
    }
}
