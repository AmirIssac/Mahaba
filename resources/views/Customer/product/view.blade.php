@extends('Layouts.main')
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('links')
<style>
    #add-to-cart{
        width: 50px;
    }
    .category_img{
        border-radius: 8px;
        filter: drop-shadow(3px 5px 3px #e4eef0);
     }
     .view-btn{
                background-color: wheat;
                color: #622521;
                font-weight: bold;
                width: 50px;
                height: 35px;
     }
     .view-btn:hover{
         text-decoration: none;
         color: #622521;
         background-color: white;;
     }
     .displaynone{
         display: none;
     }
     #rate-div form{
         display: flex;
         justify-content: center;
         padding: 25px;
     }
     #rate-btn{
                background-color: rgb(255, 255, 255);
                color: #622521;
                font-weight: bold;
                width: 50px;
                height: 45px;
                border: 1px solid #fff;
                border-radius:4px;
     }
     .bi-star-fill:hover{
         cursor: pointer;
     }
     .bi-star:hover{
         cursor: pointer;
     }

    /* select style */
    .small {
    font-size: .8em;
    }

    .square {
    width: .7em;
    height: .7em;
    margin: .5em;
    display: inline-block;
    }

    /* Custom dropdown */
    .custom-dropdown {
    position: relative;
    display: inline-block;
    vertical-align: middle;
    }

    .custom-dropdown select {
    background-color: wheat;
    color: #622521;
    font-size: inherit;
    padding: .5em;
    padding-right: 2.5em;
    border: 0;
    margin: 0;
    border-radius: 3px;
    text-indent: 0.01px;
    text-overflow: '';
    -webkit-appearance: button; /* hide default arrow in chrome OSX */
    }

    .custom-dropdown::before,
    .custom-dropdown::after {
    content: "";
    position: absolute;
    pointer-events: none;
    }

    .custom-dropdown::after { /*  Custom dropdown arrow */
    content: "\25BC";
    height: 1em;
    font-size: .625em;
    line-height: 1;
    right: 1.2em;
    top: 50%;
    margin-top: -.5em;
    }

    .custom-dropdown::before { /*  Custom dropdown arrow cover */
    width: 2em;
    right: 0;
    top: 0;
    bottom: 0;
    border-radius: 0 3px 3px 0;
    }

    .custom-dropdown select[disabled] {
    color: rgba(0,0,0,.3);
    }

    .custom-dropdown select[disabled]::after {
    color: rgba(0,0,0,.1);
    }

    .custom-dropdown::before {
    background-color: rgba(0,0,0,.15);
    }

    .custom-dropdown::after {
    color: rgba(0,0,0,.4);
    }

    @media only screen and (max-width: 600px) {
        .product-div {
            display: flex;
            flex-direction: column;
        }
        .my-product-content {
                    flex-direction: column;
                }
    }
</style>
@endsection
@section('body')
<!-- Header-->
<header class="bg-prim py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white product-div">
            <h1 class="display-4 fw-bolder text-prim">{{ $product->name_en }}</h1>
            <div style="display: flex; justify-content: space-around;" class="my-product-content">
                <div style="display: flex; flex-direction: column">
                    <div style="display: flex; justify-content: center">
                    @for($k=1;$k<=$rate;$k++)
                            <i style="color:wheat" class="fa fa-star"></i>
                    @endfor
                            <span>({{$reviews}} reviews)</span>
                    </div>
                    <img src="{{ asset('storage/'.$product->image) }}" class="category_img" height="250px">
                </div>

                <div style="display: flex; flex-direction: column; padding:10px;">
                    <h3>{{ $product->description }}</h3>
                @if($product->hasDiscount())  {{-- product has discount --}}
                                    <?php
                                        if($product->isPercentDiscount()){
                                            $discount = $product->price * $product->discount->value / 100;
                                            $new_price = $product->price - $discount;
                                        }
                                        else
                                            $new_price = $product->price - $product->discount->value;
                                    ?>
                                    <input style="display: none;" type="number" id="initial-price" value="{{$new_price}}">
                                    <span class="text-muted text-decoration-line-through" style="color: #f44336 !important">{{ $product->price }}</span>
                                    <h4>السعر {{ $new_price }} درهم</h4>
                @else

                                    <input style="display: none;" type="number" id="initial-price" value="{{$product->price}}">
                                    <h4>السعر {{ $product->price }} درهم</h4>
                @endif
                @if($product->isGram())
                    <h4>من أجل كل 1 كيلو غرام</h4>
                @elseif($product->isPiece())
                    <h4>  من أجل كل قطعة  </h4>
                @endif
                    {{--
                    <input style="display: none"  id="weight-in-gram" type='number' name='weight' value="{{$product->unit == 'gram' ? $product->min_weight : $product->min_weight}}" readonly/>
                    --}}
                    {{--
                    <div class="d-flex justify-content-center small text-warning mb-2">
                        <div class="bi-star-fill"></div>
                        <div class="bi-star-fill"></div>
                        <div class="bi-star-fill"></div>
                        <div class="bi-star-fill"></div>
                        <div class="bi-star-fill"></div>
                    </div>
                    --}}
                    <div style="display: flex; justify-content: space-around; padding:20px;">
                        @if($product->availability)
                        <button id="add-to-cart" class="btn view-btn"> <i class="fa fa-cart-plus"></i></button>
                        @else
                        <button id="add-to-cart" class="btn btn-danger" disabled> <i class="fa fa-cart-plus"></i> </button>
                        @endif
                        {{--
                        <button id="favorite-btn" class="btn view-btn"><i class="fa-solid fa-heart-circle-plus"></i> </button>
                        --}}
                            <span class="custom-dropdown small">
                                <select id="weight-in-gram">
                                    @if($product->isGram())
                                        <?php $quantity = $product->min_weight / 1000 ;
                                        $increasing = $product->increase_by / 1000 ;
                                        $loop_counter = 1 ;
                                        ?>
                                        @for($i=$quantity ; $loop_counter <= 12 ; $i+=$increasing)
                                            <option value="{{ $i * 1000 }}">{{ $i }} K.G</option>
                                            <?php $loop_counter++ ?>
                                        @endfor
                                    @elseif($product->isPiece())
                                        <?php $quantity = $product->min_weight;
                                        $increasing = $product->increase_by;
                                        $loop_counter = 1 ;
                                        ?>
                                        @for($i=$quantity ; $loop_counter <= 12 ; $i+=$increasing)
                                            <option value="{{ $i }}">{{ $i }}</option>
                                            <?php $loop_counter++ ?>
                                        @endfor
                                    @endif
                                </select>
                            </span>
                    </div>

                    <div>
                        @foreach($attributes_list as $attribute)
                        {{ $attribute->name_ar }}
                        <br>
                        <br>
                            @foreach($product->attributeValues as $attribute_value)
                                    @if($attribute_value->attribute_id == $attribute->id)
                                        {{ $attribute_value->value }}+
                                        {{ $attribute_value->printAttributeValuePrice($product->id) }}
                                        <input type="checkbox" name="attribute_values[]" class="attribute_values" value="{{ $attribute_value->id }}">
                                    @endif
                            @endforeach
                        <br>
                        @endforeach
                    </div>

                    @if(Auth::user())
                    @if(!$exist_rate)
                        <div id="rate-div">
                            <form action="{{route('rate.product',$product->id)}}" method="POST">
                                @for($i=1;$i<=5;$i++)
                                        @csrf
                                        <div style="margin:10px; font-size:20px;" class="bi-star" id="empty-star-{{$i}}"></div>
                                        <div style="margin:10px;font-size:20px;" class="bi-star-fill displaynone" id="star-{{$i}}"></div>
                                @endfor
                                <button class="star-btn" id="rate-btn" name="rate" value="{{0}}">
                                    rate
                                </button>
                            </form>
                        </div>
                    @else
                        <li><b>Thanx for rating</b>
                            <div style="display: flex; justify-content: center">
                                @for($i=1;$i<=$user_rate_val;$i++)
                                    <div style="margin:10px;font-size:20px;" class="bi-star-fill"></div>
                                @endfor
                            </div>
                        </li>
                    @endif
                    @endif
                </div>
            </div>
            <p class="lead fw-normal text-white-50 mb-0"> Dabbagh | دباغ</p>
        </div>
    </div>
</header>
@section('scripts')
<script>

function addToCartNotification(from, align){
        color = 'success';
        $.notify({
            message: "Successfully added to cart"
        },{
            type: color,
            timer: 20,
            placement: {
                from: from,
                align: align
            }
        });
    }
        /*function addToCart(){*/
    $('document').ready(function(){
        $('#add-to-cart').on('click',function(){
           // var product_id = $('#product-id').val();
           var product_id = {!! json_encode($product->id, JSON_HEX_TAG) !!};
            //var qty = parseInt($('#quantity-input').val());
            var qty = parseInt($('#weight-in-gram').val());
           // var attribute_values = $('.attribute_values').val();
           //alert($('input[name="attribute_values[]"]:checked').serialize());
           // all attribute values checked
           var attribute_values = $('input[name="attribute_values[]"]:checked').serializeArray();
          // console.log(attribute_values).serializeArray();
           //var attribute_values = $('input:checkbox:checked').val();
           //var formData        = $('input[name="attribute_values[]"]:checked').serializeArray();
           //alert(formData);
           //alert(attribute_values);
            //alert(attribute_values);
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "post",
                url: '/add/product/toCart/'+product_id,
                data : { quantity : qty , attribute_values : attribute_values },
                //dataType: 'json',
                success: function(data){    // data is the response come from controller
                    if(data == 'success')
                        addToCartNotification('top','center');
                }
            }); // ajax close
        });

        $('.to-favorite').on('click',function(){
            //var product_id = $('#product-id').val();
            var product_id = {!! json_encode($product->id, JSON_HEX_TAG) !!};
            $.ajax({
                headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "post",
                url: '/add/product/toFavorite/'+product_id,
                success: function(data){    // data is the response come from controller
                    if(data == 'added'){
                        $('#unfavorite-btn').addClass('displaynone');
                        $('#favorite-btn').removeClass('displaynone');
                        addToFavoriteNotification('top','center');
                        //alert('added to favorite !!');
                    }
                    else{
                        $('#favorite-btn').addClass('displaynone');
                        $('#unfavorite-btn').removeClass('displaynone');
                        removeFromFavoriteNotification('top','center');
                        //alert('removed from favorite !!');
                    }
                }
            }); // ajax close
        });
    });

    $("[id^='empty-star']").on('click',function() {
        var gold = $(this).attr('id').slice(11);
        $('#rate-btn').val(gold);
        for(var i = parseInt(gold) ; i >= 1 ; --i ){
            $('#empty-star-'+i).addClass('displaynone');
            $('#star-'+i).removeClass('displaynone');
        }
    });
    $("[id^='star']").on('click',function() {
        var gold = $(this).attr('id').slice(5);
        $('#rate-btn').val(gold);
        for( i = parseInt(gold)+1 ; i <= 5 ; ++i ){  // gaping
            $('#star-'+i).addClass('displaynone');
            $('#empty-star-'+i).removeClass('displaynone');
        }
    });

</script>
@endsection
@endsection
