<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReactionLog extends Model
{
    use HasFactory;

    protected $fillable = ['foreign_id', 'user_id', 'reaction', 'related_to'];
}
