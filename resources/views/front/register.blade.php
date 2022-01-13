@section('title',__('text.Register'))
@push('css')
    @livewireStyles
    <link rel="stylesheet" href="{{asset('css/toast.style.min.css')}}">

    <style>
        body {
        background: #f782a9;
        background: -webkit-linear-gradient(to right, #f782a9, #cecccd);
        background: linear-gradient(to right, #f782a9, #cecccd)
        }
        .gm-style-iw-d{
            color:blue;
        }
        #pac-input{
            color: #ffffff!important;
            border-radius:2em!important;
            border:none!important;
            height: 35px;
            width:30%;
            background-color: rgba(10.1, 2.07, 10.1, 0.3)!important;
            text-indent: 10px;
        }

        ::placeholder {
            color: #fff;
            opacity: 1; /* Firefox */
        }


    </style>
@endpush
    <div class="account-pages" style="margin: 150px 0 0 0 ;" >
        <div class="container ">
            <div class="row justify-content-center ">
                <div class="col-md-10 col-lg-8 col-xl-8" >

                    @if (!session()->has('activeCodeField'))
                        <div class="card" >
                            <x-general.authentication-card-logo />
                            <div class="card-body text-white bg-dark">
                                <form >
                                    <div class="row mb-4">
                                        <div class="form-group  col-sm-12" wire:ignore>
                                            <label for="map">{{__('text.Your Location')}}*</label>
                                        </div>
                                        <div wire:ignore>
                                            <input  id="pac-input" class="controls" type="text" placeholder="@lang('text.Search')..." />
                                        </div>

                                        <div wire:ignore class="form-group  col-sm-12"  id="map" style="height:400px">
                                        </div>

                                        <div class="form-group  col-sm-12">
                                            <x-general.input-error for="geoLocation" />
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="store_name">{{__('text.Store Name')}}*</label>
                                            <input id="store_name" type="text"  wire:model.defer="store_name" class="form-control {{$errors->has('store_name') ? 'is-invalid' : ''}}">
                                            <x-general.input-error for="store_name" />
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="name">{{__('text.Name')}}*</label>
                                            <input type="text" id="name" wire:model.defer="name" class="form-control {{$errors->has('name') ? 'is-invalid' : ''}}" placeholder="{{__('text.Full Name')}}*">
                                            <x-general.input-error for="name" />
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="phone">{{__('text.Phone Number')}}*</label>
                                            <input id="phone" type="text" wire:model.defer="phone"  class="form-control {{$errors->has('phone') ? 'is-invalid' : ''}}">
                                            <x-general.input-error for="phone" />
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="whatsapp">{{__('text.WhatsApp')}}*</label>
                                            <input id="whatsapp" type="text" wire:model.defer="whatsapp"  class="form-control {{$errors->has('whatsapp') ? 'is-invalid' : ''}}">
                                            <x-general.input-error for="whatsapp" />
                                        </div>
                                        <div class="form-group col-md-6 col-sm-12">
                                            <label for="email">{{__('text.Email')}}*</label>
                                            <input id="email" type="text" wire:model.defer="email"  class="form-control {{$errors->has('email') ? 'is-invalid' : ''}}">
                                            <x-general.input-error for="email" />
                                        </div>

                                        <div class="form-group  col-md-6 col-sm-12">
                                            <label for="email">{{__('text.Select Category')}}*</label>
                                            <select wire:model.defer="category" class="form-control">
                                                <option></option>

                                                @forelse ($categories as $category )
                                                    <option value="{{ $category->id }}">{{ app()->getLocale() == 'ar' ? $category->name_ar : $category->name_en }}</option>

                                                @empty
                                                <option disabled>@lang('text.No Categories available Yet')</option>

                                                @endforelse


                                            </select>
                                            <x-general.input-error for="category" />

                                        </div>


                                        <div class="row w-100 mx-0 px-0">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="pass">{{__('text.Password')}}*</label>
                                                <input id="pass" type="password" wire:model="password" class="form-control {{$errors->has('password') ? 'is-invalid' : ''}}"  placeholder="{{__('text.Password')}}">
                                                <x-general.input-error for="password" />
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="cfpass">{{__('text.Confirm Password')}}*</label>
                                                <input id="cfpass" type="password" wire:model="password_confirmation"   class="form-control {{$errors->has('password_confirmation') ? 'is-invalid' : ''}}" placeholder="{{__('text.Confirm Password')}}">
                                                <x-general.input-error for="password_confirmation" />
                                            </div>
                                        </div>

                                        <div class="form-group  col-md-6 col-sm-12">
                                            <label for="email">{{__('text.Location')}}*</label>
                                            <select wire:model="location" class="form-control">
                                                <option></option>

                                                @forelse ($cities as $city )
                                                    <option value="{{ $city}}">{{ $city}}</option>

                                                @empty

                                                @endforelse


                                            </select>
                                            <x-general.input-error for="location" />

                                        </div>

                                    </div>

                                    <div class="form-group account-btn text-center mt-2">
                                        <div class="col-12">
                                            <button wire:click.prevent="store" class="btn width-md btn-bordered btn-danger waves-effect waves-light" type="submit"  wire:loading.attr="disabled" >{{__('text.Register')}}</button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                            <!-- end card-body -->
                        </div>
                        <!-- end card -->
                    @else
                    <div class="card" >
                        <x-general.authentication-card-logo />
                        <div class="card-body text-white bg-dark">
                            <form   >
                                <div class="row form-group">
                                    <div class=" w-100 row justify-content-between align-items-center">
                                        <h3 class="form-title text-white px-3">{{__('text.Account Confirmation')}}</h3>
                                        <i class="hover-white-text mdi mdi-close-circle" wire:click.prevent="cancel" style="color:#e6508a;"></i>
                                    </div>
                                    <h4 class="form-subtitle text-white w-100 px-3">{{ __("text.We have sent a verification code to your email")}} : {{session()->has('data.email') ? session()->get('data.email') : ''}}</h4>

                                    <p  class=" px-3" wire:ignore>
                                        <span id="text" class="font-14">{{__('text.Code will expire after : ')}}</span>
                                        <span id="timerCount" class="font-weight-bold" style="color: #e6508a;"></span>
                                    </p>
                                    <script>
                                        let time={{session()->get('time')}};
                                            time=parseInt(time)+(5*60);
                                            let x= setInterval(function (){
                                                var now = new Date().getTime()/1000;
                                                var distance = time - now;
                                                var minutes = Math.floor((distance % ( 60 * 60)) / ( 60));
                                                var seconds = Math.floor((distance % (60)));
                                                $('#timerCount').html(minutes+':'+seconds)
                                                if (distance < 0) {
                                                    clearInterval(x);
                                                    $('#timerCount').empty()
                                                    $('#text').addClass(['text-danger','font-weight-bold'])
                                                    $('#text').html("{{__('text.CODE EXPIRED')}}");
                                                }
                                            },1000)

                                        window.addEventListener('refreshCode',function (e){
                                            $('#text').removeClass(['text-danger','font-weight-bold'])
                                            $('#text').html("{{__('text.Code will expire after : ')}}")
                                            time=parseInt(e.detail)+(5*60)
                                            let x= setInterval(function (){
                                                var now = new Date().getTime()/1000;
                                                var distance = time - now;
                                                var minutes = Math.floor((distance % ( 60 * 60)) / ( 60));
                                                var seconds = Math.floor((distance % (60)));
                                                $('#timerCount').html(minutes+':'+seconds)
                                                if (distance < 0) {
                                                    clearInterval(x);
                                                    $('#timerCount').empty()
                                                    $('#text').addClass(['text-danger','font-weight-bold'])
                                                    $('#text').html("{{__('text.CODE EXPIRED')}}");

                                                }
                                            },1000)

                                        })

                                    </script>
                                </div>
                                <div class="form-group">
                                    <label for="frm-reg-lname">{{__('text.Code')}}*</label>
                                    <div class="row px-2">
                                        <input type="text" wire:model="code" class="col-8 form-control  {{$errors->has('code') ? 'is-invalid' : ''}}" placeholder="######">
                                        <a href="#" wire:click.prevent="resend" class="hover-dark-text col-4 row justify-content-center align-items-center" style="color: #e6508a;;">{{__('text.Resend?')}}</a>
                                    </div>

                                    <x-general.input-error for="code" />
                                </div>
                                <div class="form-group account-btn text-center mt-2">
                                    <div class="col-12">
                                        <button wire:click.prevent="create" class="btn width-md btn-bordered btn-danger waves-effect waves-light" type="submit"  wire:loading.attr="disabled" >{{__('text.Register')}}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    @endif

                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
@push('js')


    <script src="{{asset('js/toast.script.js')}}"></script>

    @livewireScripts

    <script>
        window.addEventListener('success',e=>{
            $.Toast(e.detail,"",'success',{
                stack: false,
                position_class: "toast-top-center",
                rtl: {{app()->getLocale()=='ar' ? "true" : 'false'}}
            });
        })
        window.addEventListener('danger',e=>{
            $.Toast(e.detail,"",'error',{
                stack: false,
                position_class: "toast-top-center",
                rtl: {{app()->getLocale()=='ar' ? "true" : 'false'}}
            });
        })




        let map,input,searchBox;
            let markers = [];


            function initAutocomplete() {
                map = new google.maps.Map(document.getElementById("map"), {
                    center: { lat: 24.81620056234382 , lng: 40.60623492394177 },
                    zoom: 6,
                    mapTypeId: "roadmap",
                    mapTypeControl: false,
                    fullscreenControl: false
                });



                // This event listener will call addMarker() when the map is clicked.
                map.addListener("click", (event) => {
                    deleteMarkers();
                    addMarker(event.latLng);
                });


                // Create the search box and link it to the UI element.
                createSearchBox();


                // Bias the SearchBox results towards current map's viewport.
                map.addListener("bounds_changed", () => {
                    searchBox.setBounds(map.getBounds());
                });
                // Listen for the event fired when the user selects a prediction and retrieve
                // more details for that place.
                searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();
                places.forEach((place) => {
                if (!place.geometry || !place.geometry.location) {
                    console.log("Returned place contains no geometry");
                    return;
                }
                // Create a marker for each place.
                addMarker(place.geometry.location);

                if (place.geometry.viewport) {
                    // Only geocodes have viewport.
                    bounds.union(place.geometry.viewport);
                } else {
                    bounds.extend(place.geometry.location);
                }
                });
                map.fitBounds(bounds);
                });
            }






            // Adds a marker to the map and push to the array.
            function addMarker(position) {
                const marker = new google.maps.Marker({
                    position,
                    map,
                });
                markers.push(marker);
                @this.set('geoLocation',marker.getPosition().lat()+","+marker.getPosition().lng())
            }

            // Sets the map on all markers in the array.
            function setMapOnAll(map) {
                for (let i = 0; i < markers.length; i++) {
                    markers[i].setMap(map);
                }
            }

            // Removes the markers from the map, but keeps them in the array.
            function hideMarkers() {
                setMapOnAll(null);
            }


            // Deletes all markers in the array by removing references to them.
            function deleteMarkers() {
                hideMarkers();
                markers = [];
            }

            // Create the search box and link it to the UI element.
            function createSearchBox(){
                input = document.getElementById("pac-input");
                searchBox = new google.maps.places.SearchBox(input);
                map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
            }

        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBmX3cxNy7VH9WLrzoh6FLGkjtZ0g3tLSE&callback=initAutocomplete&libraries=places&v=weekly" async ></script>


@endpush



