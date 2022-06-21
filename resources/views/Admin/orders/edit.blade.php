@extends('Layouts.dashboard_main')
@section('links')
<style>
    .pending-row{
        background-color: #cfccca;
    }
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
          <h4 class="card-title"> <span class="badge badge-primary"> #{{$order->number}} </span> </h3>
            @if($order->store)
            <a style="float: right" href="{{route('print.order',$order->id)}}" class="btn btn-info">print</a>
            @endif
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
                    <b style="color: #ff7300"> {{$order->status}} </b>
                    @elseif($order->status == 'preparing' || $order->status == 'shipping')
                    <b style="color: #04558b"> {{$order->status}} </b>
                    @elseif($order->status == 'delivered')
                    <b style="color: #069e1f"> {{$order->status}} </b>
                    @elseif($order->status == 'failed' || $order->status == 'cancelled' || $order->status == 'rejected')
                    <b style="color: #c00202"> {{$order->status}} </b>
                    @endif
                  </td>
                  <td style="font-weight: bold; color: #38b818;" class="text-center">{{$order->total}}</td>
                  <td class="text-center">{{$order->address}}</td>
                  <td class="text-center">{{$order->paymentDetail->provider}}</td>
                  @if($order->paymentDetail->status == 'pending')
                    <td style="color: #ff7300" class="text-center">{{$order->paymentDetail->status}}</td>
                  @elseif($order->paymentDetail->status == 'success')
                    <td style="color: #069e1f" class="text-center">{{$order->paymentDetail->status}}</td>
                  @elseif($order->paymentDetail->status == 'failed')
                    <td style="color: #c00202" class="text-center">{{$order->paymentDetail->status}}</td>
                  @endif
                </tr>
                <tr>
                    <th>
                      Email
                    </th>
                    <th style="font-weight: bold" class="text-center">
                      Phone
                    </th>
                    <th style="font-weight: bold" class="text-center">
                      Note
                    </th>
                    <th style="font-weight: bold" class="text-center">
                      Discount
                    </th>
                    <th style="font-weight: bold" class="text-center">
                      Discount type
                    </th>
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
                    <th>
                    </th>
                 </tr>
                 <tr>
                    <td>
                      {{$order->email}}
                    </td>
                    <td class="text-center">
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
                      @if($order_discount)
                        <b style="color: #74690c">
                        {{$order_discount->value}}
                        </b>
                      @else
                        <span class="badge badge-danger">none</span>
                      @endif
                    </td>
                    <td class="text-center">
                      @if($order_discount)
                        <b style="color: #74690c">
                        {{$order_discount->type}} / {{$order_discount->code}}
                        </b>
                      @else
                        /
                      @endif
                    </td>
                    @if($order->status != 'delivered' && $order->status != 'rejected')
                      <td style="color: #c00202; font-weight: bold" class="text-center">
                        <input type="hidden" id="estimated-time" value="{{$estimated_time}}">
                        <p id="estimated-time-div"></p>
                      </td>
                    @else
                    <td style="color: #0254c0; font-weight: bold" class="text-center">
                      <p>{{$done_in}}</p>
                    </td>
                    @endif
                    <td class="text-center"></td>
                    <td class="text-center">
                    </td>
                 </tr>
                 <tr>   {{-- order items --}}
                    <th>
                      Code
                    </th>
                    <th style="font-weight: bold" class="text-center">
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
                    <th style="font-weight: bold" class="text-center">
                      Total
                    </th>
                </tr>
                @foreach($order_items as $item)
                    <tr>
                        <td>
                          {{$item->product->code}}
                        </td>
                        <td style="font-weight: bold" class="text-center">
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
                            @foreach($item->item_attributes as $item_attr)
                          + {{ $item_attr['value'] }} ({{ $item_attr['price'] }})
                            @endforeach
                        </td>
                        <td style="font-weight: bold; color: #38b818;" class="text-center">
                          @if($loop->last)
                              {{$order->total}}
                          @endif
                        </td>
                    </tr>
                @endforeach
                @if(isset($order_center_system))
                {{-- center transfer process --}}
                @if($order_center_system->status == 'pending')
                <tr class="pending-row">
                      <td>
                          <b>1</b>
                      </td>
                      <td style="font-weight: bold" class="text-center">
                        {{--  {{$order_center_system->status}}  --}}
                        transfered
                      </td>
                      <td>
                      </td>
                      <td>
                      </td>
                      <td>
                      </td>
                      <td style="font-weight: bold" class="text-center">
                        {{$order_center_system->created_at}}
                      </td>
                @elseif($order_center_system->status == 'rejected')
                      <tr class="rejected-row">
                        <td>
                            <b>1</b>
                        </td>
                        <td style="font-weight: bold" class="text-center">
                          {{--  {{$order_center_system->status}}  --}}
                          Rejected
                        </td>
                        <td style="font-weight: bold" class="text-center">
                          @foreach($order->rejectReasons as $reason)
                            {{$reason->name_en}}
                          @endforeach
                        </td>
                      <td>
                      </td>
                      <td>
                      </td>
                      <td>
                      </td>
                      <td style="font-weight: bold" class="text-center">
                        {{$order_center_system->created_at}}
                      </td>
                @endif
                </tr>
                {{-- employee life cycle except center one --}}
                <?php $counter = 2 ; ?>
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
                   @endif
                    @if(!$order->store && $order->status != 'rejected')  {{-- الطلب غير محول بعد --}}
                    <form action="{{route('transfer.order',$order->id)}}" method="POST">
                      @csrf
                        <tr>
                            <td style="color: #dd2222; font-weight: bold;">
                                Transfer the order to
                            </td>
                            <td style="font-weight: bold" class="text-center">
                                <select name="store_id" id="store-select" class="form-control">
                                    @foreach($stores as $store)
                                        <option value="{{$store->id}}">{{$store->name_en}}</option>
                                    @endforeach
                                    <option value="reject" style="background-color: #c00202; color:white">Reject</option>
                                </select>
                            </td>
                            <td>
                              <input type="text" name="admin_note" id="admin-note" class="form-control" placeholder="additional note...">
                              <select name="reject_reason" id="reject-reasons" class="form-control displaynone" style="background-color: #c00202; color:white">
                                @foreach($reject_reasons as $reject_reason)
                                    <option value="{{$reject_reason->id}}">{{$reject_reason->name_en}}</option>
                                @endforeach
                              </select>
                            </td>
                            <td style="font-weight: bold" class="text-center">
                              <button type="submit" class="btn btn-primary">confirm</button>
                            </td>
                            <td>
                            </td>
                            <td></td>
                        </tr>
                    </form>
                    @elseif($order->orderSystems()->count() == 1 && $order->status != 'rejected')  {{-- الطلب محول ولكن لم يستلمه الكاشير بعد --}}
                      <tr>
                        <td style="font-weight: bold;">
                            Order in
                        </td>
                        <td style="color: #38b818; font-weight: bold;" class="text-center">
                            <b>{{$order_store->name_en}}</b>
                        </td>
                        <td style="font-weight: bold" class="text-center">
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td>
                        </td>
                      </tr>
                      {{--
                      <form action="{{route('transfer.order',$order->id)}}" method="POST">
                        @csrf
                        <tr>
                          <td style="color: #dd2222; font-weight: bold;">
                            Change order to
                          </td>
                          <td style="font-weight: bold" class="text-center">
                              <select name="store_id" class="form-control">
                                  @foreach($stores as $store)
                                      @if($order->store_id == $store->id)
                                      <option value="{{$store->id}}" selected>{{$store->name_en}}</option>
                                      @else
                                      <option value="{{$store->id}}">{{$store->name_en}}</option>
                                      @endif
                                  @endforeach
                          </select>
                          <input type="hidden" name="change_order_transfer" value="yes">
                          </td>
                          <td style="font-weight: bold" class="text-center">
                                <button class="btn btn-primary">confirm</button>
                          </td>
                          <td>
                          </td>
                          <td>
                          </td>
                          <td>
                          </td>
                        </tr>
                      </form>
                      --}}
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
    $('#store-select').on('change',function(){
      if($(this).val() == 'reject'){
        $('#admin-note').addClass('displaynone');
        $('#reject-reasons').removeClass('displaynone');
      }
      else{
        $('#admin-note').removeClass('displaynone');
        $('#reject-reasons').addClass('displaynone');
      }
    });
  </script>
@endsection
@endsection
