@extends('Layouts.main')
@section('links')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .cart-total-container{

    }
    .displaynone{
        display: none;
    }
    .table{
        color: white;
    }
    .table td{
        font-size: 28px;
    }
    .product-image{
        height: 45px;
        border-radius: 10px;
    }
    section{
        background-color: #7fad39;
    }
    #checkout-box{
        padding: 10px;
        border-radius: 4px;
        filter: drop-shadow(0px 2px 3px black);
    }
    @media only screen and (max-width: 600px) {
        .table td , th , * {
                    font-size: 16px;
                }
        .product-image{
            height: 40px;
        }
            }
</style>
@endsection
@section('body')
<!-- Header-->
<header class="bg-prim py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white product-div">
            <table class="table">
                <thead>
                    <tr>
                        <th>Products</th>
                        <th>1 K.G Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>
                            @if(Auth::user())
                                <form action="{{route('delete.cart.content',$cart->id)}}" method="POST">
                            @else {{-- Guest --}}
                                <form action="{{route('delete.cart.content')}}" method="POST">
                            @endif
                            @csrf
                            <button type="submit" id="clear-cart-btn" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Cart</button>
                            </form>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php $counter = 0 ?>
                    @if($cart_items->count() > 0)
                    @foreach($cart_items as $item)
                    <input type="hidden" value="{{$item->product->id}}" id="product{{$counter}}">
                    <tr>
                        <td>
                            <img src="{{asset('storage/'.$item->product->image)}}" alt="" class="product-image">
                            <h5>{{$item->product->name_en}}</h5>
                            @foreach($item->attributeValues as $attr_val)
                               <p style="font-size: 12px !important;"> +
                                {{ $attr_val->printAttributeValuePrice($item->product->id) }}
                                {{ $attr_val->value }} </p>
                            @endforeach
                        </td>
                        <td>
                        @if($item->product->hasDiscount())  {{-- product has discount --}}
                            <?php
                                if($item->product->isPercentDiscount()){
                                    $discount = $item->product->price * $item->product->discount->value / 100;
                                    $new_price = $item->product->price - $discount;
                                }
                                else
                                    $new_price = $item->product->price - $item->product->discount->value;
                            ?>
                        <input type="hidden" id="final-item-price{{$counter}}" value="{{$new_price}}">
                        {{$new_price}}
                        @else
                            <input type="hidden" id="final-item-price{{$counter}}" value="{{$item->product->price}}">
                            {{$item->product->price}}
                        @endif
                        </td>
                        <td>
                            <div class="quantity">
                                <input type="number" class="displaynone" id="min{{$item->product->id}}" value="{{$item->product->min_weight}}">
                                <input type="number" class="displaynone" id="increase{{$item->product->id}}" value="{{$item->product->increase_by}}">
                                <div class="cart-qty">
                                    @if($item->product->isGram())
                                    {{$item->quantity / 1000}} KG
                                    @elseif($item->product->isPiece())
                                    {{$item->quantity}}
                                    @endif
                                    <input type="hidden" id="pro{{$item->product->id}}">
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($item->product->isGram())
                                <input type="hidden" id="single-item-unit{{$counter}}" value="gram">
                                <input type="hidden" id="single-item-total{{$counter}}" value="{{$item->product->hasDiscount() ? ($new_price * $item->quantity) / 1000 : ($item->product->price * $item->quantity) / 1000}}">
                                <p id="h-item-total{{$counter}}">{{$item->product->hasDiscount() ? ($new_price * $item->quantity) / 1000 : ($item->product->price * $item->quantity) / 1000}}</p>
                                +
                                <p id="attr-item-total{{$counter}}">
                                    @php
                                        $attr_total = 0 ;
                                    @endphp
                                    @foreach($item->attributeValues as $attr_val)
                                        @php
                                            $attr_total+=$attr_val->price;
                                        @endphp
                                    @endforeach
                                    {{  $attr_total  }}
                                </p>
                            @else {{-- piece --}}
                                <input type="hidden" id="single-item-unit{{$counter}}" value="piece">
                                <input type="hidden" id="single-item-total{{$counter}}" value="{{$item->product->hasDiscount() ? ($new_price * $item->quantity)  : ($item->product->price * $item->quantity)}}">
                                <p id="h-item-total{{$counter}}">{{$item->product->hasDiscount() ? ($new_price * $item->quantity) : ($item->product->price * $item->quantity)}}</p>
                                +
                                <p id="attr-item-total{{$counter}}">
                                    @php
                                        $attr_total = 0 ;
                                    @endphp
                                    @foreach($item->attributeValues as $attr_val)
                                        @php
                                            if($attr_val->isValue())
                                                $attr_total+=$attr_val->printAttributeValuePrice($item->product->id);
                                            elseif($attr_val->isPercent())
                                                $attr_total+=$attr_val->printAttributeValuePrice($item->product->id) * $item->quantity;
                                        @endphp
                                    @endforeach
                                    {{  $attr_total  }}
                                </p>
                            @endif
                        </td>
                        <td>
                            <form action="{{route('delete.cart.item',$item->id)}}" method="POST">
                                @csrf
                                <button style="border: none; background-color: transparent" class="btn btn-danger" onclick="javascript:this.form.submit();">delete</button>
                            </form>
                        </td>
                    </tr>
                    <?php $counter++; ?>
                    @endforeach
                    <tr>
                        <td>
                            <a href="/" class="btn btn-secondary"><i class="fa-solid fa-basket-shopping"></i> Continue Shopping</a>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @else {{-- Empty cart --}}
                    <tr>
                        <td>
                            Your cart is empty <i class="fa-solid fa-face-frown"></i>
                        </td>
                        <td>
                            <a href="/" class="btn btn-success"><i class="fa-solid fa-basket-shopping"></i> Go Back Shopping</a>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td></td>
                    </tr>
                    @endif
                    <input type="hidden" value="{{$counter}}" id="cart-rows"> {{-- number of rows in cart --}}
                </tbody>
              </table>
        </div>
    </div>
</header>
<section class="bg-second py-2 text-second">
    <div class="container">
        <div class="text-center text-white product-div">
            <div class="row bg-second">
                <div class="col-lg-12 text-second">
                    <div style="background-color: #f5ebeb" id="checkout-box">
                        <h5><b>Cart Total</b></h5>
                        <input type="hidden" value="{{$tax}}" id="tax">
                        <ul>
                            <li>Subtotal <span id="cart-subtotal">{{$cart_total}} AED</span></li>
                            <li>Tax <span id="cart-subtotal">{{$tax}}%</span></li>
                            <li><h3><b>Total <span id="cart-total">{{$cart_grand_total}} AED</span></b></h3></li>
                        </ul>
                        <input type="hidden" id="min-order-val" value="{{$min_order}}">
                        @if(Auth::user())
                            @if($min_order > $cart_grand_total)
                            <h4 id="min-order-warning">You can't submit order by less than {{$min_order}} AED</h4>
                            <div id="proceed-to-checkout-div" class="displaynone">
                            <a href="{{route('checkout')}}" class="primary-btn">PROCEED TO CHECKOUT</a>
                            </div>
                            @else
                            <form action="{{route('checkout')}}" method="GET" id="checkout-form">
                                @csrf
                                <h4 id="min-order-warning" class="displaynone">You can't submit order by less than {{$min_order}} AED</h4>
                                <div id="proceed-to-checkout-div">
                                    {{--
                                <a href="{{route('checkout')}}" class="primary-btn">PROCEED TO CHECKOUT</a>
                                    --}}
                                    {{--
                                <input type="hidden" name="points_applied" id="points-input">
                                --}}
                                <button class="btn btn-success"><i class="fa-solid fa-money-check"></i> Proceed To Checkout</button>
                                </div>
                            </form>
                            @endif
                        @else
                            @if($min_order > $cart_grand_total)
                            <h4 id="min-order-warning">You can't submit order by less than {{$min_order}} AED</h4>
                            <div id="proceed-to-checkout-div" class="displaynone">
                            <button id="proceed-to-checkout"><i class="fa-solid fa-money-check"></i></button>
                            </div>
                            @else
                            <h4 id="min-order-warning" class="displaynone">You can't submit order by less than {{$min_order}} AED</h4>
                            <div id="proceed-to-checkout-div">
                            <button id="proceed-to-checkout"><i class="fa-solid fa-money-check"></i></button>
                            </div>
                            @endif
                        @endif
                    </div>
                </div>

                {{--
                <div class="col-lg-6" style="background-color: red">
                    second box
                </div>
                --}}

            </div>
        </div>
    </div>
</section>
@section('scripts')
<script>
    /*-------------------
		Cart Live Quantity change
	--------------------- */
   // proQty.prepend('<span class="dec qtybtn">-</span>');
   // proQty.append('<span class="inc qtybtn">+</span>');
    $('.cart-qty').on('click', '.cartqtybtn', function () {
        var proQty = $(this);
        //alert($(this).attr('id'));
        //var gold = proQty.attr('id');//.substring(3);
        var gold= proQty.attr('class').substr(0, proQty.attr('class').indexOf('q'));
        var product_id = $('#product'+gold).val();
        var $button = $(this);
        var oldValue = $button.parent().find('input').val();
        var min_order = parseFloat($('#min-order-val').val());
        //alert(gold);
       // alert(product_id);
            //var newVal = parseFloat(oldValue) + 1;
                //var id = $(this).data("id");
                //alert(gold);
                var token = $("meta[name='csrf-token']").attr("content");
                var quantity = $('#quantity-input'+gold).val();
                $.ajax(
                {
                    url: "/update/product/inCart/"+product_id,
                    type: 'POST',
                    data: {
                        "quantity": quantity,
                        "_token": token,
                    },
                    success: function (){
                        var item_unit = $('#single-item-unit'+gold).val();
                        if(item_unit == 'gram')
                            $('#single-item-total'+gold).val((parseFloat($('#final-item-price'+gold).val()) * quantity) / 1000);
                        else if(item_unit == 'piece')
                            $('#single-item-total'+gold).val((parseFloat($('#final-item-price'+gold).val()) * quantity));
                        $('#h-item-total'+gold).text($('#single-item-total'+gold).val());
                        var cart_total = 0 ;
                        var cart_grand_total = 0 ;
                        var tax = $('#tax').val();
                        var cart_rows = $('#cart-rows').val();
                        //alert(cart_rows);
                        for(var i=0 ; i < cart_rows ; i++){
                            cart_total = cart_total + parseFloat($('#single-item-total'+i).val());
                        }
                        var tax_value = tax * cart_total / 100 ;
                        cart_grand_total = cart_total + tax_value ;
                        cart_grand_total = cart_grand_total.toFixed(2);
                        $('#cart-subtotal').text(cart_total + ' AED');
                        $('#cart-total').text(cart_grand_total + ' AED');
                        // check min_order
                        if(min_order > cart_grand_total){    // warning
                            $('#min-order-warning').removeClass('displaynone');
                            $('#proceed-to-checkout-div').addClass('displaynone');
                        }
                        else{
                            $('#min-order-warning').addClass('displaynone');
                            $('#proceed-to-checkout-div').removeClass('displaynone');
                        }
                    }
                });
    });
    $('#proceed-to-checkout').on('click',function(){
        $('#div-checkout').removeClass('col-lg-12').addClass('col-lg-6');
        $('#have-account-form').fadeIn('slow');
    });
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });

    /*
    // take points value into input hidden when submit the form
    $('#checkout-form').submit(function(){
    $('#points-input').val($('points-select').val());
    return true;
    });
    */

    $('#points-select').on('change',function(){
        $('#points-input').val($(this).val());
    });
</script>
@endsection
@endsection
