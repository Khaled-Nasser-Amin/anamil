<?php

namespace App\Models;

use App\Traits\TwoFactorAuthenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use TwoFactorAuthenticatable,SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'phone',
        'whatsapp',
        'location',
        'store_name',
        'code',
        'category_id',
        'activation',
        'add_product',
        'geoLocation',
    ];

    protected $guarded=['role'];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];


    public function products(){
        return $this->hasMany(Product::class);
    }
    public function getImageAttribute($value){
        return $value ? asset('images/users/'.$value):'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF';
    }

    public function products_colors(){
        return $this->hasManyThrough(Color::class,Product::class,'user_id','product_id');
    }

    public function orders_colors(){
        return $this->products_colors()->has('order');
    }

    public function orders(){
        return $this->belongsToMany(Order::class,'order_vendor','vendor_id','order_id')->withPivot(['subtotal','taxes','total_amount']);
    }

    public function myActivities(){
        return $this->hasMany(Activity::class,'vendor_id');
    }
    public function refunds(){
        return $this->hasMany(Refund::class,'vendor_id');
    }
    public function activitesBelongsToMe(){
        return $this->hasMany(Activity::class,'belongs_to_id');
    }

}
