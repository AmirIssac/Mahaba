@extends('Layouts.main')
@section('links')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    #clear-cart-btn{
        background-color: white;
        border: 1px solid #2b201e;
        border-radius: 5px;
        font-size: 13px;
        font-weight: bold;
        color: black;
    }
    #clear-cart-btn:hover{
        background-color: #2b201e;
        border: 1px solid #2b201e;
        border-radius: 5px;
        font-size: 13px;
        color: white;
    }
    #proceed-to-checkout{
        border: 1px solid white;
    }
    #login-btn{
        border: 1px solid white;
    }
    #checkout-box , #have-account-form{
        filter: drop-shadow(3px 3px 3px #7fad39);
    }
    #min-order-warning{
        color: #dd2222;
        font-weight: bold;
    }
    .displaynone{
        display: none;
    }
    #question-mark{
        border-radius : 50%;
        padding: 5px;
    }
    #question-mark:hover{
        cursor: pointer;
    }
</style>
@endsection
@section('content')
    <!-- Shoping Cart Section Begin -->
    <section style="margin-top:-100px;" class="shoping-cart spad">
        <div class="container">
            @if(Auth::user())
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th class="shoping__product">Products</th>
                                    <th>1 K.G Price Piece Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>
                                        @if(Auth::user())
                                            <form action="{{route('delete.cart.content',$cart->id)}}" method="POST">
                                        @else {{-- Guest --}}
                                            <form action="{{route('delete.cart.content')}}" method="POST">
                                        @endif
                                        @csrf
                                        <button type="submit" id="clear-cart-btn">Clear Cart</button>
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
                                    <td class="shoping__cart__item">
                                        <img src="{{asset('storage/'.$item->product->image)}}" alt="" height="75px">
                                        <h5>{{$item->product->name_en}}</h5>
                                    </td>
                                    <td class="shoping__cart__price">
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
                                    <td class="shoping__cart__quantity">
                                        <div class="quantity">
                                            <input type="number" class="displaynone" id="min{{$item->product->id}}" value="{{$item->product->min_weight}}">
                                            <input type="number" class="displaynone" id="increase{{$item->product->id}}" value="{{$item->product->increase_by}}">
                                            <div class="cart-qty">
                                                <span class="{{$counter}}qty dec cartqtybtn">-</span>
                                                <input type="text" value="{{$item->quantity}}" id="quantity-input{{$counter}}">
                                                @if($item->product->unit == 'gram')
                                                g
                                                @endif
                                                <input type="hidden" id="pro{{$item->product->id}}">
                                                <span class="{{$counter}}qty inc cartqtybtn" id="qty{{$counter}}">+</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="shoping__cart__total">
                                        {{--
                                        {{$item->product->price * $item->quantity}}
                                        --}}
                                        {{--
                                        @if($item->product->discount)  {{-- product has discount 
                                            {{$new_price * $item->quantity}}
                                        @else
                                            {{$item->product->price * $item->quantity}}
                                        @endif
                                        --}}
                                        {{--
                                        <input type="hidden" id="single-item-total{{$counter}}" value="{{$item->product->discount ? $new_price * $item->quantity : $item->product->price * $item->quantity}}">
                                        <h3 id="h-item-total{{$counter}}">{{$item->product->discount ? $new_price * $item->quantity : $item->product->price * $item->quantity}}</h6>
                                        --}}
                                        @if($item->product->unit == 'gram')    
                                        <input type="hidden" id="single-item-unit{{$counter}}" value="gram">
                                        <input type="hidden" id="single-item-total{{$counter}}" value="{{$item->product->hasDiscount() ? ($new_price * $item->quantity) / 1000 : ($item->product->price * $item->quantity) / 1000}}">
                                        <h3 id="h-item-total{{$counter}}">{{$item->product->hasDiscount() ? ($new_price * $item->quantity) / 1000 : ($item->product->price * $item->quantity) / 1000}}</h6>
                                        @else
                                        <input type="hidden" id="single-item-unit{{$counter}}" value="piece">
                                        <input type="hidden" id="single-item-total{{$counter}}" value="{{$item->product->hasDiscount() ? $new_price * $item->quantity : $item->product->price * $item->quantity}}">
                                        <h3 id="h-item-total{{$counter}}">{{$item->product->hasDiscount() ? $new_price * $item->quantity : $item->product->price * $item->quantity}}</h6>
                                        @endif
                                    </td>
                                    <td class="shoping__cart__item__close">
                                        <form action="{{route('delete.cart.item',$item->id)}}" method="POST">
                                            @csrf
                                            {{--
                                            <span onclick="javascript:this.form.submit();" class="icon_close"></span>
                                            --}}
                                            <button style="border: none; background-color: transparent" onclick="javascript:this.form.submit();" class="icon_close"></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php $counter++; ?>
                                @endforeach
                                <tr>
                                    <td class="shoping__cart__item">
                                       <span class="badge badge-info"> Apply points </span>
                                    </td>
                                    <td class="shoping__cart__price">
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                    </td>
                                    <td>
                                        <span id="question-mark" class="badge badge-warning" data-toggle="tooltip" data-placement="top" title="each {{$one_percent_discount}} point give you 1% discount">?</span>
                                        <select name="points" class="form-control" id="points-select">
                                            <option value="none">/</option>
                                            @for($i = $points ; $i >= $one_percent_discount ; $i-=$one_percent_discount)
                                                <option value="{{$i}}">{{$i}}</option>
                                            @endfor
                                        </select>
                                    </td>
                                </tr>
                                @else {{-- Empty cart --}}
                                <tr>
                                    <td>
                                        <span class="badge badge-danger">empty</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-danger">none</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-danger">none</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-danger">none</span>
                                    </td>
                                </tr>
                                @endif
                                <input type="hidden" value="{{$counter}}" id="cart-rows"> {{-- number of rows in cart --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else  {{-- Not authentacated --}}
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__table">
                        <table>
                            <thead>
                                <tr>
                                    <th class="shoping__product">Products</th>
                                    <th>1 K.G Price Piece Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>
                                        @if(Auth::user())
                                        <form action="{{route('delete.cart.content',$cart->id)}}" method="POST">
                                        @else {{-- Guest --}}
                                        <form action="{{route('delete.cart.content')}}" method="POST">
                                        @endif
                                        @csrf
                                        <button type="submit" id="clear-cart-btn">Clear Cart</button>
                                        </form>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter = 0 ?>
                                @if($cart_items->count() > 0)
                                @foreach($cart_items as $item)
                                <input type="hidden" value="{{$item->id}}" id="product{{$counter}}">
                                <tr>
                                    <td class="shoping__cart__item">
                                        <img src="{{asset('storage/'.$item->image)}}" alt="" height="75px">
                                        <h5>{{$item->name_en}}</h5>
                                    </td>
                                    <td class="shoping__cart__price">
                                    @if($item->hasDiscount())  {{-- product has discount --}}
                                        <?php
                                           if($item->isPercentDiscount()){
                                                $discount = $item->price * $item->discount->value / 100;
                                                $new_price = $item->price - $discount;
                                            }
                                            else
                                                $new_price = $item->price - $item->discount->value;
                                        ?>
                                    <input type="hidden" id="final-item-price{{$counter}}" value="{{$new_price}}">
                                    {{$new_price}}
                                    @else
                                        
                                        <input type="hidden" id="final-item-price{{$counter}}" value="{{$item->price}}">
                                        {{$item->price}}
                                    @endif
                                    </td>
                                    <td class="shoping__cart__quantity">
                                        <div class="quantity">
                                            <input type="number" class="displaynone" id="min{{$item->id}}" value="{{$item->min_weight}}">
                                            <input type="number" class="displaynone" id="increase{{$item->id}}" value="{{$item->increase_by}}">
                                            <div class="cart-qty">
                                                <span class="{{$counter}}qty dec cartqtybtn">-</span>
                                                <input type="text" value="{{$item->quantity}}" id="quantity-input{{$counter}}">
                                                @if($item->unit == 'gram')
                                                g
                                                @endif
                                                <input type="hidden" id="pro{{$item->id}}">
                                                <span class="{{$counter}}qty inc cartqtybtn" id="qty{{$counter}}">+</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="shoping__cart__total">
                                        @if($item->unit == 'gram')    
                                        <input type="hidden" id="single-item-unit{{$counter}}" value="gram">
                                        <input type="hidden" id="single-item-total{{$counter}}" value="{{$item->hasDiscount() ? ($new_price * $item->quantity) / 1000 : ($item->price * $item->quantity) / 1000}}">
                                        <h3 id="h-item-total{{$counter}}">{{$item->hasDiscount() ? ($new_price * $item->quantity) / 1000 : ($item->price * $item->quantity) / 1000}}</h6>
                                        @else
                                        <input type="hidden" id="single-item-unit{{$counter}}" value="piece">
                                        <input type="hidden" id="single-item-total{{$counter}}" value="{{$item->hasDiscount() ? $new_price * $item->quantity : $item->price * $item->quantity}}">
                                        <h3 id="h-item-total{{$counter}}">{{$item->hasDiscount() ? $new_price * $item->quantity : $item->price * $item->quantity}}</h6>
                                        @endif
                                    </td>
                                    <td class="shoping__cart__item__close">
                                        <form action="{{route('delete.cart.item',$item->id)}}" method="POST">
                                            @csrf
                                            {{--
                                            <span onclick="javascript:this.form.submit();" class="icon_close"></span>
                                            --}}
                                            <button style="border: none; background-color: transparent" onclick="javascript:this.form.submit();" class="icon_close"></button>
                                        </form>
                                    </td>
                                </tr>
                                <?php $counter++; ?>
                                @endforeach
                                @else {{-- Empty cart --}}
                                <tr>
                                    <td>
                                        <span class="badge badge-danger">empty</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-danger">none</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-danger">none</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-danger">none</span>
                                    </td>
                                </tr>
                                @endif
                                <input type="hidden" value="{{$counter}}" id="cart-rows"> {{-- number of rows in cart --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
            <div class="row">
                {{--
                <div class="col-lg-12">
                    <div class="shoping__cart__btns">
                        <a href="#" class="primary-btn cart-btn">CONTINUE SHOPPING</a>
                        <a href="#" class="primary-btn cart-btn cart-btn-right"><span class="icon_loading"></span>
                            Upadate Cart</a>
                    </div>
                </div>
                --}}
                <div id="have-account-form" class="col-lg-6 displaynone">
                    <div class="shoping__checkout">
                      <form action="{{route('login')}}" method="POST">
                        <h5>Already have an account ?</h5>
                        <ul>
                            <li>E-mail <input type="email" name="email" class="form-control"></li>
                            <li>Password <input type="password" name="password" class="form-control"></li>
                            <li>
                                    @csrf
                                <button id="login-btn" class="primary-btn">Login</button>
                            </li>
                        </ul>
                        <h6>Don't have an account ?</h6>
                        <a style="color: #7fad39; font-weight: bold;" href="{{route('sign.up')}}">sign up</a>
                      </form>
                    </div>
                </div>
               
                <div id="div-checkout" class="col-lg-12">
                    <div id="checkout-box" class="shoping__checkout">
                        <h5>Cart Total</h5>
                        <input type="hidden" value="{{$tax}}" id="tax">
                        <ul>
                            <li>Subtotal <span id="cart-subtotal">{{$cart_total}} AED</span></li>
                            <li>Tax <span id="cart-subtotal">{{$tax}}%</span></li>
                            <?php $tax_value = $tax * $cart_total / 100 ;
                                  $cart_grand_total = $cart_total + $tax_value ;
                                  $cart_grand_total = number_format((float)$cart_grand_total, 2, '.', '');
                            ?>
                            <li>Total <span id="cart-total">{{$cart_grand_total}} AED</span></li>
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
                                <input type="hidden" name="points_applied" id="points-input">
                                <button class="primary-btn">PROCEED TO CHECKOUT</button>
                                </div>
                            </form>
                            @endif
                        @else
                            @if($min_order > $cart_grand_total)
                            <h4 id="min-order-warning">You can't submit order by less than {{$min_order}} AED</h4>
                            <div id="proceed-to-checkout-div" class="displaynone">
                            <button id="proceed-to-checkout" class="primary-btn">PROCEED TO CHECKOUT</button>
                            </div>
                            @else
                            <h4 id="min-order-warning" class="displaynone">You can't submit order by less than {{$min_order}} AED</h4>
                            <div id="proceed-to-checkout-div">
                            <button id="proceed-to-checkout" class="primary-btn">PROCEED TO CHECKOUT</button>
                            </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Shoping Cart Section End -->
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