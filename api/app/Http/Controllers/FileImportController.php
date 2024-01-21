<?php

namespace App\Http\Controllers;

use App\Enums\FileImportStatus;
use App\Imports\ContactImport;
use App\Jobs\UpdateFileImportStatus;
use App\Models\FileImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use ZipArchive;
use Maatwebsite\Excel\Facades\Excel;

class FileImportController extends Controller
{
    public function import(Request $request)
    {
        try {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'error'=> 'Only files with xlsx and xls extensions are acceptable.',
            ], 400);
        }

        // Get the uploaded file
        $file = $request->file('file');
        $filename = $request->file('file')->getClientOriginalName();

        # Local (public)
        // $filename = 'Teste.xlsx';

        $fileImport = new FileImport();
        $fileImport->filename = $filename;
        $fileImport->status = FileImportStatus::QUEUED->name;

        $fileImport->save();

        (new ContactImport($fileImport))->queue($file)->chain([
            new UpdateFileImportStatus($fileImport),
        ]);

        return response()->json([
            'fileImportId' => $fileImport->id,
        ]);
    }
}
