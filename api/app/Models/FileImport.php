<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FileImport extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = ["filename", "status"];

    public function fileImportErrors(): HasMany
    {
        return $this->hasMany(FileImportError::class);
    }
}
