<div class="col-sm-12 mt-2 mb-4">
    <form class="row justify-content-center" method="get" action="{{--{{route('products.index')}}--}}">
        <div class="col-sm-6 col-md-3">
            <div class="form-group">
                <label for="category_name" class="control-label">{{__('text.Category Name')}}</label>
                <select id="category_name" class="form-control" wire:model="category">
                    <option value="" selected class="bg-secondary text-white">- {{__('text.Choose Category')}}</option>
                    @foreach(\App\Models\Category::withTrashed()->where('parent_id',0)->get() as $category)
                    @php
                    recursion($category->id,'- ','bg-secondary');
                    @endphp
                    @endforeach
                </select>
            </div>
        </div>
        @can('isAdmin')
            <div class="col-sm-6 col-md-3">

                <div class="form-group">
                    <label for="filterProducts" class="control-label">{{__('text.Filter Products')}}</label>
                    <select id="filterProducts" class="form-control" wire:model="filterProducts">
                        <option value="" selected class="bg-secondary text-white"></option>
                        <option value="My Products" selected class="bg-secondary text-white">@lang('text.My Products')</option>
                        <option value="All Products" selected class="bg-secondary text-white">@lang('text.All Products')</option>

                    </select>
                </div>

            </div>
            <div class="col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="field-00" class="control-label">{{__('text.Store Name')}}</label>
                    <input type="text" wire:model="store_name" class="form-control" id="field-00" placeholder="{{__('text.Store Name')}}...">
                </div>
            </div>
        @endcan
        <div class="col-sm-6 col-md-3">
            <div class="form-group">
                <label for="field-00" class="control-label">{{__('text.Product Name')}}</label>
                <input type="text" wire:model="productName" class="form-control" id="field-00" placeholder="{{__('text.Product Name')}}...">
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="form-group">
                <label for="field-0" class="control-label">{{__('text.Type Of Fabric')}}</label>
                <input type="text" wire:model="typeOfFabric" class="form-control" id="field-0" placeholder="الندى">
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="form-group">
                <label for="field-2" class="control-label">{{__('text.Type Of Sleeve')}}</label>
                <input type="text" wire:model="typeOfSleeve" class="form-control" id="field-2" placeholder="مربع">
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="form-group">
                <label for="field-4" class="control-label">{{__('text.Size')}}</label>
                <input type="text" wire:model="size" class="form-control" id="field-4" placeholder="42">
            </div>
        </div>
        <div class="col-sm-6 col-md-3">
            <div class="form-group">
                <label for="field-5" class="control-label">{{__('text.Date')}}</label>
                <input type="date" wire:model="date" class="form-control" id="field-5">
            </div>
        </div>

        <div class="col-sm-6 col-md-3">
            <div class="form-group">
                <label for="field-3" class="control-label">{{__('text.Price')}}</label>
                <input type="text" wire:model="price" class="form-control" id="field-3" placeholder="{{__('text.Search By Price')}}">
            </div>
        </div>
    </form>
</div>
