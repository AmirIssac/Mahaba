@extends('Layouts.main')
@section('links')
<style>
    .product-container{
        background-color: #622521;
        color: white;
        border: none;
        filter: drop-shadow(3px 5px 3px #622521);
     }
     .view-btn{
                background-color: wheat;
                color: #622521;
                font-weight: bold;
                width: 50px;
                height: 35px;
     }
     a.view-btn:hover{
         text-decoration: none;
         color: #622521;
         background-color: white;;
     }
     .sale-badge{
         background-color: #951509;
     }
     .text-decoration-line-through{
         color: #d6c1bf;
     }
     .new-price{
         font-weight: bold;
     }
</style>
@endsection
@section('body')
<!-- Section-->
<section class="py-5">
   <div class="container px-4 px-lg-5 mt-5">
       <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
           @foreach($products as $product)
                <div class="col mb-5">
                    <div class="card h-100 product-container">
                        <!-- Sale badge-->
                        @if($product->hasDiscount())  {{-- product has discount --}}
                            <?php
                                if($product->isPercentDiscount()){
                                    $discount = $product->discount->value;
                                }
                                else{
                                    $discount = $product->discount->value * 100 / $product->price;
                                }
                            ?>
                            <div class="badge sale-badge position-absolute" style="top: 0.5rem; right: 0.5rem">Sale {{ $discount }}%</div>
                        @else
                        @endif
                        <!-- Product image-->
                        <img class="card-img-top" src="{{ asset('storage/'.$product->image)}}" alt="..." />
                        <!-- Product details-->
                        <div class="card-body p-4">
                            <div class="text-center">
                                <!-- Product name-->
                                <h5 class="fw-bolder">{{ $product->name_en }}</h5>
                                <!-- Product reviews-->
                                {{--
                                <div class="d-flex justify-content-center small text-warning mb-2">
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                    <div class="bi-star-fill"></div>
                                </div>
                                --}}
                                <!-- Product price-->
                                @if($product->hasDiscount())  {{-- product has discount --}}
                                        <?php
                                            if($product->isPercentDiscount()){
                                                $discount = $product->price * $product->discount->value / 100;
                                                $new_price = $product->price - $discount;
                                            }
                                            else
                                                $new_price = $product->price - $product->discount->value;
                                        ?>
                                           <span class="text-decoration-line-through">{{$product->price}}</span>
                                           <span class="new-price">
                                           {{ $new_price }} AED
                                           </span>
                                @else
                                         <span class="new-price">
                                          {{ $product->price }} AED
                                         </span>
                                @endif
                            </div>
                        </div>
                        <!-- Product actions-->
                        <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                            <div class="text-center"><a class="btn view-btn" href="#"><i class="fa-solid fa-cart-arrow-down"></i></a>
                                <a class="btn view-btn" href="{{ route('view.product',$product->id) }}"><i class="fa-solid fa-eye"></i></a>
                            </div>                        </div>
                    </div>
                </div>
           @endforeach
       </div>
   </div>
</section>
@endsection {{--  end-body --}}
