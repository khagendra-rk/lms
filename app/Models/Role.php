<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    // public function hasPermission($permission)
    // {
    //     return $this->permissions->contains($permission);
    // }
    public function hasPermission($slug): bool
    {
        return $this->permissions()->where('slug', $slug)->exists();
    }
}
