<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryCollection extends JsonResource
{

    public function toArray($request)
    {
        return [

            'id' => (string) $this->id,
            'name' => app()->getLocale() == 'ar' ?$this->name_ar: $this->name_en ,
            'image' => $this->image,
        ];

    }
}
