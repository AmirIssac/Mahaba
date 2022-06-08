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
                </tr>
                @foreach($order_items as $item)
                    <tr>
                        <td>{{$item->product->name_en}}
                        </td>
                        <td><b>{{$item->quantity/1000}} K.G </b> </td>
                        <td>
                            <span>{{$item->price * $item->quantity / 1000}} AED</span>
                        </td>
                    </tr>
                @endforeach
                <tr style="color: white">
                    <th>Sub total</th>
                    <th>Tax</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td>{{$order->sub_total}} AED</td>
                    <td>{{$order->tax_ratio}}% ({{$order->tax_value}} AED)</td>
                    <td>{{$order->total}} AED<span class="badge badge-success">success</span>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</header>
@section('scripts')
@endsection
@endsection
