<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'questions';

    protected $fillable = [
        'test_id',
        'question_text'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function Tests()
    {
        return $this->belongsTo(Tests::class, 'test_id');
    }

    public function Options()
    {
        return $this->hasMany(Options::class, 'question_id');
    }
}
