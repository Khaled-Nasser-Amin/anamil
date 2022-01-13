@extends('admin.layouts.appLogged')
@section('title','Categories')
@push('css')
    @livewireStyles
    <link href="{{asset('libs/datatables/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('libs/datatables/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
        <style>
        svg{
            width: 20px;
            height: 20px;
        }

    </style>
@endpush
@section('content')
    <div class="content">

        <!-- Start Content-->
        <div class="container-fluid">

            <x-admin.general.page-title>
                <li class="breadcrumb-item"><a href="/admin/categories">{{__('text.Categories')}} </a></li>
                <li class="breadcrumb-item active"> {{__('text.Details')}}</li>
                <x-slot name="title">
                    <h4 class="page-title">{{__('text.Category Details')}}</h4>
                </x-slot>
            </x-admin.general.page-title>



            <!-- end page title -->

            <div class="property-detail-wrapper">
                <div class="row">

                    <x-admin.categories.category.category-details :category="$category" />

                    @livewire('admin.products-management.categories.category.products-table-for-category',["category" => $category->id])

                </div>
                <!-- end row -->
            </div>
            <!-- end property-detail-wrapper -->

        </div>
        <!-- end container-fluid -->

    </div>
@endsection

@push('script')
    @livewireScripts

<!-- Datatable plugin js -->
<script src="{{asset('libs/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('libs/datatables/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('libs/datatables/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('libs/datatables/buttons.bootstrap4.min.js')}}"></script>
<script src="{{asset('libs/datatables/buttons.print.min.js')}}"></script>
<!-- Datatables init -->

<script>

    $("#datatable-buttons").DataTable(
            {
                dom:"Bfrtip",
                deferRender:    true,
                scrollY:        800,
                scrollCollapse: true,
                scroller:       true,
                buttons:[
                     {extend:"print", autoPrint: false,text:"@lang('text.Print')",className:"btn-sm",exportOptions: {stripHtml : false,columns: [ 0,1, 2,3,4,5,6,7,8 ]},messageTop:'<h2 class="text-center">@lang('text.Sold Pieces for category') <span class="text-danger" style="font-weight: bold">{{app()->getLocale() =='ar' ? $category->name_ar:$category->name_en}}</span> @lang('text.during') ({{ session()->get("current_month") ?? null }} / {{ session()->get("current_year")??null }})</h2>'}
                    ],
                responsive:0
            })
</script>

@endpush
