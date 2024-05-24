<?php

namespace App\Http\Controllers;

use App\Exports\OrderExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportToExcel(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return Excel::download(new OrderExport($startDate, $endDate), 'orders.xlsx');
    }
}
