<?php

namespace App\Http\Controllers;

use App\Enums\FileImportStatus;
use App\Http\Utils;
use App\Imports\ExcelImport;
use App\Jobs\UpdateFileImportStatus;
use App\Models\FileImport;
use DateTime;
use Illuminate\Http\Request;

class FileImportController extends Controller
{
    public function index(Request $request)
    {
        $pageSize = $request->query("pageSize", 10);
        $createdAt = $request->query("createdAt");
        $formattedDate = '';

        if ($createdAt) {
            $formattedDate = DateTime::createFromFormat('Y-m-d', $createdAt);

            if ($formattedDate == false) {
                return response()->json([
                    'error' => 'Invalid value for query param "createdAt"'
                ]);
            }

        }

        $fileImports = FileImport::when($formattedDate, function ($query) use ($formattedDate) {
            $query->whereDate("created_at", $formattedDate);
        })
            ->orderBy('id', 'desc')
            ->paginate($pageSize)
            ->toArray();

        $links = Utils::formatPaginationLinks($fileImports);
        $fileImports['links'] = $links;

        return response()->json($fileImports);
    }

    public function show(FileImport $fileImport)
    {
        return response()->json($fileImport);
    }

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

        (new ExcelImport($fileImport))->queue($file)->chain([
            new UpdateFileImportStatus($fileImport),
        ]);

        return response()->json([
            'fileImportId' => $fileImport->id,
        ], 200);
    }
}
