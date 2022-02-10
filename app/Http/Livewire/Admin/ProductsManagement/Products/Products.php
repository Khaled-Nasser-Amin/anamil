<?php

namespace App\Http\Livewire\Admin\ProductsManagement\Products;

use App\Http\Controllers\admin\productManagement\products\ProductController;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;

class Products extends Component
{
    use WithPagination,AuthorizesRequests;
    public $price;
    public $category;
    public $search;
    public $store_name;
    public $date;
    public $stock;
    public $type;
    public $filterProducts;

    protected $listeners=['delete'];

    public function mount(){
        $product="";
        if(session()->has('product_id'))
            $product=Product::find(session()->get('product_id'));
        if($product){
            $this->category=$product->category_id;
            $this->size=$product->size;
            $this->type=$product->type;
            $this->stock=$product->stock;
            $this->date=$product->created_at;
            $this->search=app()->getLocale() == 'ar' ? $product->name_ar:$product->name_en;
        }

        session()->forget(['product_id']);
    }

    public function render()
    {
        $categories=Category::all();
        $setting=Setting::find(1);
        $products=$this->search();

        return view('admin.productManagement.products.index',compact('products','categories','setting'))->extends('admin.layouts.appLogged')->section('content');
    }



    public function confirmDelete($id){
        $this->emit('confirmDelete',$id);
    }

    //delete product
    public function delete(Product $product){
        $this->authorize('delete',$product);
        $cat=$product->category;
        $instance=new ProductController();
        if($product->type == 'group'){
            $stock=$product->stock;
            foreach($product->child_products()->withTrashed()->get() as $child){
                $child->update(['stock' => $child->stock+($stock*$child->pivot->quantity)]);
                $child->save();
            }

            $product->update(['stock' => 0]);
            $product->save();
        }
        $vendor_id=$instance->destroy($product);

        session()->flash('success',__('text.Product Deleted Successfully') );
        create_activity('Product Deleted',auth()->user()->id,$vendor_id);


    }


    //update product's featured
    public function updateFeatured(Product $product){
        $this->authorize('update',$product);
        if(checkCollectionActive($product)){
            return ;
        }
        $numberOfProducts=auth()->user()->products->where('featured',1)->count();
        if ($numberOfProducts < 6 || $product->featured == 1){
            if($product->featured == 0 ){
                $featured= 1;
                create_activity('Added a product as a feature',auth()->user()->id,$product->user_id);

            }else{
                $featured= 0;
                create_activity('Removed a product as a feature',auth()->user()->id,$product->user_id);
            }

            $product->update([
                'featured'=>$featured
            ]);
        }else{
            $this->dispatchBrowserEvent('danger',__('text.You have only 6 special products'));
        }

    }
    // public function updateFeatured(Product $product){
    //     Gate::authorize('isAdmin');
    //     if(checkCollectionActive($product)){
    //         return ;
    //     }
    //     $numberOfProducts=Product::where('featured',1)->count();
    //     $allowed_featured_products=Setting::find(1)->no_of_featured_products;
    //     if ($numberOfProducts < $allowed_featured_products || $product->featured == 1){
    //         if($product->featured == 0 ){
    //             $featured= 1;
    //             create_activity('Added a product as a feature',auth()->user()->id,$product->user_id);

    //         }else{
    //             $featured= 0;
    //             create_activity('Removed a product as a feature',auth()->user()->id,$product->user_id);
    //         }

    //         $product->update([
    //             'featured'=>$featured
    //         ]);
    //     }else{
    //         $this->dispatchBrowserEvent('danger',__('text.You have only'). $allowed_featured_products . __('text.special products'));
    //     }

    // }



    //change product status
    public function updateStatus(Product $product){
        $this->authorize('update',$product);
        if($product->isActive == 0 ){
            $status= 1;
            $product->update([
                'isActive'=>$status
            ]);
            // $this->updateCategoryStatus($product->category);
            create_activity('Active a product',auth()->user()->id,$product->user_id);

        }else{
            $status= 0;
            $product->update([
                'isActive'=>$status
            ]);
            // $this->deleteCategoryStatus($product->category);
            create_activity('Unactive a product',auth()->user()->id,$product->user_id);
        }


    }


    //search and return products paginated
    protected function search(){
       return Product::
       when($this->store_name,function ($q) {
            return $q->join('users','users.id','=','products.user_id')
            ->where('users.store_name','like','%'.$this->store_name.'%')->select('products.*');
        })
        ->where(function($q){
            return
                $q->where(function($q){
                    $q->when($this->search,function ($q){
                        return $q->where('products.name_ar','like','%'.$this->search.'%')
                        ->orWhere('products.name_en','like','%'.$this->search.'%')
                        ->orWhere('products.description_ar','like','%'.$this->search.'%')
                        ->orWhere('products.description_en','like','%'.$this->search.'%');
                    });
                })
                ->when($this->category,function ($q){
                        return $q->where('products.category_id',$this->category);
                })
                ->when($this->type,function ($q){
                        return $q->where('products.type',$this->type);
                })
                ->when($this->stock,function ($q){
                        return $q->where('products.stock',$this->stock);
                })
                ->when($this->date,function ($q)  {
                    return $q->whereDate('products.created_at',$this->date);
                });
            })
            ->distinct('products.id')->latest('products.created_at')->paginate(12);
    }


}
