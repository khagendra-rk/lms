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
        'user_id',
    ];

    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
