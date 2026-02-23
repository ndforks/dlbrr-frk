<?php

namespace App\Http\Controllers\Holiday;

use App\Http\Controllers\Controller;
use App\Services\HolidayService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ListHoliday extends Controller
{
    private HolidayService $service;

    public function __construct(HolidayService $service)
    {
        $this->service = $service;
    }

    public function __invoke(Request $request): View
    {
        $page = $request->integer('page', 0);
        $limit = $request->integer('limit', 25);

        $filters = [
            'all' => $request->input('search_all'),
        ];

        $data = $this->service->list($filters, $page, $limit);

        return view('holiday.list', $data);
    }
}
