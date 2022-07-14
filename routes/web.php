<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\InventoryController as AdminInventoryController;
use App\Http\Controllers\Customer\ProductController as ProductController;
use App\Http\Controllers\Customer\CartController as CartController;
use App\Http\Controllers\Admin\UserContoller as UserDashboardContoller;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware'=>['global_data_share']] , function(){


Route::get('/', [Controller::class, 'index'])->name('index');

// Admin
Route::group(['middleware'=>['is_admin','auth']] , function(){
        Route::get('/inventory',[AdminInventoryController::class, 'index'])->name('inventory.index');
        Route::post('/store/product',[AdminInventoryController::class, 'storeProduct'])->name('store.product');
        Route::get('/edit/product/form/{product_id}',[AdminInventoryController::class, 'editProductForm'])->name('edit.product.form');
        Route::post('/update/product/{product_id}',[AdminInventoryController::class, 'updateProduct'])->name('update.product');
        Route::get('/users',[UserDashboardContoller::class, 'index'])->name('show.users');
        Route::get('/customers',[UserDashboardContoller::class, 'showCustomers'])->name('show.customers');
        Route::get('/employees',[UserDashboardContoller::class, 'showEmployees'])->name('show.employees');
        Route::get('/view/user/{user_id}',[UserDashboardContoller::class, 'viewUser'])->name('view.user');
        Route::post('/update/user/{user_id}',[UserDashboardContoller::class, 'update'])->name('update.user');
        Route::post('/store/discount',[AdminInventoryController::class, 'storeNewDiscount'])->name('store.discount');
        Route::get('/edit/discount/{discount_id}',[AdminInventoryController::class, 'editDiscountForm'])->name('edit.discount.form');
        Route::post('/update/discount/{discount_id}',[AdminInventoryController::class, 'updateDiscount'])->name('update.discount');
        Route::post('/store-attribute',[AdminInventoryController::class, 'storeAttribute'])->name('store.attribute');
        Route::post('/store-attribute-value',[AdminInventoryController::class, 'storeAttributeValue'])->name('store.attribute.value');
        Route::get('/orders',[App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders');
        Route::get('/edit/order/{order_id}',[App\Http\Controllers\Admin\OrderController::class, 'editOrder'])->name('edit.order');
        Route::post('/transfer/order/{order_id}',[App\Http\Controllers\Admin\OrderController::class, 'transferOrder'])->name('transfer.order');
        Route::get('/settings' , [App\Http\Controllers\Admin\SettingController::class , 'index'])->name('settings');
        Route::post('/update/settings' , [App\Http\Controllers\Admin\SettingController::class , 'update'])->name('update.settings');
        Route::get('/print/order/{order_id}',[App\Http\Controllers\Admin\OrderController::class, 'printOrder'])->name('print.order');
        Route::get('/roles-permissions',[App\Http\Controllers\Admin\PermissionController::class, 'rolesAndPermissionsIndex'])->name('roles.permissions');
        Route::get('/dashboard-messages',[App\Http\Controllers\Admin\SettingController::class, 'showMessages'])->name('dashboard.messages');
        Route::get('/dashboard-view-message/{message_id}',[App\Http\Controllers\Admin\SettingController::class, 'viewMessage'])->name('dashboard.view.message');
        Route::get('/add-employee',[UserDashboardContoller::class, 'addEmployee'])->name('add.employee');
        Route::post('/store-employee',[UserDashboardContoller::class, 'storeEmployee'])->name('store.employee');
});
/*
Route::get('/login-form',function(){
    return view('auth.login_form');
});
*/

// Employee
Route::group(['middleware'=>['auth','is_employee']] , function(){
    Route::get('/dashboard',[Controller::class, 'adminDashboard'])->name('dashboard')->middleware('auth');
    Route::get('/employee/orders',[App\Http\Controllers\Employee\OrderController::class, 'index'])->name('employee.orders');
    Route::group(['middleware'=>['employee_order_access']] , function(){
        Route::get('/employee/edit/order/{order_id}',[App\Http\Controllers\Employee\OrderController::class, 'editOrder'])->name('employee.edit.order');
        Route::post('/employee/accept/order/{order_id}',[App\Http\Controllers\Employee\OrderController::class, 'acceptOrder'])->name('employee.accept.order');
        Route::post('/employee/reject/order/{order_id}',[App\Http\Controllers\Employee\OrderController::class, 'rejectOrder'])->name('employee.reject.order');
        Route::post('/employee/change/orderStatus/{order_id}',[App\Http\Controllers\Employee\OrderController::class, 'changeStatus'])->name('employee.change.order.status');
        Route::get('/print/delivery/order/{order_id}',[App\Http\Controllers\Employee\OrderController::class, 'printDeliveryOrder'])->name('print.delivery.order');
    });

    Route::get('/check/new/orders',[App\Http\Controllers\Employee\OrderController::class, 'ajaxCheckNewOrders'])->name('check.new.orders');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


// customer
Route::group(['middleware'=>['auth']] , function(){
    Route::post('/add/product/toFavorite/{product_id}', [ProductController::class, 'ProductToFavorite'])->name('add.product.to.favorite');
    Route::post('/remove/product/fromFavorite/{product_id}', [ProductController::class, 'removeFromFavorite'])->name('remove.product.from.favorite'); // for deleting from favorite view without ajax
    Route::get('/view/my-cart', [CartController::class, 'viewCart'])->name('view.cart');
    Route::get('/view/my-favorite', [ProfileController::class, 'viewFavorite'])->name('view.favorite');
    Route::get('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    //Route::get('/checkout/guest',[OrderController::class, 'guestCheckout'])->name('checkout.guest');
    Route::post('/submit/order', [App\Http\Controllers\Customer\OrderController::class, 'submitOrder'])->name('submit.order')->middleware('submit_order');
    //Route::post('/submit/order/as-guest',[App\Http\Controllers\Customer\OrderController::class, 'submitOrderAsGuest'])->name('submit.order.as.guest');
    Route::get('/my-orders', [App\Http\Controllers\Customer\OrderController::class, 'showMyOrders'])->name('my.orders');
    Route::get('/my-profile', [ProfileController::class, 'myProfile'])->name('my.profile');
    Route::post('/submit/profile', [ProfileController::class, 'submitProfile'])->name('submit.profile');
    Route::post('rate/product/{product_id}', [ProductController::class, 'rateProduct'])->name('rate.product');
});


Route::group(['middleware'=>['customer_order_check']] , function(){
    Route::get('/order/details/{order_id}',[App\Http\Controllers\Customer\OrderController::class, 'details'])->name('order.details');
    Route::get('/view/order/{order_id}',[App\Http\Controllers\Customer\OrderController::class, 'viewOrder'])->name('view.order');
});



// display session
Route::get('/session', function(){
    return Session::get('cart');
});


// guest
Route::get('/sign-up', [Controller::class, 'signUpForm'])->name('sign.up');
});
Route::get('/by-category/{category_id}', [ProductController::class, 'indexByCategory'])->name('index.by.category');
Route::get('/filter', [ProductController::class, 'filter'])->name('filter');
Route::get('/product/{product_id}', [ProductController::class, 'viewProduct'])->name('view.product');
Route::post('/add/product/toCart/{product_id}', [ProductController::class, 'addProductToCart'])->name('add.product.to.cart');
Route::post('/delete/cart/item/{cart_item}', [CartController::class, 'deleteCartItem'])->name('delete.cart.item');
Route::post('/delete/cart/content/{cart_id?}', [CartController::class, 'deleteCartContent'])->name('delete.cart.content');
Route::post('/delete/guest/cart/item/{index}', [CartController::class, 'deleteGuestCartItem'])->name('delete.guest.cart.item');
Route::get('/checkout/guest',[OrderController::class, 'guestCheckout'])->name('checkout.guest');
Route::post('/submit/order/as-guest',[App\Http\Controllers\Customer\OrderController::class, 'submitOrderAsGuest'])->name('submit.order.as.guest');
//Route::post('/update/product/inCart/{product_id}', [ProductController::class, 'updateProductCart'])->name('update.product.in.cart');
// guest middleware for specific routes
Route::group(['middleware'=>['is_guest']] , function(){
    Route::get('/view/guest-cart', [CartController::class, 'viewGuestCart'])->name('view.guest.cart');
});
Route::get('/contact-us', [ContactController::class, 'contactUs'])->name('contact.us');
Route::post('/store-contact-form', [ContactController::class, 'storeContactForm'])->name('store.contact.form');

