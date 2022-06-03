<?php

namespace App\Http\Controllers;

use App\Classes\Dashboard;
use App\Models\Shop\Category;
use App\Models\Shop\Product;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index(){
        $categories = Category::all();
        $products = Product::all();
        return view('index',['categories'=>$categories,'products'=>$products]);
    }

    public function adminDashboard(){
        $dashboard = new Dashboard;
        $orders_by_year = $dashboard->ordersByYearChart(now()->year);
        return view('Admin.dashboard',['orders_by_year'=>$orders_by_year]);
    }

    public function signUpForm(){
        return view('auth.signUp');
    }
}
