<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=['name_ar','name_en','slug','image','shipping_place','status'];
    protected $guarded=[];

    // public function child_categories(){
    //     return $this->hasMany(Category::class,'parent_id');
    // }
    // public function parent_category(){
    //     return $this->belongsTo(Category::class,'parent_id');
    // }

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function scopeOrders(){
        return $this->products()->withTrashed()->has('orders');
    }

    public function getImageAttribute($value){
        return $value ? asset('images/categories/'.$value):'https://ui-avatars.com/api/?name='.urlencode($this->name_en).'&color=7F9CF5&background=EBF4FF';
    }
    public function getSlugAttribute($value){
        return Str::slug($value);
    }
}
