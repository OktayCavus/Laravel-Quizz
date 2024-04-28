<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Roles_Permissions extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'roles_permissions';

    protected $fillable = [
        'role_id',
        'perm_id'
    ];
}
