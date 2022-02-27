<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ProductCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if($this->isActive == 1 && $this->stock > 0 && !checkCollectionActive($this)){
            $quantity=[];
            if($this->pivot && $this->pivot->quantity > 0){
                $quantity=['quantity' =>(string) $this->pivot->quantity];
            }
            return array_merge([
                    'name' => app()->getLocale() == 'ar' ? $this->name_ar:$this->name_en,
                    'image' => $this->image,
                    'type' => $this->type,
                    'price' => $this->price."",
                    'sale' => $this->sale."",
                    'sale_precentage' => ($this->sale > 0 ? round((($this->price - $this->sale)*100)/$this->price) : 0)."%" ,
                    'id' => (string) $this->id,
            ],$quantity);
        }

    }
}
