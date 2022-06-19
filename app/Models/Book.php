<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'author',
        'publication',
        'edition',
        'published_year',
        'book_type',
        'price',
        'prefix',
        'added_by',
    ];

    public function faculties()
    {
        return $this->belongsToMany(Faculty::class)->withPivot('semester');
    }

    public function indices()
    {
        return $this->hasMany(Index::class);
    }
}
