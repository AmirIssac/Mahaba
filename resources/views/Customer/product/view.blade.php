@extends('Layouts.main')
@section('links')
<style>
    #add-to-cart{
        width: 50px;
    }
</style>
@endsection
@section('body')
<!-- Header-->
<header class="bg-prim py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white my-sheep-header">
            <h1 class="display-4 fw-bolder text-prim">{{ $product->name_en }}</h1>
            <div style="display: flex; justify-content: space-around;" class="my-sheep-content">

                <div style="display: flex; flex-direction: column">
                    <img src="{{ asset('storage/'.$product->image) }}" class="category_img" height="250px">
                </div>

                <div style="display: flex; flex-direction: column">
                    <h3>{{ $product->description }}</h3>
                    <h4>السعر {{ $product->price }} درهم</h4>
                    <h4>من أجل كل 1 كيلو غرام</h4>
                    <div class="d-flex justify-content-center small text-warning mb-2">
                        <div class="bi-star-fill"></div>
                        <div class="bi-star-fill"></div>
                        <div class="bi-star-fill"></div>
                        <div class="bi-star-fill"></div>
                        <div class="bi-star-fill"></div>
                    </div>
                    <div style="display: flex; justify-content: space-around">
                        <button id="add-to-cart" class="btn btn-success"><i class="fa fa-cart-plus"></i> </button>
                        <button id="favorite-btn" class="btn btn-success"><i class="fa-solid fa-heart-circle-plus"></i> </button>
                            <select>
                                <?php $quantity = $product->min_weight / 1000 ;
                                      $increasing = $product->increase_by / 1000 ;
                                ?>
                                @for($i=$quantity ; $i <= 10 ; $i+=$increasing)
                                    <option value="{{ $i }}">{{ $i }} K.G</option>
                                @endfor
                            </select>
                    </div>
                </div>
            </div>
            <p class="lead fw-normal text-white-50 mb-0"> Dabbagh | دباغ</p>
        </div>
    </div>
</header>
@endsection
