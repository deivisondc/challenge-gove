<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ["scheduled_for", "status"];

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function fileImport(): BelongsTo
    {
        return $this->belongsTo(FileImport::class);
    }
}
