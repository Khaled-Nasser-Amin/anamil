@section('title',__('text.Categories'))
@push('css')
    @livewireStyles
    <link href="{{asset('libs/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet" type="text/css" />
    <style>
        svg{
            width: 20px;
            height: 20px;
        }
    </style>

@endpush
    <div class="content">
        <!-- Start Content-->
        <div class="container-fluid">
            <!-- start page title -->
            <x-admin.general.page-title>
                <li class="breadcrumb-item active">{{__('text.Categories')}}</li>
                <li class="breadcrumb-item active"><a href="{{route('admin.index')}}">{{__('text.Dashboard')}}</a></li>
                <x-slot name="title">
                    <h4 class="page-title">{{__('text.Categories')}}</h4>
                </x-slot>
            </x-admin.general.page-title>


            <!-- End row -->
            @include('admin.partials.success')
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <x-admin.categories.modal-add :categories="$categories" />
                    <x-admin.categories.modal-update :categories="$categories"
                    {{-- :updateCategories="$update_categories" --}}
                     />
                    <!-- Responsive modal -->
                    <button class="btn btn-primary waves-effect waves-light" data-toggle="modal" data-target="#AddNewCategory">
                        {{__('text.Add New Category')}}
                    </button>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-sm-12">
                    <x-admin.categories.table-show :categories="$categories" />
                    {{$categories->links()}}
                </div>
            </div>
        </div>
    </div>

@push('script')
    <script src="{{asset('libs/sweetalert2/sweetalert2.min.js')}}"></script>
    @livewireScripts
    <script>
    window.Livewire.on('addedCategory',()=>{
        $('#AddNewCategory').modal('hide');
    })
    window.Livewire.on('updatedCategory',()=>{
        $('#EditCategory').modal('hide');
    })

    $(document).on('click','#parent',function (){
            let currentCatAr=$('#name_ar').val();
            let currentCatEn=$('#name_en').val();
            let locale="{{app()->getLocale()}}";
            let val;
            if (locale == 'ar'){
               $("#parent option").each(function() {
                    if ($(this).text() == currentCatAr){
                        val = $(this).val();
                    }
                })
            }else{
                $("#parent option").filter(function() {
                    if ($(this).text() == currentCatEn){
                        val =  $(this).val();
                    }
                })
            }
        $("#parent option[value="+val+"]").remove();
    })

    //event fired to livewire called delete
    window.Livewire.on('confirmDelete',function (e) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success mx-2',
                cancelButton: 'btn btn-danger mx-2'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: '{{__("text.Are you sure?")}}',
            text: '{{__("text.You won't be able to revert this!")}}',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: '{{__("text.Yes, delete it!")}}',
            cancelButtonText: '{{__("text.No, cancel!")}}',
            reverseButtons: true
        }).then((result) => {
            if (result.value == true) {
                window.Livewire.emit('delete',e)
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                swalWithBootstrapButtons.fire(
                    '{{__("text.Cancelled")}}',
                    '{{__("text.Your imaginary file is safe :)")}}',
                    'error'
                )
            }
        })

    })

    </script>



@endpush

