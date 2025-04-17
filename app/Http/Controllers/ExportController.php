<?php

namespace App\Http\Controllers;

use App\Exports\TicketsExport;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function export()
    {
        return Excel::download(new TicketsExport(), 'laporan_tickets.xlsx');
    }
}