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
        #order-details-container{
            padding: 10px;
            background-color: #622521;
            color: #fff;
        }
    </style>
@endsection
@section('body')
<section class="bg-second py-2 text-second">
    <div class="container">
            <div class="row bg-second">
                <div class="col-lg-6 text-second">
                    <h4>Billing Details</h4>
                    <form action="{{route('submit.order.as.guest')}}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-lg-8 col-md-6">
                                <div class="row">
                                    <div class="col-lg-6" style="margin: 8px 0px;">
                                        <div>
                                            Fist Name<span>*</span>
                                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-6" style="margin: 8px 0px;">
                                        <div>
                                            Last Name<span>*</span>
                                            <input type="text" name="last_name" value="{{ old('last_name') }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div style="margin: 8px 0px;">
                                    @if($errors->has('address2'))
                                    <p style="color: red; font-weight:bold;">
                                        {{ $errors->first('address2') }}
                                    </p>
                                    @endif
                                    Address
                                <input type="text" name="address2" value="{{ old('address2') }}" id="address2" class="form-control">
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
                                            <input type="text" name="phone" placeholder="0500000000" value="{{ old('phone') }}" class="form-control">
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
                                            <input type="text" name="email" value="{{ old('email') }}" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div style="margin: 8px 0px;">
                                    <p>Order notes</p>
                                    <input type="text" name="customer_note"
                                        placeholder="Notes about your order, e.g. special notes for delivery." value="{{ old('customer_note') }}" class="form-control">
                                </div>
                            </div>

                        </div>
                </div>
                <div class="col-lg-3" id="order-details-container">
                        <h4>Your Order {{$date}}</h4>
                        <div class="checkout__order__products">Products :</div>
                        <ul>
                            @foreach($cart_items as $item)
                                @if($item->hasDiscount())  {{-- product has discount --}}
                                    <?php
                                        if($item->isPercentDiscount()){
                                            $discount = $item->price * $item->discount->value / 100;
                                            $new_price = $item->price - $discount;
                                        }
                                        else
                                            $new_price = $item->price - $item->discount->value;
                                    ?>
                                <li> {{$item->name_en}}
                                     @if($item->unit == 'gram')
                                     <b style="color: wheat">{{$item->quantity/1000}} K.G </b> <span>{{$new_price * $item->quantity / 1000}} AED</span>
                                     @else
                                     <b style="color: wheat">{{$item->quantity}}  </b> <span>{{$new_price * $item->quantity}} AED</span>
                                     @endif
                                    </li>
                                @else
                                <li> {{$item->name_en}}
                                    @if($item->unit == 'gram')
                                     <b style="color: wheat">{{$item->quantity/1000}} K.G </b> <span>{{$item->price * $item->quantity / 1000}} AED</span>
                                    @else
                                    <b style="color: wheat">{{$item->quantity}} </b> <span>{{$item->price * $item->quantity}} AED</span>
                                    @endif
                                    <span></span>
                                </li>
                                @endif
                                {{-- attributes --}}
                                @foreach($item->attributes as $attr_val) {{-- attr_val is array --}}
                                    +
                                    @php
                                        $attr_total = 0 ;
                                    // $percent = $attr_val['price'];
                                        //$attr_cost = $item->pri
                                        $attr_val_obj = App\Models\AttributeValue::find($attr_val['id']);
                                        if($attr_val_obj->isValue())
                                            $attr_total+=$attr_val_obj->printAttributeValuePrice($item->id);
                                        elseif($attr_val_obj->isPercent())
                                            $attr_total+=$attr_val_obj->printAttributeValuePrice($item->id) * $item->quantity
                                    @endphp
                                    {{  $attr_total  }}
                                @endforeach
                            @endforeach
                        </ul>
                        @if(Session::get('points_applied') && Session::get('total_before_discount'))
                        <div><span style="text-decoration: line-through">{{Session::get('total_before_discount')}}</span> <span style="float: right" class="badge badge-info"> saved {{Session::get('discount')}} AED </span></div>
                        <div class="checkout__order__subtotal">Subtotal <span>{{$total_order_price}} AED</span></div>
                        @else
                        <div class="checkout__order__subtotal">Subtotal <span>{{$total_order_price}} AED</span></div>
                        @endif
                        <div class="checkout__order__total">Tax <span>{{$tax}}%</span></div>
                        <div style="color: wheat" class="checkout__order__total"><b>Total <span>{{$order_grand_total}} AED</span></b></div>
                        <div class="checkout__input__checkbox">
                            <label for="cash">
                                Cash Payment
                                <input type="radio" name="payment_method" id="cash" value="cash" checked>
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div class="checkout__input__checkbox">
                            <label for="online">
                                Card
                                <input type="radio" name="payment_method" value="other" id="online">
                                <span class="checkmark"></span>
                            </label>
                        </div>
                        <div>
                                @if(is_numeric($hours_remaining_to_deliver))
                                    <h4 style="color: wheat">
                                    You will receive your order in about
                                    {{$hours_remaining_to_deliver}} Hours
                                    </h4>
                                @else
                                    <h4 style="color: #e4eef0">
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
@endsection
@endsection
