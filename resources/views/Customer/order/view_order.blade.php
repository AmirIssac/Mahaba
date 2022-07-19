@extends('Layouts.main')
@section('links')

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
                            <span>  {{$order->total}}  </span>
                        </td>
                        <td>
                            {{isset($order->store) ? $order->store->name_en : '/'}}
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

                            <?php $print_order_item_attributes = $item->printOrderItemAttributes(); ?>
                                @foreach($print_order_item_attributes as $order_attributes)
                                    + {{ $order_attributes['value'] }} ({{ $order_attributes['final_price'] }})
                                @endforeach
                        </td>
                        <td>
                            @if($item->product->isGram())
                                {{$item->quantity / 1000}} K.G
                            @elseif($item->product->isPiece())
                                {{$item->quantity}}
                            @endif
                        </td>
                        <td>
                            @if($item->product->isGram())
                                {{$item->quantity * $item->price / 1000}}
                            @elseif($item->product->isPiece())
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
</header>
@section('scripts')
@endsection
@endsection
