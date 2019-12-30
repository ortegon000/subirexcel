<?php

namespace App\Http\Controllers;

use App\Exports\ImeiTemplateExport;
use App\Imports\ImeiImport;
use App\Jobs\DeleteDuplicateImeis;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ImeiController extends Controller
{
    public function home()
    {
        $queueRunning = \DB::table('jobs')->count();
        return view('welcome', compact('queueRunning'));
    }
    public function uploadExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xls,xlsx|max:12000'
        ]);

        $fileName = $request->file('file')->getClientOriginalName();

        (new ImeiImport($fileName))->import($request->file('file'));

        return back()->with([
            'response' => [
                'status' => 'subiendo',
                'type' => 'success',
                'message' => 'Se estan subiendo los registros'
            ]
        ]);
    }

    public function downloadExcelTemplate()
    {
        return Excel::download(new ImeiTemplateExport(), 'plantilla.xlsx');
    }

    public function deleteDuplicates()
    {
        DeleteDuplicateImeis::dispatch();

        return redirect()->route('home')->with([
            'response' => [
                'status'  => 'Eliminados',
                'type'    => 'warning',
                'message' => 'los registros duplicados se estan eliminado'
            ]
        ]);
    }
}
