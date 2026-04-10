<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportBatch extends Model
{
    //
    protected $fillable = [
        'user_id', 'filename', 'total_rows', 'total_batches',
        'processed_batches', 'imported_rows', 'failed_rows',
        'status', 'errors',
    ];
}
