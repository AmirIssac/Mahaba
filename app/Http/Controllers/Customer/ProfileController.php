<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function myProfile(){
        $user = User::findOrFail(Auth::user()->id);
        $profile = $user->profile;
        $orders_count = $user->orders()->count();
        return view('Customer.profile.view')->with(['user'=>$user,'profile'=>$profile,'orders_count'=>$orders_count]);
    }

    public function submitProfile(Request $request){
        $user = User::findOrFail(Auth::user()->id);
        $profile = $user->profile;
        $profile->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'address_address' => $request->address_address,
            'address_street' => $request->address_street,
            'address_building_apartment' => $request->address_building_apartment,
            'address_latitude' => $request->address_latitude,
            'address_longitude' => $request->address_longitude,
        ]);
        return back();
    }

    public function viewFavorite(){
        $user = User::findOrFail(Auth::user()->id);
        $favorite = $user->favorite;
        $products = $favorite->products;
        return view('Customer.profile.view_favorite',['favorite'=>$favorite,'products'=>$products]);
    }
}
