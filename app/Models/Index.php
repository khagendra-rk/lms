<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Index extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'book_id',
        'book_prefix',
        'code',
        'borrowed',

    ];
}
