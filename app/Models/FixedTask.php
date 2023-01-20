<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'fixed_activity_id',
        'title'
    ];
}