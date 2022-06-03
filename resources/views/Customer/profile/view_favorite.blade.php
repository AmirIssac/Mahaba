@extends('Layouts.main')
@section('links')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
    .add-to-cart{
        background-color: #2b201e;
        border: 1px solid #2b201e;
        border-radius: 5px;
        font-size: 13px;
        color: white;
        font-weight : bold;
        width: 100px;
    }
    .displaynone{
        display: none;
    }
</style>
@endsection
@section('content')
    <!-- Favorite Section Begin -->
    <section style="margin-top:-100px;" class="shoping-cart spad">
        <div class="container">
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
                                    </th>
                                    <th>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $counter = 0 ?>
                                @if($products->count() > 0)
                                @foreach($products as $item)
                                <input type="hidden" value="{{$item->min_weight}}" id="min-weight-{{$item->id}}">
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
                                                <h4>
                                                    {{$item->min_weight}}
                                                
                                                @if($item->unit == 'gram')
                                                g
                                                @endif
                                                </h4>
                                    </td>
                                    <td class="shoping__cart__total">
                                        @if($item->unit == 'gram')    
                                        <input type="hidden" id="single-item-unit{{$counter}}" value="gram">
                                        <input type="hidden" id="single-item-total{{$counter}}" value="{{$item->hasDiscount() ? ($new_price * $item->min_weight) / 1000 : ($item->price * $item->min_weight) / 1000}}">
                                        <h3 id="h-item-total{{$counter}}">{{$item->hasDiscount() ? ($new_price * $item->min_weight) / 1000 : ($item->price * $item->min_weight) / 1000}}</h6>
                                        @else
                                        <input type="hidden" id="single-item-unit{{$counter}}" value="piece">
                                        <input type="hidden" id="single-item-total{{$counter}}" value="{{$item->hasDiscount() ? $new_price * $item->min_weight : $item->price * $item->min_weight}}">
                                        <h3 id="h-item-total{{$counter}}">{{$item->hasDiscount() ? $new_price * $item->min_weight : $item->price * $item->min_weight}}</h6>
                                        @endif
                                    </td>
                                    <td class="shoping__cart__item__close">
                                        {{--
                                            <button style="border: none; background-color: transparent" onclick="javascript:this.form.submit();" class="icon_close"></button>
                                            --}}
                                            <button id="add-to-cart-{{$item->id}}" class="add-to-cart">Add to cart</button>
                                    </td>
                                    <td>
                                            <form action="{{route('remove.product.from.favorite',$item->id)}}" method="POST">
                                                @csrf
                                                <button style="border: none; background-color: transparent" class="icon_close"></button>
                                            </form>
                                    </td>
                                </tr>
                                <?php $counter++; ?>
                                @endforeach
                                @else {{-- Empty favorite --}}
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
                                <input type="hidden" value="{{$counter}}" id="cart-rows"> {{-- number of rows in favorite --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
          
        </div>
    </section>
    <!-- Favorite Section End -->
@section('scripts')
<script>
    /*function addToCart(){*/
$('document').ready(function(){
    $('[id^=add-to-cart-]').on('click',function(){
        var gold = $(this).attr('id').slice(12);
        var qty = parseInt($('#min-weight-'+gold).val());
        var product_id = gold;
        $.ajax({
            headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "post",
            url: '/add/product/toCart/'+product_id,
            data : { quantity : qty },
            //dataType: 'json',
            success: function(data){    // data is the response come from controller
                if(data == 'success')
                    alert('added to cart !!');
            }
        }); // ajax close
    });
});
</script>
@endsection
@endsection