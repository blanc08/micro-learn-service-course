<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $table = 'chapters';
    protected $guarded = ['id'];

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('id', 'ASC');
    }
}
