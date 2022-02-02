@forelse($products as $product)
    <div class="col-sm-12 col-lg-4 col-md-6">
        <div class="news-grid">
            <div class="news-grid-image">
                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active" >
                        @if ($product->type == 'group')
                            <span class="badge badge-info" style="position: absolute;right:0">{{__('text.Group')}}</span>
                            @if ($product->sale > 0)
                                <span class="badge badge-danger" style="position: absolute;">{{__('text.Price after sale')}}</span>

                            @endif
                        @endif
                            <img src="{{$product->image}}" class="d-block w-100" alt="..." >
                        </div>

                        @foreach($product->images as $image)
                            <div class="carousel-item">
                                @if ($product->type == 'group')
                                <span class="badge badge-info" style="position: absolute;right:0">{{__('text.Group')}}</span>
                                    @if ($product->sale > 0)
                                        <span class="badge badge-danger" style="position: absolute;">{{__('text.Price after sale')}}</span>

                                    @endif
                                @endif
                                <img src="{{$image->name}}" class="img-fluid d-block w-100 h-100" alt="...">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="news-grid-box"  style="border-radius:50% ">
                    <div class="dropdown float-right">
                        <a href="#" class="dropdown-toggle card-drop arrow-none text-white" data-toggle="dropdown" aria-expanded="false">
                            <div><i class="mdi mdi-dots-horizontal h4 m-0 text-muted"></i></div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @can('update',$product)
                                <a class="dropdown-item" href="/admin/products-update/{{$product->id}}-{{$product->slug}}">{{__('text.Edit')}}</a>
                            @endcan
                            <a class="dropdown-item" href="/admin/product-details/{{$product->id}}-{{$product->slug}}">{{__('text.Show')}}</a>
                            <button class="dropdown-item" type="button" wire:click="confirmDelete({{$product->id}})">{{__('text.Delete')}}</button>
                        </div>
                    </div>
                </div>
            </div>




            <div class="news-grid-txt">
                @can('isAdmin')
                    <div class="row justify-content-between align-items-center">
                        <h2>{{ $product->user->store_name}}</h2>
                        <a href="{{$product->user->image}}" target="_blank"><img src="{{$product->user->image}}" class="rounded-circle" style="width: 50px;height: 50px" alt="user-image"></a>

                    </div>
                @endcan
                <div class="row justify-content-between align-items-center">
                    <h2>{{app()->getLocale() == 'ar' ?$product->name_ar:$product->name_en}}</h2>
                    <span><i class="mdi mdi-calendar" aria-hidden="true"></i> {{date('M d Y',strtotime($product->created_at))}}</span>
                </div>
                @can('update',$product)
                <div class="row justify-content-between align-items-center">
                    <h2>{{ $product->isActive == 1 ? __('text.Available For Sale') : __('text.Not Available For Sale')}}</h2>
                    <input wire:click.prevent="updateStatus({{ $product->id }})" type="checkbox" {{ $product->isActive == 1 ? "checked" : '' }}>
                </div>
                @endcan
                @if($product->type == 'group')
                <h5>@lang('text.Products')</h5>
                <div class="table-responsive col" style="height: auto!important;max-height:80px;overflow-y:scroll">
                    <table class="table table-sm table-borderless mb-0">
                        <tbody>
                        @forelse ($product->child_products()->withTrashed()->get() as $child)
                        <tr>
                            <th style="font-size: larger">
                                {{ app()->getLocale() == 'ar' ? $child->name_ar : $child->name_en }}

                                @if($child->deleted_at)
                                    <i class="mdi mdi-alert-decagram text-danger"></i>
                                @endif

                            </th>
                            <td>{{ $child->pivot->quantity }}</td>

                        </tr>

                        @empty
                        @endforelse
                        </tbody>
                    </table>
                </div>
                @endif

                <ul>
                    <li><br><span class="text-pink">{{__('text.Price')}}</span>
                        @if (!$product->sale)
                        <span class="text-pink"> {{__('text.Price')}} </span>| <span class="text-muted">{{$product->price}} @lang('text.SAR')</span>
                        @else
                            <span class="text-pink"> {{__('text.Price')}} </span>| <span class="text-muted"><del>{{$product->price}}</del> {{$product->sale}} @lang('text.SAR')</span>
                        @endif                    </li>
                    <li><br><span class="text-pink">{{__('text.Stock')}}</span>
                        |<span class="text-muted">{{$product->stock}}</span>
                    </li>
                    @can('isAdmin')
                    <li><br><span class="text-pink">{{__('text.Category Name')}}</span>
                    |<span class="text-pink">
                        <a
                        href="/admin/category/{{$product->category()->withTrashed()->first()->id}}-{{$product->category()->withTrashed()->first()->slug}}"

                          >{{app()->getLocale() == 'ar'? $product->category()->withTrashed()->first()->name_ar : $product->category()->withTrashed()->first()->name_en}}</a></span>
                    </li>
                    @endcan
                </ul>

                @if($product->description_ar || $product->description_en)
                    <span class="text-pink">{{__('text.Description')}}</span>
                    <div class="slimscroll description_scroll mb-0">{{app()->getLocale() == 'ar' ?$product->description_ar:$product->description_en}}</div>
                @endif



                @if($product->stock > 0 && !checkCollectionActive($product))
                    @can('isAdmin')
                        <button id="changeFeatured" wire:click.prevent="updateFeatured({{$product->id}})" class="btn btn-{{$product->featured == 0 ? "secondary":"primary"}} mt-3 btn-rounded btn-bordered waves-effect width-md waves-light text-white d-block mx-auto w-75">{{__('text.Featured')}} <i class="far fa-star"></i></button>
                    @endcan
                @endif
                @if (checkCollectionActive($product))
                <div class="alert alert-danger">@lang('text.In active collection becouse there are some data missing')</div>

                @endif
                @if ($product->stock <= 0)
                <div class="alert alert-danger">@lang('text.Out of Stock')</div>

                @endif

            </div>




        </div>
    </div>
@empty
    <h1 class='text-center flex-grow-1'>{{__('text.No Data Yet')}}</h1>
@endforelse
