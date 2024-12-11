<?php

namespace App\Http\Controllers;

use App\Exports\DataExport;
use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel as FacadesExcel;
use Excel;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ExcelController extends Controller
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function exportExcel(Request $request)
    {
        return FacadesExcel::download(new DataExport($request), 'data.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }
}
