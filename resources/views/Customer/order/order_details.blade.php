@extends('Layouts.main')
@section('links')
<style>
    .table {
        color: wheat;
    }
</style>
@endsection
@section('body')
<!-- Header-->
<header class="bg-prim py-5">
    <div class="container px-4 px-lg-5 my-5">
        <div class="text-center text-white product-div">
            <caption><b>#{{$order->number}}</b></caption>
            <table class="table">
                <tr>
                    <th>Product</th>
                    <th>QTY</th>
                    <th>Price</th>
                    <th>
                        Extras
                    </th>
                </tr>
                @foreach($order_items as $item)
                    <tr>
                        <td>{{$item->product->name_en}}
                        </td>
                        <td>
                            <b>
                            @if($item->product->isGram())
                            {{$item->quantity/1000}} K.G
                            @elseif($item->product->isPiece())
                            {{$item->quantity}}
                            @endif
                            </b>
                        </td>
                        <td>
                            <span>
                                @if($item->product->isGram())
                                {{$item->price * $item->quantity / 1000}} AED
                                @elseif($item->product->isPiece())
                                {{$item->price * $item->quantity}} AED
                                @endif
                            </span>
                        </td>
                        <td>
                            @foreach($item->item_attributes as $attr)
                                +{{ $attr['value'] }}
                                {{--
                                 ({{ $attr->printAttributeValuePrice($item->product->id) }})
                                 --}}
                            @endforeach
                        </td>
                    </tr>
                @endforeach
                <tr style="color: white">
                    <th>Sub total</th>
                    <th>Tax</th>
                    <th>Total</th>
                    <th></th>
                </tr>
                <tr>
                    <td>{{$order->sub_total}} AED</td>
                    <td>{{$order->tax_ratio}}% ({{$order->tax_value}} AED)</td>
                    <td>{{$order->total}} AED<span class="badge badge-success">success</span>
                    </td>
                    <td></td>
                </tr>
                <tr>
                <td>
                    شكرا لشرائك من الدباغ
                </td>
                <td>
                    @isset($reference)
                    هذا الرمز لتتمكن من متابعة حالة طلبك <span class="badge badge-success">{{ $reference }}</span>
                    @endisset
                </td>
                <td></td><td></td>
                </tr>
            </table>
        </div>
    </div>
</header>
@section('scripts')
@endsection
@endsection
