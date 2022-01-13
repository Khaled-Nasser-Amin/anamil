<?php

namespace App\Http\Controllers\admin\productManagement\products;
use App\Http\Controllers\Controller;

use App\Models\Images;
use App\Traits\ImageTrait;
use App\Models\Product;
use Illuminate\Support\Facades\Request;

class ProductController extends Controller
{
    use ImageTrait;

    public function store($request)
    {
        $this->authorize('create',Product::class);
        $data=collect($request)->except(['category_id','image','colorsIndex'])->toArray();
        $data=$this->livewireAddSingleImage($request,$data,'products');
        $product=Product::create($data);
        $product->category()->associate($request['category_id'])->save();
        return $product;
    }



    public function update($request,$id)
    {
        $product=Product::findOrFail($id);

        $this->authorize('update',$product);
        $data=collect($request)->except(['image','colorsIndex','taxes_selected'])->toArray();
        if ($request['image']){
            if(!$product->has('orders')){
                $this->livewireDeleteSingleImage($product,'products');
            }
            $data=$this->livewireAddSingleImage($request,$data,'products');
        }

        $product->update($data);
        $product->taxes()->detach();
        $product->taxes()->syncWithoutDetaching($request['taxes_selected']);
        $product->save();
        return $product;
    }
    public function destroy($product)
    {
        $this->authorize('delete',$product);
        $vendor_id=$product->user_id;
        $product->delete();
        return $vendor_id;
    }

    public function show(Request $request,Product $product,$slug){
        $this->authorize('view',$product);
        $images[]=['name' => $product->image];
        return view('admin.productManagement.products.show',compact('product','images'));
    }
    public function addNewProduct(){
        $this->authorize('create',Product::class);
        return view('admin.productManagement.products.create');
    }
    public function updateProduct(Product $product){
        $this->authorize('update',$product);
        return view('admin.productManagement.products.edit',compact('product'));
    }









}
