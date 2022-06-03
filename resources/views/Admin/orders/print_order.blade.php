<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="stylesheet" href="style.css">
        <title>DabbaghFoods</title>
    </head>
    <style>
    * {
        font-size: 12px;
        font-family: 'Times New Roman';
    }
    
    td,
    th,
    tr,
    table {
        border-top: 1px solid black;
        border-collapse: collapse;
    }
    
    td.description,
    th.description {
        width: 75px;
        max-width: 75px;
    }
    
    td.quantity,
    th.quantity {
        width: 40px;
        max-width: 40px;
        word-break: break-all;
    }
    
    td.price,
    th.price {
        width: 40px;
        max-width: 40px;
        word-break: break-all;
    }
    
    .centered {
        text-align: center;
        align-content: center;
    }
    
    .ticket {
        width: 155px;
        max-width: 155px;
    }
    
    img {
        max-width: inherit;
        width: inherit;
    }
    @page { margin: 0; }

    @media print {
        .hidden-print,
        .hidden-print * {
            display: none !important;
        }
    }
    </style>
    <body>
        <div class="ticket">
            <img src="{{asset('img/logo.png')}}" alt="Logo">
            <p class="centered">{{$store}}
                <br>{{$order->first_name}} {{$order->last_name}}
                <br>{{$order->phone}}
                <br>{{$order->address}}</p>
            <table>
                <thead>
                    <tr>
                        <th class="quantity">Q.</th>
                        <th class="description">Item</th>
                        <th class="price">AED</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order_items as $item)
                    <tr>
                        <td class="quantity">
                            @if($item->product->isGram())
                                {{$item->quantity / 1000}} K.G
                            @elseif($item->product->isPiece())
                                {{$item->quantity}}
                            @endif
                        </td>
                        <td class="description">{{$item->product->name_en}}</td>
                        <td class="price">
                            @if($item->product->isGram())
                                {{$item->quantity * $item->price / 1000}}
                            @elseif($item->product->isPiece())
                                {{$item->quantity * $item->price}}
                            @endif
                        </td>
                    </tr>
                    @endforeach
                    <tr>
                        <td class="quantity">
                            Tax
                        </td>
                        <td class="description"></td>
                        <td class="price">
                            5%
                        </td>
                    </tr>
                    <tr>
                        <td class="quantity">
                            Total
                        </td>
                        <td class="description"></td>
                        <td class="price">
                            {{$order->total}}
                        </td>
                    </tr>
                    <tr>
                        <td class="quantity">
                            pay
                        </td>
                        <td class="description"></td>
                        <td class="price">
                            {{$order->paymentDetail->provider}}
                        </td>
                    </tr>
                    <tr>
                        <td class="quantity">
                            Emp
                        </td>
                        <td class="description"></td>
                        <td class="price">
                            {{$employee}}
                        </td>
                    </tr>
                    <tr>
                        <td class="quantity">
                            St
                        </td>
                        <td class="description"></td>
                        <td class="price">
                            {{$order->status}}
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="centered">Thanks for chosing DabbaghFoods
                <br>www.dabbaghfoods.com</p>
        </div>
        {{--
        <button id="btnPrint" class="hidden-print">Print</button>
        <script>
            const $btnPrint = document.querySelector("#btnPrint");
            $btnPrint.addEventListener("click", () => {
                window.print();
            });
        </script>
        --}}
        <script>
            // self executing function here
            (function() {
               window.print();
            })();
            </script>
    </body>
</html>nx