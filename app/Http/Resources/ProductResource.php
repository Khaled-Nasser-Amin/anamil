<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $products=['products' => []];
        if($this->type == 'group'){
            $products=['products' => collect(ProductCollection::collection($this->child_products()->get()))->filter()];
        }
        return array_merge([
            'name' =>  app()->getLocale() == 'ar' ? $this->name_ar:$this->name_en,
            'images' => collect($this->images->pluck('name'))->prepend($this->image),
            'type' => $this->type,
            'id' =>(string) $this->id,
            'description' => app()->getLocale() == 'ar' ? ($this->description_ar ?? ''):($this->description_en ?? ''),
            'price' => $this->price."",
            'sale' => $this->sale."",
            'tax' => ($this->taxes->sum('tax')*($this->sale == 0 || $this->sale == ''? $this->price:$this->sale) )/100 ."",
            'stock' => (string) $this->stock,
        ],$products);
    }
}
