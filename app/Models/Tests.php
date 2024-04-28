<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tests extends Model
{
    use HasFactory;

    protected $table = 'tests';

    protected $fillable = [
        'category_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function Category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function Question()
    {
        return $this->hasMany(Question::class, 'test_id');
    }
}
