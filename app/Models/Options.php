<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Options extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'options';

    protected $fillable = [
        'question_id',
        'option_text',
        'is_correct',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public function Question()
    {
        return $this->belongsTo(Question::class);
    }
}
