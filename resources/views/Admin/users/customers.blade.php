@extends('Layouts.dashboard_main')
@section('content')
<div class="panel-header panel-header-sm">
</div>
<div class="content">
  <div class="row">
    <div class="col-md-12 ml-auto mr-auto">
      <div class="card card-upgrade">
        <div class="card-header text-center">
          <h4 class="card-title">Customers <span class="badge badge-primary"> {{$customers->count()}} </span> </h3>
            <p class="card-category"></p>
        </div>
        <div class="card-body">
          <div class="table-responsive table-upgrade">
            <table class="table">
              <thead>
                <th style="font-weight: bold">Name</th>
                <th style="font-weight: bold">Email</th>
                <th style="font-weight: bold" class="text-center">Phone</th>
                <th style="font-weight: bold" class="text-center">Address</th>
                <th style="font-weight: bold" class="text-center">Orders</th>
                <th style="font-weight: bold" class="text-center">Last Order</th>
                <th style="font-weight: bold" class="text-center">Action</th>
              </thead>
              <tbody>
                @foreach($customers as $customer)
                <tr style="font-weight: bold">
                <td>{{$customer->name}}</td>
                <td>{{$customer->email}}</td>
                <td class="text-center">{{$customer->profile->phone}}</td>
                <td class="text-center">
                    @if($customer->profile->address_address)
                        {{$customer->profile->address_address}}
                    @else
                        /
                    @endif
                </td>
                <td class="text-center">{{$customer->orders->count()}}</td>
                <td class="text-center">
                    @if($customer->orders->count() > 0)
                         {{$customer->orders->last()->created_at}}
                    @else
                         /
                    @endif
                </td>
                <td class="text-center">
                    <a href="{{route('view.user',$customer->id)}}"><i class="fa fa-eye"></i></a>
                </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection