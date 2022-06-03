@extends('Layouts.dashboard_main')
@section('links')
<style>
.radius-span{
  border-radius:50%;
  margin: 0 10;
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
                <th style="font-weight: bold" class="text-center">Date</th>
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
                  <td class="text-center">{{$order->created_at}}</td>
                  <td class="text-center">
                    @if(isset($order->store))
                    {{$order->store->name_en}}
                    @else
                    <span class="badge badge-danger">NONE</span>
                    @endif
                  </td>
                  <td class="text-center"><a href="{{route('edit.order',$order->id)}}"><i class="fas fa-tools"></i></a></td>
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
@endsection