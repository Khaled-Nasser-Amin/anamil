<div wire:ignore.self class="modal fade" id="colorsAndPrices" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{__('text.Color')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div class="addSelectProduct">

                    <div class="form-group row justify-content-between" >
                        <div class="form-group mb-4 col-sm-6" >
                            <label>{{__('text.Gallery')}}</label>
                            <input type="file" wire:model="groupImage" class="form-control"  multiple data-height="210" />
                            <x-general.input-error for="groupImage" />

                        </div>
                        <div class="col-md-6 col-sm-6">
                            <label for="color">{{__('text.Color')}}</label><br>
                            <input type="color" class="form-control"  wire:model='color' id="color" ><br>
                            <x-general.input-error for="color" />
                        </div>

                        <div class=" col-sm-6">
                            <label for="price">{{__('text.Price')}}</label><br>
                            <input type="number" wire:model='price' class="form-control" id="price" autocomplete="none"><br>
                            <x-general.input-error for="price" />
                        </div>
                        <div class="col-sm-6">
                            <label for="sale">{{__('text.Sale')}}</label><br>
                            <input type="number" wire:model='sale' class="form-control" id="sale" autocomplete="none"><br>
                            <x-general.input-error for="sale" />
                        </div>

                        {{-- size modal --}}
                        <div class="col-12 row justify-content-between mx-0">

                            <h5 class="px-3">{{__('text.Sizes')}}</h5>

                            <button data-toggle="modal" data-target="#sizeAndStock0" type="button" class="btn btn-success btn_AddMore btn-sm">
                                {{__('text.Add Size')}}
                            </button>

                            <x-admin.products.modal-size-and-stock :index="0"/>
                            <x-admin.products.modal-update-size-and-stock :index="0" />

                        </div>
                        <div class="px-2">
                            <x-general.input-error for="sizes" />

                        </div>

                        <div class="col-12 " style="overflow-y: scroll">
                            @if (collect($sizes)->count() > 0)
                                 <table class="table table-borderd text-center">
                                <tr>
                                    <th>@lang('text.Size')</th>
                                    <th>@lang('text.Stock')</th>
                                    <th>@lang('text.Action')</th>
                                </tr>
                                @foreach ($sizes as $index => $value)
                                    <tr>
                                        <td>{{ $value['size'] }}</td>
                                        <td>{{ $value['stock'] }}</td>
                                        <td>
                                            <button data-toggle="modal" data-target="#updateSizeAndStock0" type="button" wire:click.prevent="updateSize({{ $index }})" class="btn btn-info btn-sm "><i class="mdi mdi-pencil"></i></button>
                                            <button wire:click.prevent="deleteSize({{ $index }})" class="btn btn-danger btn-sm "><i class="mdi mdi-delete"></i></button>
                                        </td>
                                    </tr>
                                @endforeach

                            </table>
                            @endif

                        </div>




                        <div class="col-12 row justify-content-center">
                            <button wire:click.prevent="addColor" class="btn btn-primary">@lang('text.Save')</button>
                        </div>
                        {{-- <div class="col-sm-12 row justify-content-center align-items-center">
                            <button type="button" wire:click="deleteProduct({{$index}})" class="btn btn-danger btn_remove">{{__("text.Delete")}}</button>
                        </div> --}}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

