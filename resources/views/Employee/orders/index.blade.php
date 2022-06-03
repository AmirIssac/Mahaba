@extends('Layouts.dashboard_main')
@section('links')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  .displaynone {
    display: none;
  }
</style>
<style>
.radius-span{
  border-radius:50%;
  margin: 0 10;
}
.orders-container-notification{
  background-color: black;
  color: white;
  outline: 10px solid rgb(240, 6, 6);
  animation-name: border-animation;
  animation-duration: 3s;
  animation-iteration-count: infinite;
}
/*
@keyframes border-animation {
  0%   {border: 3px solid rgb(240, 6, 6);}
  25%  {border: 6px solid rgb(240, 6, 6);}
  50%  {border: 9px solid rgb(240, 6, 6);}
  75%  {border: 12px solid rgb(240, 6, 6);}
  100% {border: 15px solid rgb(240, 6, 6);}
}
*/
@keyframes border-animation {
  0%   {outline: 8px solid rgb(240, 6, 6);
        background-color: black;
        color : white;
        }
  25%  {outline: 0px solid rgb(240, 6, 6);
        background-color: white;
        color : black;
        }
  50%  {outline: 8px solid rgb(240, 6, 6);
        background-color: black;
        color : white;}
  75%  {outline: 0px solid rgb(240, 6, 6);
        background-color: white;
        color : black;}
  100% {outline: 8px solid rgb(240, 6, 6);
        background-color: black;
        color : white;}
}
</style>
@endsection
@section('content')
<div class="panel-header panel-header-sm">
</div>
<div class="content">
  <div class="row">
    <div class="col-md-12 ml-auto mr-auto">
      <div class="card card-upgrade">
        <span id="new-orders-badge" style="margin: 20px;" class="badge badge-danger displaynone"><b id="new-orders-count">2</b> NEW</span>
        <p style="color:rgb(240, 6, 6) !important; margin-left: 20px; font-weight:bold;" id="text-notification" class="displaynone">please press F5 or Refresh the page</p>
        <div class="card-header text-center">
          <h4 class="card-title">Orders <span class="badge badge-primary radius-span"> {{$orders_count}} </span>
          </h4>
          <h4 class="card-title">
          Pending <span class="badge badge-danger radius-span"> {{$status_arr['pending']}} </span>
          preparing <span class="badge badge-info radius-span"> {{$status_arr['preparing']}} </span>
          shipping <span class="badge badge-info radius-span"> {{$status_arr['shipping']}} </span>
          delivered <span class="badge badge-success radius-span"> {{$status_arr['delivered']}} </span>
          rejected <span class="badge badge-warning radius-span"> {{$status_arr['rejected']}} </span>
          </h4>
            <p class="card-category"></p>
        </div>
        <div class="card-body">
          <div class="table-responsive table-upgrade">
            <table class="table">
              <thead>
                <th style="font-weight: bold">Number</th>
                <th style="font-weight: bold" class="text-center">Total</th>
                <th style="font-weight: bold" class="text-center">Status</th>
                <th style="font-weight: bold" class="text-center">Customer</th>
                <th style="font-weight: bold" class="text-center">Store</th>
                <th style="font-weight: bold" class="text-center">Action</th>
              </thead>
              <tbody>
                @foreach($orders as $order)
                <tr style="font-weight: bold">
                  <td>{{$order->number}}</td>
                  <td class="text-center"><span class="badge badge-success">{{$order->total}}</span></td>
                  <td class="text-center">
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
                  <td class="text-center">
                    @if($order->user->isGuest())
                    Guest
                    @else
                    {{$order->user->profile->first_name}}
                    @endif
                  </td>
                  <td class="text-center">{{$order->store->name_en}}</td>
                  <td class="text-center"><a href="{{route('employee.edit.order',$order->id)}}"><i class="fas fa-tools"></i></a></td>
                </tr>
                @endforeach
              </tbody>
            </table>
            {!! $orders->links() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
{{-- timestamp for the last order created when the page refreshed  --}}
<input type="hidden" id="last-updated-order" value="{{$last_updated_order_timestamp}}">
{{-- audio link --}}
<input type="hidden" id="notification-audio-link" value="{{asset('audio/new-order.wav')}}">
@section('scripts')
<script>
  function checkNewOrders(){
    var token = $("meta[name='csrf-token']").attr("content");
    var updated_at = $('#last-updated-order').val();
    $.ajax(
            {
                    url: "/check/new/orders/",
                    type: 'GET',
                    data: {
                        "_token": token,
                        "updated_at" : updated_at,
                    },
                    success: function (data){
                      if(data > 0){
                        $('#new-orders-badge').removeClass('displaynone');
                        $('#new-orders-count').html(data);
                        $('#text-notification').removeClass('displaynone');
                        $('.card').addClass('orders-container-notification');
                        var audio = new Audio($('#notification-audio-link').val());
                        audio.play();
                      }
                    }
    });
  }
  window.setInterval(checkNewOrders, 5000);
</script>
@endsection
@endsection