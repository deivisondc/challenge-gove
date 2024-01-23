<?php

namespace App\Http\Controllers;

use App\Http\Utils;
use App\Models\FileImport;
use App\Models\FileImportError;
use Illuminate\Http\Request;

class FileImportErrorController extends Controller
{

    public function index(FileImport $fileImport, Request $request)
    {
        $pageSize = $request->query("pageSize",10);

        $fileImportErrors = FileImportError::where("file_import_id", $fileImport->id)
            ->paginate($pageSize)
            ->toArray();

        $links = Utils::formatPaginationLinks($fileImportErrors);
        $fileImportErrors['links'] = $links;

        return response()->json($fileImportErrors);
    }

}
