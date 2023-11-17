<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $guarded = [' '];

    public function users() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function voice(){
        return $this->hasMany(Voice::class, 'question_id', 'id');
    }
}
