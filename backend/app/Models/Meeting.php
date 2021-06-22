<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'subject',
        'date',
        'start',
        'end',
        'status',
        'new',
        'created_at',
        'updated_at',
    ];
}
