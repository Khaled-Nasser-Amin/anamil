<form wire:submit.prevent="{{$action}}">
    <div class="row">
        <div class="col-lg-6">
            <div class="p-4">
                <div class="form-group">
                    <label for="name_ar"> {{__('text.Name_ar')}}</label>
                    <input type="text" wire:model="name_ar" class="form-control" id="name_ar" name="name_ar">
                    <x-general.input-error for="name_ar" />
                </div>
                <div class="form-group">
                    <label for="name_en"> {{__('text.Name_en')}}</label>
                    <input type="text" wire:model="name_en" class="form-control" id="name_en" name="name_en">
                    <x-general.input-error for="name_en" />
                </div>
                <div class="form-group">
                    <label for="slug">{{__('text.Slug')}}</label>
                    <input type="text" name="slug" wire:model="slug" class="form-control" id="slug" >
                    <x-general.input-error for="slug" />
                </div>
                <div class="form-group">
                    <label for="typeOfFabric">{{__('text.Type Of Fabric')}}</label>
                    <input type="text" wire:model="typeOfFabric" name="typeOfFabric" class="form-control" id="typeOfFabric" >
                    <x-general.input-error for="typeOfFabric" />
                </div>
                <div class="form-group mt-2">
                    <label for="typeOfSleeve">{{__('text.Type Of Sleeve')}}</label>
                    <input type="text" wire:model="typeOfSleeve" name="typeOfSleeve" class="form-control" id="typeOfSleeve" >
                    <x-general.input-error for="typeOfSleeve" />
                </div>

                <div class="form-group">
                    <label for="Description_ar">{{__('text.Description_ar')}}</label>
                    <textarea wire:model="description_ar" class="form-control" name="description_ar" id="Description_ar" rows="5"></textarea>
                    <x-general.input-error for="description_ar" />
                </div>

            </div>
        </div>

        <div class="col-lg-6">
            <div class="row mt-4">
                <div class="col-12 w-100 form-group">
                    <label for="parent" class="control-label">{{__('text.Category')}}</label>
                    <select class="form-control" wire:model="category_id">
                        <option value="" selected class="bg-secondary text-white">- {{__('text.Choose Category')}}</option>
                        @foreach(\App\Models\Category::where('parent_id',0)->get() as $category)
                        @php
                        recursion($category->id,'- ','bg-secondary');
                        @endphp
                        @endforeach
                    </select>
                    <x-general.input-error for="category_id" />
                </div>
            </div>

            <div class="form-group mb-4" >
                <label>{{__('text.Product Image')}} </label>
                <input type="file" wire:model="image"   class="form-control" data-height="210" />
            </div>
            <x-general.input-error for="image" />

            <div class="form-group">
                <label for="additions">{{__('text.Additions')}}</label>
                <input type="text" wire:model="additions" class="form-control" id="additions" name="additions" autocomplete="none">
                <x-general.input-error for="additions" />
            </div>
            <div class="form-group " wire:ignore>
                <label for="tax">{{__('text.Tax')}}</label>
                <select multiple="multiple" wire:model="taxes_selected" class="multi-select form-control border-secondary"  id="my_multi_select1"  data-plugin="multiselect">
                    @forelse ($taxes as $tax)
                        <option value='{{ $tax->id }}'   >{{ app()->getLocale() == 'ar' ? $tax->name_ar: $tax->name_en }} ({{  $tax->tax }}%)</option>
                   @empty
                    <option class="text-muted" disabled>@lang('text.No Data Yet')</option>

                   @endforelse
                </select>
            </div>
            <div class="mb-2">
                            <x-general.input-error for="taxes_selected" />

            </div>

            <div class="form-group">
                <label for="Description_en">{{__('text.Description_en')}}</label>
                <textarea wire:model="description_en" class="form-control" name="description_en" id="Description_en" rows="5"></textarea>
                <x-general.input-error for="description_en" />
            </div>
            <div class="form-group mx-2">
                <label class="mr-2"> {{__('text.Colors and Prices')}}</label>
                <button type="button" class="btn btn-primary d-block" data-toggle="modal" data-target="#colorsAndPrices">@lang('text.Add price for each color')</button>
            </div>
            <x-general.input-error for="colorsIndex" />
            <br>
            <x-general.input-error for="colorsIndex.*.price" />
            <br>
            <x-general.input-error for="colorsIndex.*.sale" />
            <br>
            <x-general.input-error for="colorsIndex.*.color" />
            <x-admin.products.modal-add-color :sizes="$sizes" :index="$index"/>
            <x-admin.products.modal-update-color :sizes="$sizes" :index="$index" />


        </div>
        <div class="col-12 " style="overflow-y: scroll">
            @if (collect($colorsIndex)->count() > 0)
                <table class="table table-borderd text-center">
                    <tr>
                        <th>@lang('text.Color')</th>
                        <th>@lang('text.Price')</th>
                        <th>@lang('text.Sale Price')</th>
                        <th>@lang('text.Action')</th>
                    </tr>
                    @foreach ($colorsIndex as $index => $value)
                        <tr>
                            <td><span style="background-color: {{ $value['color'] }}; width:30px;height:30px;border-radius:50%;display:inline-block"></span></td>
                            <td>{{ $value['price'] }}</td>
                            <td>{{ $value['sale'] <= 0 ? __('text.No Sale') : $value['sale'] }}</td>
                            <td>
                                <button data-toggle="modal" data-target="#updateColorsAndPrices"  wire:click.prevent="updateColor({{ $index }})" class="btn btn-info btn-sm "><i class="mdi mdi-pencil"></i></button>
                                <button wire:click.prevent="deleteColor({{ $index }})" class="btn btn-danger btn-sm "><i class="mdi mdi-delete"></i></button>
                            </td>
                        </tr>
                    @endforeach
                </table>
            @endif

        </div>
        <div class="text-center col-12">
            <button type="submit" class="btn btn-success waves-effect waves-light">{{__('text.Submit')}}</button>
        </div>
    </div>

</form>

@push('script')
    <script>
         $('#my_multi_select1').on('change',function(){
            $('#my_multi_select1').multiSelect('refresh');
            @this.set('taxes_selected',$(this).val());
        })

    </script>
@endpush
