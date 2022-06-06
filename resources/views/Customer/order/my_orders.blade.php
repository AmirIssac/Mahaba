@extends('Layouts.main')
@section('links')
<style>
    .table ,  td , th {
        color: #622521 !important;
    }
    tbody , td , th {
        color: #622521 !important;
    }
</style>
@endsection
@section('body')
<!-- Header-->
<header class="bg-second py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center product-div">
            <table class="table">
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
                            <span>  {{$order->total}}  </span>
                        </td>
                        <td>
                            @if($order->status == 'pending')
                           <span> {{$order->status}} </span>
                           @elseif($order->status == 'preparing' || $order->status == 'shipping')
                           <span> {{$order->status}} </span>
                           @elseif($order->status == 'delivered')
                           <span> {{$order->status}} </span>
                           @elseif($order->status == 'failed' || $order->status == 'cancelled' || $order->status == 'rejected')
                           <span> {{$order->status}} </span>
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
    </div>
</header>
@section('scripts')

@endsection
@endsection
