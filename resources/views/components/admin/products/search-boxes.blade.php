<div class="col-sm-12 mt-2 mb-4">
    <form class="row justify-content-center" method="get" action="{{--{{route('products.index')}}--}}">
        <div class="col-sm-6 col-md-3">
            <div class="form-group">
                <label for="field-00" class="control-label">{{__('text.Search')}}</label>
                <input type="text" wire:model="search" class="form-control" id="field-00" placeholder="{{__('text.Product Name')}},{{ __('text.Description') }}">
            </div>
        </div>
        @can('isAdmin')
            <div class="col-sm-6 col-md-3">
                <div class="form-group">
                    <label for="category_name" class="control-label">{{__('text.Category Name')}}</label>
                    <select id="category_name" class="form-control" wire:model="category">
                        <option value="" selected class="bg-secondary text-white">- {{__('text.Choose Category')}}</option>
                        @foreach(\App\Models\Category::withTrashed()->get() as $category)
                        <option value="{{ $category->id }}" class="bg-secondary text-white">{{ app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
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
                <label for="field-0" class="control-label">{{__('text.Stock')}}</label>
                <input type="text" wire:model="stock" class="form-control" id="field-0">
            </div>
        </div>
        <div class="col-sm-6 col-md-3">

            <div class="form-group">
                <label for="filterProducts" class="control-label">{{__('text.Type')}}</label>
                <select id="filterProducts" class="form-control" wire:model="type">
                    <option value="" selected class="bg-secondary text-white"></option>
                    <option value="single"  class="bg-secondary text-white">@lang('text.Single product')</option>
                    <option value="group"  class="bg-secondary text-white">@lang('text.Group of Products')</option>

                </select>
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
