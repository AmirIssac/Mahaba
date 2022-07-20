<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shop\Cart;
use App\Models\Shop\Favorite;
use App\Models\Shop\Profile;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserContoller extends Controller
{
    //

    public function index(){
        $customers = User::whereHas('roles', function (Builder $query) {
            $query->where('name','customer');
        })->orderBy('created_at','DESC')->simplePaginate(6);
        $super_admins = User::whereHas('roles', function (Builder $query) {
            $query->where('name','super_admin');
        })->get();
        $admins = User::whereHas('roles', function (Builder $query) {
            $query->where('name','admin');
        })->get();
        $employees = User::whereHas('roles', function (Builder $query) {
            $query->where('name','employee');
        })->with('stores')->get();
        return view('Admin.users.index',['customers' => $customers , 'super_admins' => $super_admins ,
                                        'admins' => $admins , 'employees' => $employees]);
    }

    public function showCustomers(){
        $customers = User::whereHas('roles', function (Builder $query) {
            $query->where('name','customer');
        })->orderBy('created_at','DESC')->with(['profile','orders'])->simplePaginate(15);
        return view('Admin.users.customers',['customers'=>$customers]);
    }

    public function showEmployees(){
        $employees = User::whereHas('roles', function (Builder $query) {
            $query->where('name','employee');
        })->orderBy('created_at','DESC')->with(['profile'])->simplePaginate(15);
        return view('Admin.users.employees',['employees'=>$employees]);
    }

    public function viewUser($id){
        $person = User::findOrFail($id);
        $all_stores = Store::all();
        $user_stores = $person->stores;
        $orders_count = $person->orders->count();
        return view('Admin.users.view_user',['person' => $person,'user_stores'=>$user_stores,'orders_count' => $orders_count,'all_stores' => $all_stores]);
    }

    public function update(Request $request , $id){
        $user = User::findOrFail($id);
        $profile = $user->profile;
        if ($request->filled('confirm_password') && $request->new_password == $request->confirm_password)
            $user->update([
                'email' => $request->email ,
                'name' => $request->first_name ,
                'password' => Hash::make($request->new_password),
            ]);
        else
            $user->update([
                    'email' => $request->email ,
                    'name' => $request->first_name ,

            ]);
        $profile->update([
            'first_name' => $request->first_name ,
            'last_name' => $request->last_name ,
            'phone' => $request->phone ,
            'address_address' => $request->address ,
        ]);
        // attach stores
        $user->stores()->sync($request->stores);
        Session::flash('success', 'Updated successfully');
        return back();
    }

    public function addEmployee(){
        $stores = Store::get();
        return view('Admin.users.add_employee',['stores'=>$stores]);
    }

    public function storeEmployee(Request $request){
        $user = User::create([
            'name' => $request->first_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $phone = '00971'.$request->phone;
        Profile::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $phone,
            'address_address' => $request->address,
            'address_latitude' => null,
            'address_longitude' => null,
        ]);
        Cart::create([
            'user_id' => $user->id,
        ]);
        Favorite::create([
            'user_id' => $user->id,
        ]);
        $user->assignRole('employee');
        $store_id = $request->store_id;
        $user->stores()->attach($store_id);
        return back();
    }
}
