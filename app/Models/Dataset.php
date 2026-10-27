<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Dataset extends Model
{
 protected $fillable = [
    'department_id',
    'uploaded_by',
    'name',
    'description',
    'original_filename',
    'file_path',
    'file_type',
    'status',
];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'uploaded_by'
        );
    }

    public function records(): HasMany
    {
        return $this->hasMany(DatasetRecord::class);
    }
}