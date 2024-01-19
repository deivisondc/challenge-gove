<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FileImportError extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ["error"];

    public function fileImport(): BelongsTo
    {
        return $this->belongsTo(FileImport::class);
    }
}
