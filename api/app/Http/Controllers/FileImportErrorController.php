<?php

namespace App\Http\Controllers;

use App\Http\Utils;
use App\Models\FileImport;
use App\Models\FileImportError;
use App\Services\FileImportErrorService;
use Illuminate\Http\Request;

class FileImportErrorController extends Controller
{
    public function __construct(
        protected FileImportErrorService $service
    ) {}

    public function index(FileImport $fileImport, Request $request)
    {
        $fileImportErrors = $this->service->getByFile($fileImport->id, $request->query->all());

        return response()->json($fileImportErrors);
    }

}
