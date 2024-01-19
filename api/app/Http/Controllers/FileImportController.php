<?php

namespace App\Http\Controllers;

use App\Enums\FileImportStatus;
use App\Imports\ContactImport;
use App\Models\FileImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use Maatwebsite\Excel\Facades\Excel;

class FileImportController extends Controller
{
    public function import(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        // Get the uploaded file
        $file = $request->file('file');
        $filename = $request->file('file')->getClientOriginalName();

        # Local (public)
        // $filename = 'Teste.xlsx';

        $fileImport = new FileImport();
        $fileImport->filename = $filename;
        $fileImport->status = FileImportStatus::QUEUED->name;

        $fileImport->save();

        Excel::import(new ContactImport($fileImport), $file);

        return response()->json([
            'result' => 200
        ]);
    }
}
