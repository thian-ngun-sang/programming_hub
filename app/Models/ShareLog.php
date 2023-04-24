<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShareLog extends Model
{
    use HasFactory;

    protected $fillable = ['foreign_id', 'user_id', 'related_to'];
}
