<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Task extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id', 'input_data', 'status', 'result', 'error_message'
    ];

    protected $casts = [
        'input_data' => 'array',
    ];

    protected static function booted()
    {
        static::creating(function ($task) {
            $task->id = (string) Str::uuid();
        });
    }
}
