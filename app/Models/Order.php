<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['quotation_id', 'final_delivery', 'observations', 'suggested'];

    public function quotation(){
        return $this->belongsTo('App\Models\Quotation');
    }

    public function payments(){
        return $this->hasMany('App\Models\Payments');
    }

}
