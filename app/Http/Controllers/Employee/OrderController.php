<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\OrderSystem;
use App\Models\RejectReason;
use App\Models\Shop\Order;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // to paginate on a collection
    public function paginate($items, $perPage = 2, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof \Illuminate\Support\Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
    }
    public function paginateCollection($items, $perPage = 2, $page = null, $options = [])
    {
        $page = $page ?: (\Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
        return new \Illuminate\Pagination\LengthAwarePaginator(array_values($items->forPage($page, $perPage)->toArray()), $items->count(), $perPage, $page, $options);
    }
    public function index(){
        $user = User::findOrFail(Auth::user()->id);
        /*   we stop taking all worker stores orders for paginate purpose
        $stores = $user->stores;   // stores this employee works in
        $orders = collect();
        foreach($stores as $store){
            $store_orders = $store->orders()->orderBy('status')->orderBy('created_at','DESC')->get();
            foreach($store_orders as $store_order)
                $orders->add($store_order);
        }
        */
        /*
        $orders_with_paginate = $this->paginateCollection($orders);
        */

        $stores = $user->stores;
        $user_store = $stores->first();
        $orders = Order::where('store_id',$user_store->id)->orderBy('status')->orderBy('created_at','DESC')->simplePaginate(15);
        $orders_no_paginate = Order::where('store_id',$user_store->id)->get();
        $orders_count = $orders_no_paginate->count();
        // send to view the updated_at timestamp of last pending order when page opened
        $pending_orders = collect();
        foreach($stores as $store){
            $store_orders = $store->orders()->where('status','pending')->orderBy('updated_at','DESC')->get();
            foreach($store_orders as $store_order)
                $pending_orders->add($store_order);
        }
        $last_updated_pending_order_timestamp = $pending_orders->first()->updated_at;

        // orders statistics
        $pending = 0 ;
        $preparing = 0 ;
        $shipping = 0 ;
        $delivered = 0 ;
        $rejected = 0 ;
        foreach($orders_no_paginate as $single_order){
            switch($single_order->status){
                case 'pending' : $pending++ ; break;
                case 'preparing' : $preparing++ ; break;
                case 'shipping' : $shipping++ ; break;
                case 'delivered' : $delivered++ ; break;
                case 'rejected' : $rejected++ ; break;
                default : break;
            }
        $status_arr = array('pending'=>$pending,'preparing'=>$preparing,'shipping'=>$shipping,'delivered'=>$delivered,'rejected'=>$rejected);
        }
        return view('Employee.orders.index',['orders'=>$orders,'last_updated_order_timestamp'=>$last_updated_pending_order_timestamp,
                                                'status_arr'=>$status_arr,'orders_count'=>$orders_count]);
    }
    public function editOrder($id){
        $order = Order::findOrFail($id);
        $stores = Store::all();
        $order_items = $order->orderItems;
        $order_center_system = $order->orderSystems->first();
        $order_employee_systems = $order->orderSystems()->where('id','!=',$order_center_system->id)->get();
        $estimated_time = $order->estimated_time;
        $done_in = $order->finishedIn();
        // get reasons of reject
        $reject_reasons = RejectReason::all();
        return view('Employee.orders.edit',['order'=>$order,'stores'=>$stores,'order_items'=>$order_items,
                    'order_center_system'=>$order_center_system,'order_employee_systems'=>$order_employee_systems,
                    'estimated_time' => $estimated_time,'reject_reasons'=>$reject_reasons,'done_in' => $done_in]);
    }

    public function acceptOrder($id){
        $order = Order::findOrFail($id);
        // validate that this order reference for this employee store
        $store_id = $order->store->id;
        $check_related = User::whereHas('stores', function($q) use ($store_id) {
                $q->where('stores.id', $store_id);
            })->where('users.id',Auth::user()->id)->count();
        if($check_related < 1)  // the order is not in the store that this employee work
                return back();
        // validate this order status is pending
        if($order->status != 'pending')
            return back();

        $order->update([
            'status' => 'preparing',
        ]);
        $order_system = OrderSystem::create([
            'order_id' => $order->id ,
            'user_id' => Auth::user()->id ,
            'status' => 'preparing' ,
        ]);
        return back();
    }

    public function rejectOrder(Request $request,$id){
        $order = Order::findOrFail($id);
        // validate that this order reference for this employee store
        $store_id = $order->store->id;
        $check_related = User::whereHas('stores', function($q) use ($store_id) {
                $q->where('stores.id', $store_id);
            })->where('users.id',Auth::user()->id)->count();
        if($check_related < 1)  // the order is not in the store that this employee work
                return back();
        // validate this order status is pending
        if($order->status != 'pending')
            return back();

        $order->update([
            'status' => 'rejected',
        ]);
        $order_system = OrderSystem::create([
            'order_id' => $order->id ,
            'user_id' => Auth::user()->id ,
            'status' => 'rejected' ,
        ]);
        // rejection note
        $reason_id = $request->reject_reason;
            $order->rejectReasons()->attach($reason_id);
            $reason_note = RejectReason::find($reason_id);
            $order_system->update([
                    'employee_note' => $reason_note->name_en,
            ]);
        return back();
    }

    public function changeStatus(Request $request , $id){
        $order = Order::findOrFail($id);
        // validate
        if($order->status == 'delivered' || $order->status == 'rejected')
            return back();
        $status = $request->order_status;
        $order->update([
            'status' => $status,
        ]);
        $order_system = OrderSystem::create([
            'order_id' => $order->id ,
            'user_id' => Auth::user()->id ,
            'status' => $status ,
            'employee_note' => $request->employee_note ,
        ]);
        // change payment cash status
        $payment_detail = $order->paymentDetail ;
        if($payment_detail->isCash()){
            if($status == 'delivered')
                $payment_detail->update([
                    'status' => 'success',
                ]);
            elseif($status == 'rejected')
                $payment_detail->update([
                    'status' => 'failed',
                ]);
        }
        // check if order rejected so we put the reason
        if($status == 'rejected'){
            $reason_id = $request->reject_reason;
            $order->rejectReasons()->attach($reason_id);
            $reason_note = RejectReason::find($reason_id);
            $order_system->update([
                    'employee_note' => $reason_note->name_en,
                ]);
        }
        return back();
    }

    public function ajaxCheckNewOrders(Request $request){
        $user = User::findOrFail(Auth::user()->id);
        $stores = $user->stores;   // stores this employee works in
        $orders = collect();
        foreach($stores as $store){
            $store_orders = $store->orders()->where('status','pending')->orderBy('updated_at','DESC')->get();
            foreach($store_orders as $store_order)
                $orders->add($store_order);
        }
        // $last_updated_pending_order = $orders->first();
        $new_orders_count = $orders->where('updated_at' , '>' ,  Carbon::parse($request->updated_at) )->count();
        //return response($orders->first());
        return response($new_orders_count);
    }

    public function printDeliveryOrder($id){
        $order = Order::find($id);
        $store = $order->store->name_en;
        $order_items = $order->orderItems;
        return view('Employee.orders.print_delivery_order',['order'=>$order,'store'=>$store,'order_items'=>$order_items,
                                                            ]);
    }
}

