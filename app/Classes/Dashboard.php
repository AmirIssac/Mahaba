<?php
namespace App\Classes;

use App\Models\Shop\Order;
use App\Models\Shop\PaymentDetail;

class Dashboard
{
    public function ordersByYearChart($year){
        // all orders
        $january = Order::whereYear('created_at',$year)->whereMonth('created_at',1)->count();
        $february = Order::whereYear('created_at',$year)->whereMonth('created_at',2)->count();
        $march = Order::whereYear('created_at',$year)->whereMonth('created_at',3)->count();
        $april = Order::whereYear('created_at',$year)->whereMonth('created_at',4)->count();
        $may = Order::whereYear('created_at',$year)->whereMonth('created_at',5)->count();
        $june = Order::whereYear('created_at',$year)->whereMonth('created_at',6)->count();
        $july = Order::whereYear('created_at',$year)->whereMonth('created_at',7)->count();
        $august = Order::whereYear('created_at',$year)->whereMonth('created_at',8)->count();
        $september = Order::whereYear('created_at',$year)->whereMonth('created_at',9)->count();
        $october = Order::whereYear('created_at',$year)->whereMonth('created_at',10)->count();
        $november = Order::whereYear('created_at',$year)->whereMonth('created_at',11)->count();
        $december = Order::whereYear('created_at',$year)->whereMonth('created_at',12)->count();
        $orders = array($january,$february,$march,$april,$may,$june,$july,$august,
                       $september,$october,$november,$december);
        // delivered orders
        $january = Order::where('status','delivered')->whereYear('created_at',$year)->whereMonth('created_at',1)->count();
        $february = Order::where('status','delivered')->whereYear('created_at',$year)->whereMonth('created_at',2)->count();
        $march = Order::where('status','delivered')->whereYear('created_at',$year)->whereMonth('created_at',3)->count();
        $april = Order::where('status','delivered')->whereYear('created_at',$year)->whereMonth('created_at',4)->count();
        $may = Order::where('status','delivered')->whereYear('created_at',$year)->whereMonth('created_at',5)->count();
        $june = Order::where('status','delivered')->whereYear('created_at',$year)->whereMonth('created_at',6)->count();
        $july = Order::where('status','delivered')->whereYear('created_at',$year)->whereMonth('created_at',7)->count();
        $august = Order::where('status','delivered')->whereYear('created_at',$year)->whereMonth('created_at',8)->count();
        $september = Order::where('status','delivered')->whereYear('created_at',$year)->whereMonth('created_at',9)->count();
        $october = Order::where('status','delivered')->whereYear('created_at',$year)->whereMonth('created_at',10)->count();
        $november = Order::where('status','delivered')->whereYear('created_at',$year)->whereMonth('created_at',11)->count();
        $december = Order::where('status','delivered')->whereYear('created_at',$year)->whereMonth('created_at',12)->count();
        $delivered = array($january,$february,$march,$april,$may,$june,$july,$august,
                       $september,$october,$november,$december);
        return array('orders_count'=>$orders,'delivered'=>$delivered);
    }

    public function financialByYearChart($year){
        $cash = 0 ;
        $card = 0 ;
        $january_payments = PaymentDetail::where('status','success')->whereHas('Order', function($q) use ($year){
            $q->whereYear('created_at',$year)->whereMonth('created_at',1);
        })->get();
        foreach($january_payments as $jan_pay){
            if($jan_pay->provider == 'cash')
                $cash += $jan_pay->amount;
            else
                $card += $jan_pay->amount;
        }
        $array = array('jan_cash' => $cash , 'jan_card' => $card);
        $cash = 0 ;
        $card = 0 ;
        $february_payments = PaymentDetail::where('status','success')->whereHas('Order', function($q) use ($year){
            $q->whereYear('created_at',$year)->whereMonth('created_at',1);
        })->get();
        foreach($february_payments as $february_pay){
            if($february_pay->provider == 'cash')
                $cash += $february_pay->amount;
            else
                $card += $february_pay->amount;
        }
        $cash = 0 ;
        $card = 0 ;
        $march_payments = PaymentDetail::where('status','success')->whereHas('Order', function($q) use ($year){
            $q->whereYear('created_at',$year)->whereMonth('created_at',1);
        })->get();
        foreach($march_payments as $march_pay){
            if($march_pay->provider == 'cash')
                $cash += $march_pay->amount;
            else
                $card += $march_pay->amount;
        }
        $cash = 0 ;
        $card = 0 ;
        $april_payments = PaymentDetail::where('status','success')->whereHas('Order', function($q) use ($year){
            $q->whereYear('created_at',$year)->whereMonth('created_at',1);
        })->get();
        foreach($april_payments as $april_pay){
            if($april_pay->provider == 'cash')
                $cash += $april_pay->amount;
            else
                $card += $april_pay->amount;
        }
        $cash = 0 ;
        $card = 0 ;
        $may_payments = PaymentDetail::where('status','success')->whereHas('Order', function($q) use ($year){
            $q->whereYear('created_at',$year)->whereMonth('created_at',1);
        })->get();
        foreach($may_payments as $may_pay){
            if($may_pay->provider == 'cash')
                $cash += $may_pay->amount;
            else
                $card += $may_pay->amount;
        }
        $cash = 0 ;
        $card = 0 ;
        $june_payments = PaymentDetail::where('status','success')->whereHas('Order', function($q) use ($year){
            $q->whereYear('created_at',$year)->whereMonth('created_at',1);
        })->get();
        foreach($june_payments as $june_pay){
            if($june_pay->provider == 'cash')
                $cash += $june_pay->amount;
            else
                $card += $june_pay->amount;
        }
        $cash = 0 ;
        $card = 0 ;
        $july_payments = PaymentDetail::where('status','success')->whereHas('Order', function($q) use ($year){
            $q->whereYear('created_at',$year)->whereMonth('created_at',1);
        })->get();
        foreach($july_payments as $july_pay){
            if($july_pay->provider == 'cash')
                $cash += $july_pay->amount;
            else
                $card += $july_pay->amount;
        }
        $cash = 0 ;
        $card = 0 ;
        $august_payments = PaymentDetail::where('status','success')->whereHas('Order', function($q) use ($year){
            $q->whereYear('created_at',$year)->whereMonth('created_at',1);
        })->get();
        foreach($august_payments as $august_pay){
            if($august_pay->provider == 'cash')
                $cash += $august_pay->amount;
            else
                $card += $august_pay->amount;
        }
        $cash = 0 ;
        $card = 0 ;
        $september_payments = PaymentDetail::where('status','success')->whereHas('Order', function($q) use ($year){
            $q->whereYear('created_at',$year)->whereMonth('created_at',1);
        })->get();
        foreach($september_payments as $september_pay){
            if($september_pay->provider == 'cash')
                $cash += $september_pay->amount;
            else
                $card += $september_pay->amount;
        }
        $cash = 0 ;
        $card = 0 ;
        $october_payments = PaymentDetail::where('status','success')->whereHas('Order', function($q) use ($year){
            $q->whereYear('created_at',$year)->whereMonth('created_at',1);
        })->get();
        foreach($october_payments as $october_pay){
            if($october_pay->provider == 'cash')
                $cash += $october_pay->amount;
            else
                $card += $october_pay->amount;
        }
        $cash = 0 ;
        $card = 0 ;
        $november_payments = PaymentDetail::where('status','success')->whereHas('Order', function($q) use ($year){
            $q->whereYear('created_at',$year)->whereMonth('created_at',1);
        })->get();
        foreach($november_payments as $november_pay){
            if($november_pay->provider == 'cash')
                $cash += $november_pay->amount;
            else
                $card += $november_pay->amount;
        }
        $cash = 0 ;
        $card = 0 ;
        $december_payments = PaymentDetail::where('status','success')->whereHas('Order', function($q) use ($year){
            $q->whereYear('created_at',$year)->whereMonth('created_at',1);
        })->get();
        foreach($december_payments as $december_pay){
            if($december_pay->provider == 'cash')
                $cash += $december_pay->amount;
            else
                $card += $december_pay->amount;
        }
       // $orders = array($january,$february,$march,$april,$may,$june,$july,$august,
               //        $september,$october,$november,$december);
    }
}

?>