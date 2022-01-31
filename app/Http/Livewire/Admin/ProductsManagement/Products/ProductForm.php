<?php

namespace App\Http\Livewire\Admin\ProductsManagement\Products;

use App\Http\Controllers\admin\productManagement\products\ProductController;
use App\Models\Category;
use App\Models\Images;
use App\Models\Product;
use App\Models\Size;
use App\Models\Tax;
use App\Traits\ImageTrait;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class ProductForm extends Component
{
use WithFileUploads,AuthorizesRequests,ImageTrait;
    public $name_ar, $name_en,$taxes,$taxes_selected,
        $description_ar,$description_en,$productsIndex ,$category_id,
        $image,$type,$slug,$products,$search;
    public $action; // action for change form action between add new product and update product
    public $product;

    public $price,$sale,$stock,$groupImage;

    protected $listeners = ['edit', 'selected_product','change_quantity','change_stock'];



    public function mount(){
        $this->taxes=Tax::get();
        $this->taxes_selected=[];
        $this->stock=1;
        $this->products=Product::where('type','single')->where('user_id',auth()->user()->id)->get();
        $this->type="single";
        $this->productsIndex[]=['product_id' => '','quantity' => '' ,'stock' => '','calc' => ''];

    }

    public function store(){
        $this->authorize('create',Product::class);
        $productStore=new ProductController();
        if ($this->type == 'single') {
            $data = $this->validation($this->imageValidationForStore());
        } else {
            $data = $this->validation(array_merge($this->imageValidationForStore(), $this->group_validation()));
        }
        $data=$this->setSlug($data);
        $product=$productStore->store($data);
        auth()->user()->products()->save($product);
        if($this->type == 'group'){
            $this->groupType($product);
        }
        $product->taxes()->syncWithoutDetaching($this->taxes_selected);
        $this->associateImagesWithProduct($data,$product);
        $this->resetVariables();
        $this->dispatchBrowserEvent('success', __('text.Product Added Successfully'));
        create_activity('Product Created',auth()->user()->id,$product->user_id);


    }


    public function edit(){
        $this->authorize('update',$this->product);
        $this->resetVariables();
        $this->products=Product::where('type','single')->where('user_id',auth()->user()->id)->get();
        $this->name_ar= $this->product->name_ar;
        $this->name_en=$this->product->name_en;
        $this->taxes_selected=$this->product->taxes->pluck('id')->toArray();
        $this->description_ar=$this->product->description_ar;
        $this->description_en=$this->product->description_en;
        $this->slug=$this->product->slug;
        $this->price=$this->product->price;
        $this->sale=$this->product->sale;
        $this->stock=$this->product->stock;
        $this->type=$this->product->type;
        $this->category_id=$this->product->category_id;
        if($this->type== 'group'){
            $this->productsIndex=[];
            foreach($this->product->child_products()->get() as $product){
                $this->productsIndex[]=['product_id' =>$product->id,'quantity' => $product->pivot->quantity ,'stock' => $product->stock,'calc' => $product->stock];
            }
        }
        $this->products=Product::where('type','single')->where('user_id',auth()->user()->id)->whereNotIn('id',collect($this->productsIndex)->pluck('product_id'))->get();

        $this->emit('refreshMultiSelect');
    }

    public function update($id){
        $this->authorize('update',$this->product);
        $productUpdate=new ProductController();
        $data=$this->validation(['image' => 'nullable|mimes:jpg,png,jpeg,gif']);
        $product=$productUpdate->update($data,$id);
        $this->updateColorsAndPrice ($product);
        if($product->wasChanged('category_id')){
            $new_cat=Category::find($product->category_id);
            $old_cat=Category::find($this->product->category_id);
            $this->updateCategoryStatus($new_cat);
            $this->deleteCategoryStatus($old_cat);
        }

        if($product->wasChanged()){
            create_activity('Product Updated',auth()->user()->id,$product->user_id);
        }
        $this->dispatchBrowserEvent('success', __('text.Product Updated Successfully'));

    }

    public function render()
    {
        return view('components.admin.products.product-form');
    }

    public function validation($image_validation){
        $this->sale = $this->sale == '' ? 0 : $this->sale;
        return $this->validate(array_merge([
            'name_ar' => 'required|string|max:255|',
            'name_en' => 'required|string|max:255|',
            'slug' => 'nullable|string|max:255|',
            'description_ar' => 'nullable|string|max:255|',
            'description_en' => 'nullable|string|max:255|',
            'taxes_selected'=>'required|array|min:1',
            'taxes_selected.*'=>'exists:taxes,id',
            'price' => 'required|integer|gt:0',
            'stock' => 'required|integer|gt:0',
            'sale' => 'nullable|integer|lt:price',
            'type' => ['required', Rule::in(['single', 'group'])],
            'category_id' => [ Rule::requiredIf(auth()->user()->role == 'admin'),'integer','exists:categories,id'],
        ],$image_validation));

    }

    //image validation
    protected function imageValidationForStore()
    {
        return [
            'image' => 'required|mimes:jpg,png,jpeg,gif',
            'groupImage' => 'required|array|min:1',
            'groupImage.*' => 'mimes:jpeg,jpg,png,webp',
        ];
    }
    protected function imageValidationForUpdate()
    {
        return [
            'image' => 'nullable|mimes:jpg,png,jpeg,gif',
            'groupImage' => 'nullable|array|min:1',
            'groupImage.*' => 'mimes:jpeg,jpg,png,webp',
        ];
    }

    //images
    public function associateImagesWithProduct($data, $product)
    {
        $imagesNames = $this->livewireGroupImages($data, 'products');
        foreach ($imagesNames as $image)
            $product->images()->create([
                'name' => $image
            ]);
    }

    public function resetVariables(){

        $this->reset([
            'name_ar', 'name_en',
            'description_ar', 'description_en', 'image',
            'groupImage', 'slug', 'category_id', 'taxes_selected', 'sale', 'price', 'productsIndex'
        ]);
        $this->productsIndex[]=['product_id' => '','quantity' => '' ,'stock' => '','calc' => ''];

    }



    //set slug when slug = null
    public function setSlug($data){
        if ($this->slug == null){
            $data['slug'] = $this->name_en.'-'.$this->name_ar;
        }
        return $data;

    }


    //group of products


    public function addProduct()
    {
        $this->productsIndex[] = ['product_id' => '', 'quantity' => '','stock' => '','calc' => ''];
    }

    //select product
    public function selected_product($index, $product_id)
    {
        $product=Product::find($product_id);
        if($product){
            $this->products = collect($this->products)->filter(function ($value, $key) use($product_id){
                return $value['id'] != $product_id;
            });

            if($this->product && $this->product->child_products->where('id',$product_id)->first()){
                $old_quantity=$this->product->child_products->where('id',$this->productsIndex[$index]['product_id'])->first()->pivot->quantity;
                $this->productsIndex[$index]['stock']=$product->stock;
                $this->productsIndex[$index]['calc']=$product->stock;
                $this->productsIndex[$index]['quantity']= $old_quantity;
            }
            else{
                $this->productsIndex[$index]['stock']=$product->stock;
                $this->productsIndex[$index]['calc']=$product->stock;
            }
        }

    }
    public function change_quantity($index, $quantity)
    {
        if($this->action == 'update('.$this->product->id.')'&& $this->product->child_products->where('id',$this->productsIndex[$index]['product_id'])->first()){
            $old_stock=$this->product->stock;
            $old_quantity=$this->product->child_products->where('id',$this->productsIndex[$index]['product_id'])->first()->pivot->quantity;
            (int) $this->productsIndex[$index]['calc']=(int) $this->productsIndex[$index]['stock'] - (( (int) $this->stock* (int) $quantity) - ($old_stock*$old_quantity));
        }
        elseif($this->productsIndex[$index]['stock']){
            (int) $this->productsIndex[$index]['calc']=(int) $this->productsIndex[$index]['stock'] - ( (int) $this->stock* (int) $quantity);
        }
        $this->validate([
            'productsIndex.*.stock' => 'integer|gt:0',
            'productsIndex.*.calc' => 'integer|gte:0',
            'productsIndex.*.quantity' => 'integer|gt:0',
        ],
        [
            'productsIndex.*.calc.gte' => __('text.Out of stock'),
        ]
        );
    }
    public function change_stock()
    {
        $this->validate([
            'stock' => 'required|integer|gt:0',
        ]);
        foreach($this->productsIndex as $key=>$product){
            $this->change_quantity($key,$product['quantity']);
        }
    }

    public function deleteProduct($index)
    {
        $product=Product::find($this->productsIndex[$index]['product_id']);
        if($product){
            $this->products=collect($this->products)->push((object) $product);

        }
        unset($this->productsIndex[$index]);
        array_values($this->productsIndex);
    }


    public function groupType($product)
    {
        if ($this->type == 'group') {
            foreach ($this->productsIndex as $value) {
                $child_product=Product::find($value['product_id']);
                $child_product->update(['stock' => $value['calc']]);
                $child_product->save();
                $product->child_products()->syncWithoutDetaching([$value['product_id'] => ['quantity' => $value['quantity']]]);

            }
        }
    }


    protected function group_validation()
    {
        return [
            'productsIndex' => 'required_if:type,group|array|min:1',
            'productsIndex.*.product_id' => 'required_if:type,group|numeric|exists:products,id',
            'productsIndex.*.quantity' => 'required_if:type,group|numeric|min:1',
            'productsIndex.*.stock' => 'required|integer|gte:0',
            'productsIndex.*.calc' => 'required|integer|gte:0',
            'productsIndex.*.quantity' => 'required|integer|gt:0',
        ];
    }


}
