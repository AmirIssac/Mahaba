@extends('Layouts.main')
@section('links')
<script src="https://api.mqcdn.com/sdk/mapquest-js/v1.3.2/mapquest.js"></script>
<link type="text/css" rel="stylesheet" href="https://api.mqcdn.com/sdk/mapquest-js/v1.3.2/mapquest.css"/>
    <style>
        .taken{
            background-color: #7fad39;
            color: #fff;
        }
        .untaken{
            background-color: #f44336;
            color: #000;
        }
        #complete-profile:hover{
            color: #7fad39;
            font-weight: bold;
        }
    </style>
@endsection
@section('body')
<section class="bg-second py-2 text-second">
    <div class="container">
            <div class="row bg-second">
                <div class="col-lg-6 text-second">
                    <h4>Billing Details</h4>
                    <form action="{{route('submit.order')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-8 col-md-6">
                                <div class="row">
                                    <div class="col-lg-6" style="margin: 8px 0px;">
                                        <div>
                                            Fist Name<span>*</span>
                                            <input type="text" name="first_name" value="{{$profile->first_name}}">
                                        </div>
                                    </div>
                                    <div class="col-lg-6" style="margin: 8px 0px;">
                                        <div>
                                            Last Name<span>*</span>
                                            <input type="text" name="last_name" value="{{$profile->last_name}}">
                                        </div>
                                    </div>
                                </div>
                                <div style="margin: 8px 0px;">
                                @if($profile->address_address)
                                    Address
                                    <input type="text" class="checkout__input__add taken" value="{{$profile->address_address}}" name="address1" id="address1" readonly>
                                    <label>
                                        Ship to a different address ?
                                    </label>
                                    <input type="text" placeholder="type address here if it's different from your main profile address" name="address2" id="address2">
                                @else
                                    Address
                                <input type="text" name="address2" id="address2">
                                @endif
                                </div>
                                <div class="row">
                                    <div class="col-lg-6" style="margin: 8px 0px;">
                                        <div>
                                            @if($errors->has('phone'))
                                            <p style="color: red; font-weight:bold;">Phone<span>*</span>
                                                {{ $errors->first('phone') }}
                                            </p>
                                            @else
                                            Phone<span>*</span>
                                            @endif
                                            <?php
                                                $phone = substr($user->profile->phone, 5);
                                                $phone = '0'.$phone;
                                             ?>
                                            <input type="text" name="phone" value="{{$phone}}" placeholder="0500000000">

                                        </div>
                                    </div>
                                    <div class="col-lg-6" style="margin: 8px 0px;">
                                        <div>
                                            @if($errors->has('email'))
                                            <p style="color: red; font-weight:bold;">Email<span>*</span>
                                                {{ $errors->first('email') }}
                                            </p>
                                            @else
                                            Email<span>*</span>
                                            @endif
                                            <input type="text" name="email" value="{{$user->email}}" required>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin: 8px 0px;">
                                    <p>Order notes</p>
                                    <input type="text" name="customer_note"
                                        placeholder="Notes about your order, e.g. special notes for delivery.">
                                </div>
                            </div>


                        </div>
                </div>
                <div class="col-lg-6 text-second">
                        <h4>Your Order {{$date}}</h4>
                        <div class="checkout__order__products">Products <span>Total</span></div>
                        <ul>
                            @foreach($cart_items as $item)
                                @if($item->product->hasDiscount())  {{-- product has discount --}}
                                    <?php
                                        if($item->product->isPercentDiscount()){
                                            $discount = $item->product->price * $item->product->discount->value / 100;
                                            $new_price = $item->product->price - $discount;
                                        }
                                        else
                                            $new_price = $item->product->price - $item->product->discount->value;
                                    ?>
                                <li> {{$item->product->name_en}}
                                     @if($item->product->unit == 'gram')
                                     <b style="color: #7fad39">{{$item->quantity/1000}} K.G </b> <span>{{$new_price * $item->quantity / 1000}} AED</span>
                                     @else
                                     <b style="color: #7fad39">{{$item->quantity}}  </b> <span>{{$new_price * $item->quantity}} AED</span>
                                     @endif
                                    </li>
                                @else
                                <li> {{$item->product->name_en}}
                                    @if($item->product->unit == 'gram')
                                     <b style="color: #7fad39">{{$item->quantity/1000}} K.G </b> <span>{{$item->product->price * $item->quantity / 1000}} AED</span>
                                    @else
                                    <b style="color: #7fad39">{{$item->quantity}} </b> <span>{{$item->product->price * $item->quantity}} AED</span>
                                    @endif
                                </li>
                                @endif
                            @endforeach
                        </ul>
                        @if(Session::get('points_applied') && Session::get('total_before_discount'))
                        <div><span style="text-decoration: line-through">{{Session::get('total_before_discount')}}</span> <span style="float: right" class="badge badge-info"> saved {{Session::get('discount')}} AED </span></div>
                        <div class="checkout__order__subtotal">Subtotal <span>{{$total_order_price}} AED</span></div>
                        @else
                        <div class="checkout__order__subtotal">Subtotal <span>{{$total_order_price}} AED</span></div>
                        @endif
                        <div class="checkout__order__total">Tax <span>{{$tax}}%</span></div>
                        <?php $tax_value = $tax * $total_order_price / 100 ;
                              $order_grand_total = $total_order_price + $tax_value ;
                              $order_grand_total = number_format((float)$order_grand_total, 2, '.', '');
                        ?>
                        <div class="checkout__order__total">Total <span>{{$order_grand_total}} AED</span></div>
                        <div class="checkout__input__checkbox">
                            <label for="cash">
                                Cash Payment
                                <input type="radio" name="payment_method" id="cash" value="cash" checked>
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="checkout__input__checkbox">
                            <label for="online">
                                Other
                                <input type="radio" name="payment_method" value="other" id="online">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div>
                                @if(is_numeric($hours_remaining_to_deliver))
                                    <h4 style="color: #7fad39">
                                    You will receive your order in about
                                    {{$hours_remaining_to_deliver}} Hours
                                    </h4>
                                @else
                                    <h4 style="color: #f44336">
                                    You will receive your order tomorrow
                                    </h4>
                                @endif
                            <button type="submit" class="btn btn-success"><i class="fa-solid fa-check"></i> PLACE ORDER</button>
                    </div>

                </form>

                </div>
            </div>
    </div>
</section>
<!-- Checkout Section End -->
@section('scripts')
    <script>
    $('#address2').on('keyup paste',function(){
        if( $(this).val() != '' ){
            $('#address1').removeClass('taken').addClass('untaken');
        }
        else{
            $('#address1').removeClass('untaken').addClass('taken');
        }
    })
    </script>
    {{-- if profile not completed so we get the customer address automatically --}}
    @if(!$profile->address_address)
            <script>
                $(document).ready(function(){

                // check for Geolocation support
                if (navigator.geolocation) {
                console.log('Geolocation is supported!');
                }
                else {
                console.log('Geolocation is not supported for this Browser/OS.');
                }

                var startPos;
                var  MQ, map, directions, routes = new Array();
                L.mapquest.key = 'GPUOqQDwMnMxLWNY1wVUe96aw4ihyVr9';

                    var geoSuccess = function(position) {    // find your current position and load the map
                        startPos = position;
                        var latitude = startPos.coords.latitude;
                        var longitude = startPos.coords.longitude;

                        //$('#latitude').val(latitude);
                        //$('#longitude').val(longitude);
                        // reverse geocoding (request api)
                        function httpGet(theUrl)
                        {
                            var xmlHttp = new XMLHttpRequest();
                            xmlHttp.open( "GET", theUrl, false ); // false for synchronous request
                            xmlHttp.send( null );
                            return xmlHttp.responseText;
                        }

                        console.log(httpGet('http://www.mapquestapi.com/geocoding/v1/reverse?key=GPUOqQDwMnMxLWNY1wVUe96aw4ihyVr9&location='+latitude+','+longitude+'&includeRoadMetadata=true&includeNearestIntersection=true'));
                        var json_location = httpGet('http://www.mapquestapi.com/geocoding/v1/reverse?key=GPUOqQDwMnMxLWNY1wVUe96aw4ihyVr9&location='+latitude+','+longitude+'&includeRoadMetadata=true&includeNearestIntersection=true');
                        var location_info_object = JSON.parse(json_location);  // object
                        var address = "";
                        //address = address.concat(location_info_object.locations[0].adminArea1)
                        //$('#address').val(address);
                        address = address.concat(location_info_object.results[0].locations[0].street.concat(' , '+location_info_object.results[0].locations[0].adminArea5).concat(' , '+location_info_object.results[0].locations[0].adminArea3).concat(' , '+location_info_object.results[0].locations[0].adminArea1));
                        $('#address2').val(address);
                    };
                    navigator.geolocation.getCurrentPosition(geoSuccess);
                    /*
                    alert('final lat is ' + latitude);
                    */

                // 'map' refers to a <div> element with the ID map

                });
                </script>
        @endif
@endsection
@endsection
