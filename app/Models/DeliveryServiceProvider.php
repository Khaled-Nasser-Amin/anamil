<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;


class DeliveryServiceProvider extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    protected $fillable=[
        'name',
        'email',
        'password',
        'image',
        'phone',
        'code',
        'personal_id',
        'driving_license',
        'activation'
    ];
    protected $hidden=[
        'password',
        'code',
    ];


    public function orders(){
        return $this->hasMany(Order::class);
    }
    public function getPersonalIdAttribute($value){
        return asset('images/users/'.$value);
    }
    public function getDrivingLicenseAttribute($value){
        return asset('images/users/'.$value);
    }
    public function getImageAttribute($value){
        return $value ? asset('images/users/'.$value):'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF';
    }

}
