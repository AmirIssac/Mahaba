@extends('Layouts.secondary')
@section('links')

@endsection
@section('content')
<!-- Checkout Section Begin -->
<section style="margin-top: -75px;" class="checkout spad">
    <div class="container">
        <div class="checkout__form">
            <h4>Order Details</h4>
            <form action="{{route('submit.order')}}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="checkout__order">
                            <div style="display: flex; justify-content: space-between;">
                            <h4>#{{$order->number}}</h4>
                            <img src="{{asset('img/pngs/success-icon.png')}}" height="35px">
                            </div>
                            <div class="checkout__order__products">Products <span>Total</span></div>
                            <ul>
                                @foreach($order_items as $item)
                                <li> {{$item->product->name_en}}
                                    @if($item->product->unit == 'gram')
                                     <b style="color: #7fad39">{{$item->quantity/1000}} K.G </b> <span>{{$item->price * $item->quantity / 1000}} AED</span>
                                    @elseif($item->product->unit == 'piece')
                                    <b style="color: #7fad39">{{$item->quantity}} </b> <span>{{$item->price * $item->quantity}} AED</span>
                                    @endif
                                </li>
                                @endforeach
                            </ul>
                            <div class="checkout__order__subtotal">Subtotal <span> {{$order->sub_total}} AED</span></div>
                            <div class="checkout__order__total">Tax <span>{{$order->tax_ratio}}% ({{$order->tax_value}} AED)</span></div>
                            
                            <div class="checkout__order__total">Total <span> {{$order->total}} AED</span></div>
                            <div>
                                 <span class="badge badge-success">success</span>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
<!-- Checkout Section End -->
@section('scripts')
    
@endsection
@endsection