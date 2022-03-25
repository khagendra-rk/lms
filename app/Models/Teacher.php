<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'phone_no',
        'address',
        'email',
        'college_email',
        'image',
    ];

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }
}
