@extends('Layouts.secondary')
@section('links')

@endsection
@section('content')
{{--
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
                                 <h4>Last update on status {{$last_update_status}}</h4>
                                 <h6 style="font-weight: bold;">{{$order->status}}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
--}}
<section style="margin-top: -75px;" class="checkout spad">
    <div class="container">
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <div class="shoping__cart__table">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Number</th>
                                        <th>Total</th>
                                        <th>Store</th>
                                        <th>Status</th>
                                        <th>Last update</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <tr>
                                            <td>
                                              <b>  {{$order->number}}   </b>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">  {{$order->total}}  </span>
                                            </td>
                                            <td>
                                                {{isset($order->store) ? $order->store->name_en : '/'}}
                                            </td>
                                            <td>
                                                @if($order->status == 'pending')
                                               <span class="badge badge-danger"> {{$order->status}} </span>
                                               @elseif($order->status == 'preparing' || $order->status == 'shipping')
                                               <span class="badge badge-info"> {{$order->status}} </span>
                                               @elseif($order->status == 'delivered')
                                               <span class="badge badge-success"> {{$order->status}} </span>
                                               @elseif($order->status == 'failed' || $order->status == 'cancelled' || $order->status == 'rejected')
                                               <span class="badge badge-warning"> {{$order->status}} </span>
                                               @endif
                                            </td>
                                            <td>
                                                {{$last_update_status}}
                                            </td>
                                        </tr>
                                        <tr>
                                        <td style="font-weight: bold">
                                            Product
                                        </td>
                                        <td style="font-weight: bold">
                                            Quantity
                                        </td>
                                        <td style="font-weight: bold">
                                            Price
                                        </td>
                                        <td>
                                        </td>
                                        <td>
                                        </td>
                                    </tr>
                                    @foreach($order_items as $item)
                                        <tr>
                                            <td>
                                                {{$item->product->name_en}}
                                            </td>
                                            <td>
                                                @if($item->product->unit == 'gram')
                                                    {{$item->quantity / 1000}} K.G
                                                @elseif($item->product->unit == 'piece')
                                                    {{$item->quantity}}
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->product->unit == 'gram')
                                                    {{$item->quantity * $item->price / 1000}}
                                                @elseif($item->product->unit == 'piece')
                                                    {{$item->quantity * $item->price}}
                                                @endif
                                            </td>
                                            <td>
                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
    </div>
</section>
@section('scripts')
    
@endsection
@endsection