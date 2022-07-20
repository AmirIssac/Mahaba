@extends('Layouts.dashboard_main')
@section('links')
<style>
    .preparing-row{
        background-color: #04558b;
        color: white;
    }
    .shipping-row{
        background-color: #409ad6;
    }
    .delivered-row{
        background-color: #38b818;
        color: white;
    }
    .rejected-row{
        background-color: #c00202;
        color: white;
    }
    .displaynone{
        display: none;
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
          <h4 class="card-title"> <span class="badge badge-primary"> #{{$order->number}} </span>
            <a style="float: right" href="{{route('print.delivery.order',$order->id)}}" class="btn btn-info">print</a>
           </h4>
            <p class="card-category"></p>
        </div>
        <div class="card-body">
          <div class="table-responsive table-upgrade">
            <table class="table">
              <thead>
                <th style="font-weight: bold">Customer</th>
                <th style="font-weight: bold" class="text-center">Status</th>
                <th style="font-weight: bold" class="text-center">Total</th>
                <th style="font-weight: bold" class="text-center">Address</th>
                <th style="font-weight: bold" class="text-center">Payment</th>
                <th style="font-weight: bold" class="text-center">Payment status</th>
              </thead>
              <tbody>
                <tr style="font-weight: bold">
                  <td>
                    @if($order->user->isGuest())
                    Guest
                    @else
                    {{$order->user->profile->first_name}}
                    @endif
                  </td>
                  <td class="text-center">
                    @if($order->status == 'pending')
                    <b style="color: #c00202"> {{$order->status}} </b>
                    @elseif($order->status == 'preparing' || $order->status == 'shipping')
                    <b style="color: #04558b"> {{$order->status}} </b>
                    @elseif($order->status == 'delivered')
                    <b style="color: #069e1f"> {{$order->status}} </b>
                    @elseif($order->status == 'failed' || $order->status == 'cancelled' || $order->status == 'rejected')
                    <b style="color: #c00202"> {{$order->status}} </b>
                    @endif
                  </td>
                  <td style="color:#38b818; font-weight:bold;" class="text-center">{{$order->total}}</td>
                  <td class="text-center">{{$order->address}} , {{ $order->address_street }} , {{ $order->address_building_apartment }}</td>
                  <td class="text-center">{{$order->paymentDetail->provider}}</td>
                  <td class="text-center">{{$order->paymentDetail->status}}</td>
                </tr>
                <tr>   {{-- order items --}}
                    <th>
                        Email
                    </th>
                    <th style="font-weight: bold" class="text-center">
                        Phone
                    </th>
                    <th style="font-weight: bold" class="text-center">Customer note</th>
                    <th style="font-weight: bold" class="text-center">Center note</th>
                    @if($order->status != 'delivered' && $order->status != 'rejected')
                    <th style="font-weight: bold" class="text-center">
                      Deliver must be in
                    </th>
                    @else
                    <th style="color: #c00202; font-weight: bold" class="text-center">
                      Finished in
                    </th>
                    @endif
                    <th>
                    </th>
                </tr>
                <tr>
                    <td>
                        {{$order->email}}
                    </td>
                    <td style="font-weight: bold" class="text-center">
                        {{$order->phone}}
                    </td>
                    <td class="text-center">
                        @if($order->customer_note)
                          {{$order->customer_note}}
                        @else
                          <span class="badge badge-danger">NONE</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($order_center_system->employee_note)
                              {{$order_center_system->employee_note}}
                        @else
                              <span class="badge badge-danger">NONE</span>
                        @endif
                        @if($order->status != 'delivered' && $order->status != 'rejected')
                        <td style="color: #c00202; font-weight: bold" class="text-center">
                          <input type="hidden" id="estimated-time" value="{{$estimated_time}}">
                          <p id="estimated-time-div"></p>
                        </td>
                        @else
                        <td style="font-weight: bold" class="text-center">
                            @if($done_in['check'] == false)
                                <span class="badge badge-danger">{{$done_in['finished_time']}}</span>
                            @else
                                <span class="badge badge-success">{{$done_in['finished_time']}}</span>
                            @endif
                        </td>
                        @endif
                    </td>
                    <td>
                    </td>
                </tr>
                <tr>   {{-- order items --}}
                    <th>
                        Product
                    </th>
                    <th style="font-weight: bold" class="text-center">
                        Quantity
                    </th>
                    <th style="font-weight: bold" class="text-center">
                        Price
                    </th>
                    <th>
                        Extras
                    </th>
                    <th>
                    </th>
                    <th>
                    </th>
                </tr>
                @foreach($order_items as $item)
                    <tr>
                        <td>
                            {{$item->product->name_en}}
                        </td>
                        <td style="font-weight: bold" class="text-center">
                            @if($item->product->unit == 'gram')
                                {{$item->quantity / 1000}} K.G
                            @elseif($item->product->unit == 'piece')
                                {{$item->quantity}}
                            @endif
                        </td>
                        <td style="font-weight: bold" class="text-center">
                            @if($item->product->unit == 'gram')
                                {{$item->quantity * $item->price / 1000}}
                            @elseif($item->product->unit == 'piece')
                                {{$item->quantity * $item->price}}
                            @endif
                        </td>
                        <td>
                            <?php $print_order_item_attributes = $item->printOrderItemAttributes(); ?>
                                    @foreach($print_order_item_attributes as $order_attributes)
                                        + {{ $order_attributes['value'] }} ({{ $order_attributes['final_price'] }})
                                    @endforeach
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td>
                        <b>Transfered by</b>
                    </td>
                    <td style="font-weight: bold" class="text-center">
                        <b>{{$order_center_system->user->name}}</b>
                    </td>
                    <td style="font-weight: bold" class="text-center">
                        To
                    </td>
                    <td style="font-weight: bold" class="text-center">
                        {{$order->store->name_en}}
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                </tr>
                {{-- employee life cycle except center one --}}
                <?php $counter = 1 ; ?>
                @foreach($order_employee_systems as $order_employee_process)
                  @if($order_employee_process->status == 'preparing')
                  <tr class="preparing-row">
                  @elseif($order_employee_process->status == 'shipping')
                  <tr class="shipping-row">
                  @elseif($order_employee_process->status == 'delivered')
                  <tr class="delivered-row">
                  @elseif($order_employee_process->status == 'rejected')
                  <tr class="rejected-row">
                  @endif
                        <td>
                            <b>{{$counter}}</b>
                        </td>
                        <td style="font-weight: bold" class="text-center">
                            {{$order_employee_process->status}}
                        </td>
                        <td style="font-weight: bold" class="text-center">
                            {{$order_employee_process->employee_note}}
                        </td>
                        <td>
                        </td>
                        <td style="font-weight: bold" class="text-center">
                            {{$order_employee_process->user->name}}
                        </td>
                        <td style="font-weight: bold" class="text-center">
                            {{$order_employee_process->created_at}}
                        </td>
                   </tr>
                   <?php $counter++; ?>
                @endforeach
                @if($order->status == 'pending')
                    <tr>
                        <td>
                            <b>Accept order ?</b>
                        </td>
                        {{-- Accept --}}
                        <form action="{{route('employee.accept.order',$order->id)}}" method="POST">
                          @csrf
                            <td style="font-weight: bold" class="text-center">
                            <button type="submit" class="btn btn-success">Prepare</button>
                            </td>
                        </form>
                        {{-- Reject --}}
                        <form action="{{route('employee.reject.order',$order->id)}}" method="POST">
                            @csrf
                                <td>
                                        <button type="submit" class="btn btn-danger">Reject</button>
                                </td>
                                <td>
                                        <select name="reject_reason" id="reject-reasons" class="form-control" style="background-color: #c00202; color:white">
                                            @foreach($reject_reasons as $reject_reason)
                                                <option value="{{$reject_reason->id}}">{{$reject_reason->name_en}}</option>
                                            @endforeach
                                        </select>
                                </td>
                        </form>
                        <td style="font-weight: bold" class="text-center">
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                    </tr>
                @else
                @if($order->status != 'rejected' && $order->status != 'delivered')
                 <form action="{{route('employee.change.order.status',$order->id)}}" method="POST">
                    @csrf
                    <tr>
                        <td>
                            <b>Change order status to</b>
                        </td>
                        <td style="font-weight: bold" class="text-center">
                            <select name="order_status" id="status-select" class="form-control">
                                @if($order->status == 'preparing')
                                    <option value="shipping">shipping</option>
                                    <option value="delivered">delivered</option>
                                    <option value="rejected" style="background-color: #c00202; color: white;">rejected</option>
                                @elseif($order->status == 'shipping')
                                    <option value="delivered">delivered</option>
                                    <option value="rejected" style="background-color: #c00202; color: white;">rejected</option>
                                @endif
                            </select>
                        </td>
                        <td>
                            <input type="text" name="employee_note" id="employee-note" class="form-control" placeholder="type your note here...">
                            <select name="reject_reason" id="reject-reasons" class="form-control displaynone" style="background-color: #c00202; color:white">
                                @foreach($reject_reasons as $reject_reason)
                                    <option value="{{$reject_reason->id}}">{{$reject_reason->name_en}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td style="font-weight: bold" class="text-center">
                            <button type="submit" class="btn btn-info">Submit</button>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                    </tr>
                </form>
                @endif
                @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@section('scripts')
<script>
  // Set the date we're counting down to
  /*
  var countDownDate = new Date("Jan 5, 2024 15:37:25").getTime();
  */
  var countDownDate = new Date($('#estimated-time').val()).getTime();

  // Update the count down every 1 second
  var x = setInterval(function() {

    // Get today's date and time
    var now = new Date().getTime();

    // Find the distance between now and the count down date
    var distance = countDownDate - now;

    // Time calculations for days, hours, minutes and seconds
    var days = Math.floor(distance / (1000 * 60 * 60 * 24));
    var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((distance % (1000 * 60)) / 1000);

    // Display the result in the element with id="demo"
    document.getElementById("estimated-time-div").innerHTML = days + "d " + hours + "h "
    + minutes + "m " + seconds + "s ";

    // If the count down is finished, write some text
    if (distance < 0) {
      clearInterval(x);
      document.getElementById("estimated-time-div").innerHTML = "EXPIRED";
    }
  }, 1000);
  </script>
  <script>
    $('#status-select').on('change',function(){
      if($(this).val() == 'rejected'){
        $('#employee-note').addClass('displaynone');
        $('#reject-reasons').removeClass('displaynone');
      }
      else{
        $('#employee-note').removeClass('displaynone');
        $('#reject-reasons').addClass('displaynone');
      }
    });
  </script>
@endsection
@endsection
