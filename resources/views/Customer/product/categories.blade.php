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
           @foreach($categories as $category)
                <div class="col mb-5">
                    <a href="{{ route('index.by.category',$category->id) }}">
                        <div class="card h-100 product-container">
                            <!-- Category image-->
                            <img class="card-img-top" src="{{ asset('storage/'.$category->image)}}" alt="..." />
                            <!-- Product details-->
                            <div class="card-body p-4">
                                <div class="text-center">
                                    <!-- Product name-->
                                    <h5 class="fw-bolder">{{ $category->name_en }}</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
           @endforeach
       </div>
   </div>
</section>
@endsection {{--  end-body --}}
