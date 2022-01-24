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

    protected $listeners = ['edit', 'selected_product','change_quantity'];

    public $index; //modal size and stock

    public $index_of_color;

    public function mount(){
        $this->taxes=Tax::get();
        $this->taxes_selected=[];
        $this->products=Product::where('type','single')->where('user_id',auth()->user()->id)->get();
        $this->type="single";
        $this->productsIndex[]=['product_id' => '','quantity' => '' ,'stock' => ''];

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
        dd($data);
        $product=$productStore->store($data);
        auth()->user()->products()->save($product);
        $product->taxes()->syncWithoutDetaching($this->taxes_selected);
        $this->associateImagesWithProduct($data,$product);
        $this->resetVariables();
        $this->dispatchBrowserEvent('success', __('text.Product Added Successfully'));
        create_activity('Product Created',auth()->user()->id,$product->user_id);


    }


    public function edit(){
        $this->authorize('update',$this->product);
        $this->resetVariables();
        foreach ($this->product->colors as $row){
            foreach($row->sizes as $size){
                $sizes[]=['id'=>$size->id,'size' => $size->size,'stock' => $size->stock];
            }
            $this->colorsIndex[]= ['id'=>$row->id,'color' => $row->color,'price'=> $row->price,'sale'=> $row->sale,'sizes'=> $sizes];
            $sizes=[];
        }
        $this->name_ar= $this->product->name_ar;
        $this->name_en=$this->product->name_en;
        $this->taxes_selected=$this->product->taxes->pluck('id')->toArray();
        $this->description_ar=$this->product->description_ar;
        $this->description_en=$this->product->description_en;
        $this->slug=$this->product->slug;
        $this->typeOfFabric=$this->product->typeOfFabric;
        $this->typeOfSleeve=$this->product->typeOfSleeve;
        $this->additions=$this->product->additions;
        $this->category_id=$this->product->category_id;

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
            'price' => 'required|integer',
            'stock' => 'required|integer',
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
        $this->productsIndex[] = ['product_id' => '', 'quantity' => ''];

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
        $this->productsIndex[] = ['product_id' => '', 'quantity' => '','stock' => ''];
    }

    //select product =>  change sizes
    public function selected_product($index, $product_id)
    {
        $product=Product::find($product_id);
        if($product){
            $this->products = collect($this->products)->filter(function ($value, $key) use($product_id){
                return $value['id'] != $product_id;
            });
            $this->productsIndex[$index]['stock']=$product->stock;
        }
    }
    public function change_quantity($index, $quantity)
    {
        if($this->productsIndex[$index]['stock']){
            (int) $this->productsIndex[$index]['stock']-=( (int) $this->stock* (int) $quantity);
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
            $productsGroup = collect($this->productsIndex)->groupBy('product_id')->map(function ($value) {
                $group_by_sizes = $value->groupBy('size')->map(function ($value2) {
                    return ['size' => $value2[0]['size'], 'quantity' => $value2->sum('quantity')];
                });
                return  [$value[0]['product_id'], $group_by_sizes];
            });
            foreach ($productsGroup as $key => $value) {
                $child_product_id = $value[0];
                $product->child_products()->syncWithoutDetaching($child_product_id);
                foreach ($value[1] as $key => $value) {
                    $group_id = $product->child_products()->find($child_product_id)->pivot->id;
                    Size::find($value['size'])->groups()->syncWithoutDetaching([$group_id => ['quantity' => $value['quantity']]]);
                }
            }
        }
    }


    protected function group_validation()
    {
        return [
            'productsIndex' => 'required_if:type,group|array|min:1',
            'productsIndex.*.product_id' => 'required_if:type,group|numeric|exists:products,id',
            'productsIndex.*.quantity' => 'required_if:type,group|numeric|min:1',
        ];
    }


}
