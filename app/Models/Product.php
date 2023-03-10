<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'amount', 'term', 'type'];

    public function fixedActivities(){
        return $this->hasMany('App\Models\FixedActivity');
    }

    public function prices(){
        return $this->hasMany('App\Models\Price');
    }

    public function times(){
        return $this->hasMany('App\Models\Time');
    }
}
