<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone_no',
        'address',
        'email',
        'college_email',
        'parent_name',
        'parent_contact',
        'year',
        'registration_no',
        'symbol_no',
        'image',
        'documents',

    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }
}
