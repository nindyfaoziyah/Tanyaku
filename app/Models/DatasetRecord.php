<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DatasetRecord extends Model
{
    protected $fillable = [
        'dataset_id',
        'record_data',
    ];

    protected function casts(): array
{
    return [
        'record_data' => 'array',
    ];
}

    public function dataset(): BelongsTo
    {
        return $this->belongsTo(Dataset::class);
    }
}