<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class File extends Model
{
    
    use HasFactory;

    protected $fillable = ['foreign_id', 'user_id', 'filename', 'type', 'attach_to'];

    public function post(){
        return $this->belongsTo(Post::class);
    }
}
