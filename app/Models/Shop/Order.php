<?php

namespace App\Models\Shop;

use App\Models\DiscountDetail;
use App\Models\GuestReference;
use App\Models\OrderSystem;
use App\Models\RejectReason;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'store_id',
        'number',
        'status',
        'sub_total',
        'tax_ratio',
        'tax_value',
        'shipping',
        'total',
        'first_name',
        'last_name',
        'phone',
        'email',
        'address',
        'address_street',
        'address_building_apartment',
        'customer_note',
        'employee_note',
        'estimated_time',
    ];

    protected $dates = ['estimated_time'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderSystems()
    {
        return $this->hasMany(OrderSystem::class);
    }

    /*
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    */
    public function paymentDetail()
    {
        return $this->hasOne(PaymentDetail::class);
    }

    public function discountDetail()
    {
        return $this->hasOne(DiscountDetail::class);
    }

    public function rejectReasons(){
        return $this->belongsToMany(RejectReason::class);
    }

    public function reference()
    {
        return $this->hasOne(GuestReference::class);
    }

    /*
        calc the time between creating the order by customer
        and delivery or reject status (finished status)
    */
    public function finishedIn(){
        if($this->status == 'delivered' || $this->status == 'rejected'){
            $last_process_time = $this->orderSystems->last()->created_at;
            //$done_in = $last_process_time->diffInSeconds($this->created_at);
            //$done_in = gmdate('H:i:s', $done_in);
            $interval = $last_process_time->diff($this->created_at);
            $done_in = $interval->format('%ad %H:%I:%S');
            // return the array(Time $finished_time && Boolean $check !!-- late or not !!)
            $estimated_time = $this->estimated_time->diff($this->created_at);
            $estimated_time = $estimated_time->format('%ad %H:%I:%S');
            $check = false;
            if($estimated_time > $done_in)
                $check = true;
            $finished_arr = array('finished_time' => $done_in,'check' => $check);
            return $finished_arr;
        }
        else{
            $finished_arr = false;
            return $finished_arr;
        }
    }

    public function isRejected(){
        if($this->status == 'rejected' || $this->status == 'failed' || $this->status == 'cancelled')
            return true ;
        else
            return false ;
    }
}
