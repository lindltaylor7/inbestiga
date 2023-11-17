<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Academic_level extends Model
{
    use HasFactory;
    protected $fillable=['name'];
    public function academic_products(){
        return $this->belongsToMany('App\Models\Academic_product','academic_level_academic_product');
    }
}
