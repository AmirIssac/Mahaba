@extends('Layouts.secondary')
@section('links')

@endsection
@section('content')
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
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   @foreach($orders as $order)
                                        <tr>
                                            <td>
                                              <b>  {{$order->number}}   </b>
                                            </td>
                                            <td>
                                                <span class="badge badge-success">  {{$order->total}}  </span>
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
                                                <a href="{{route('view.order',$order->id)}}"><i class="fa fa-eye"></i></a>
                                            </td>
                                        </tr>
                                   @endforeach
                                </tbody>
                            </table>
                        </div>
                        {!! $orders->links() !!}
                    </div>
                </div>
    </div>
</section>
@section('scripts')
    
@endsection
@endsection