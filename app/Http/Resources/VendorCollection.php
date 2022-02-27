<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorCollection extends JsonResource
{

    public function toArray($request)
    {
        if($this->products()->where('isActive',1)->count() > 0 && $this->products()->get()->sum('stock') > 0){
            return [
                    'id' => (string) $this->id,
                    'name' => $this->store_name ,
                    'image' => $this->image,
                    'phone' => $this->phone."",
                    'email' => $this->email,
                    'location' => $this->location,
                    'whatsapp' => $this->whatsapp."",
                    ];
        }
    }
}
