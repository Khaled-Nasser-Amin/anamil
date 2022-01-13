<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
    protected $fillable=['payment_token','twillo_phone','twillo_token','twillo_sid','contact_phone','contact_email','contact_whatsapp','contact_land_line','shipping_cost_by_kilometer','shipping_status'];
}
