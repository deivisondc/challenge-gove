<?php

namespace App\Http\Controllers;

use App\Models\FileImport;
use App\Services\FileImportService;
use DateTime;
use Illuminate\Http\Request;

class FileImportController extends Controller
{
    public function __construct(
        protected FileImportService $service
    ) {}

    public function index(Request $request)
    {
        $filter = $request->query->all();
        $createdAt = $filter["createdAt"] ?? null;
        $formattedDate = '';

        if ($createdAt) {
            $formattedDate = DateTime::createFromFormat('Y-m-d', $createdAt);

            if ($formattedDate == false) {
                return response()->json([
                    'error' => 'Invalid value for query param "createdAt"'
                ]);
            }
        }
        $filter['createdAt'] = $formattedDate;

        $fileImports = $this->service->getAll($filter);

        return response()->json($fileImports);
    }

    public function show(FileImport $fileImport)
    {
        return response()->json($fileImport);
    }

    public function import(Request $request)
    {
        try {
            $this->validateFile($request);

            $fileImport = $this->service->importFile($request->file('file'));

            return response()->json([
                'fileImportId' => $fileImport['id'],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error'=> $e->getMessage(),
            ], 500);
        }
    }

    private function validateFile(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
    }
}
