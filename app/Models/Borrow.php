<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Borrow extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'student_id',
        'teacher_id',
        'index_id',
        'issued_by',
        'issued_at',
        'returned_at',
    ];

    public function issuedBy()
    {
        return $this->belongsTo(User::class, 'issued_by', 'id');
    }

    public function index()
    {
        return $this->belongsTo(Index::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
