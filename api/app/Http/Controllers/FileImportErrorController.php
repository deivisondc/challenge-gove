<?php

namespace App\Http\Controllers;

use App\Models\FileImport;
use App\Models\FileImportError;
use Illuminate\Http\Request;

class FileImportErrorController extends Controller
{

    public function index(FileImport $fileImport, Request $request)
    {
        $pageSize = $request->query("pageSize",10);

        return FileImportError::where("file_import_id", $fileImport->id)
            ->paginate($pageSize);
    }

}
