<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $casts = ['created_at' => 'datetime:Y-m-d H:m:s', 'updated_at' => 'datetime:Y-m-d H:m:s',];
    protected $table = 'chapters';
    protected $guarded = ['id'];

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('id', 'ASC');
    }
}
