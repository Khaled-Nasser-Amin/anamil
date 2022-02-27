<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Http\Resources\VendorCollection;
use App\Models\Product;
use App\Models\User;
use App\Traits\Responses;
use Illuminate\Http\Request;

class Category_ProductsController extends Controller
{

    use Responses;

    public function categories_and_special_products(Request $request){

        app()->setlocale($request->lang);
        $categories=Category::whereHas('products',function($q){
            $q->where('stock',">",0)->where('isActive',1);
        })->get();

        $special_products=Product::where('featured',1)->get();

        return $this->success(['categories' => CategoryCollection::collection($categories),'special_products' =>collect(ProductCollection::collection($special_products))->filter()]);
    }


    public function category_vendors(Request $request){ //category_id

        app()->setlocale($request->lang);
        $category=Category::find($request->category_id);
        if($category){
            $vendors=User::where('category_id',$category->id)->orWhere('role','admin')->get();
            return $this->success(collect(VendorCollection::collection($vendors))->filter());

        }else{
            return $this->error("",404);
        }
    }

    public function products(Request $request){ //vendor_id

        app()->setlocale($request->lang);
        $vendor=User::find($request->vendor_id);
        if($vendor){
            $products=$vendor->products;
            return $this->success(collect(ProductCollection::collection($products))->filter());

        }else{
            return $this->error("",404);
        }
    }

    public function product(Request $request){ //product_id
        app()->setlocale($request->lang);
        $product=Product::find($request->product_id);
        if($product){
            return $this->success(new ProductResource($product));

        }else{
            return $this->error("",404);
        }
    }

    public function featured_slider_products(Request $request){

        app()->setlocale($request->lang);
        $products=Product::where('featured_slider',1)->get();
        return $this->success(ProductCollection::collection($products));

    }
}
