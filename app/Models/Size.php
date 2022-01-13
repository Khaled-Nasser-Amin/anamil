<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Size extends Model
{
    use HasFactory,SoftDeletes;
    protected $guarded=[];
    public function color(){
        return $this->belongsTo(Color::class);
    }

    public function order(){
        return $this->belongsToMany(Order::class,'orders_sizes','size_id','order_id')->withPivot(['quantity','size','price','tax']);
    }


    public function product(){
        return $this->color->product;
    }
}
