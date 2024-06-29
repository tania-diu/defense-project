<?php

use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\CustomerController;
use App\Http\Controllers\Frontend\SubscribersController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Backend\Payments\Paypal\PaypalController;
use App\Http\Controllers\Backend\Payments\Stripe\StripePaymentController;
use App\Http\Controllers\Frontend\AddressController;
use App\Http\Controllers\Frontend\CartsController;
use App\Http\Controllers\Frontend\ContactUsController;
use App\Http\Controllers\Frontend\WishlistController;
use App\Http\Controllers\MediaManagerController;
use App\Http\Controllers\Backend\Payments\Paytm\PaytmPaymentController;
use App\Http\Controllers\Backend\Payments\Razorpay\RazorpayController;
use App\Http\Controllers\Frontend\OrderTrackingController;
use Illuminate\Support\Facades\Route;

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

Auth::routes(['verify' => true]);

Route::controller(LoginController::class)->group(function () {
    Route::get('/logout', 'logout')->name('logout');
    Route::get('/social-login/redirect/{provider}', 'redirectToProvider')->name('social.login');
    Route::get('/social-login/{provider}/callback', 'handleProviderCallback')->name('social.callback');
});

Route::controller(VerificationController::class)->group(function () {
    Route::get('/email/resend', 'resend')->name('verification.resend');
    Route::get('/verification-confirmation/{code}', 'verification_confirmation')->name('email.verification.confirmation');
});

Route::get('/theme/{name?}', [HomeController::class, 'theme'])->name('theme.change');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/brands', [HomeController::class, 'allBrands'])->name('home.brands');
Route::get('/categories', [HomeController::class, 'allCategories'])->name('home.categories');

# products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::post('/products/get-variation-info', [ProductController::class, 'getVariationInfo'])->name('products.getVariationInfo');
Route::post('/products/show-product-info', [ProductController::class, 'showInfo'])->name('products.showInfo');

# carts
Route::get('/carts', [CartsController::class, 'index'])->name('carts.index');
Route::post('/add-to-cart', [CartsController::class, 'store'])->name('carts.store');
Route::post('/update-cart', [CartsController::class, 'update'])->name('carts.update');
Route::post('/apply-coupon', [CartsController::class, 'applyCoupon'])->name('carts.applyCoupon');
Route::get('/clear-coupon', [CartsController::class, 'clearCoupon'])->name('carts.clearCoupon');

# blogs
Route::get('/blogs', [HomeController::class, 'allBlogs'])->name('home.blogs');
Route::get('/blogs/{slug}', [HomeController::class, 'showBlog'])->name('home.blogs.show');

# campaigns
Route::get('/campaigns', [HomeController::class, 'campaignIndex'])->name('home.campaigns');
Route::get('/campaigns/{slug}', [HomeController::class, 'showCampaign'])->name('home.campaigns.show');

# coupons
Route::get('/coupons', [HomeController::class, 'allCoupons'])->name('home.coupons');

# pages
Route::get('/pages/about-us', [HomeController::class, 'aboutUs'])->name('home.pages.aboutUs');
Route::get('/pages/contact-us', [HomeController::class, 'contactUs'])->name('home.pages.contactUs');
Route::get('/pages/{slug}', [HomeController::class, 'showPage'])->name('home.pages.show');

# contact us message
Route::post('/contact-us', [ContactUsController::class, 'store'])->name('contactUs.store');

# Subscribed Users
Route::post('/subscribers', [SubscribersController::class, 'store'])->name('subscribe.store');

# addresses
Route::post('/get-states', [AddressController::class, 'getStates'])->name('address.getStates');
Route::post('/get-cities', [AddressController::class, 'getCities'])->name('address.getCities');

# authenticated routes
Route::group(['prefix' => '', 'middleware' => ['customer', 'verified', 'isBanned']], function () {
    # customer routes 
    Route::get('/customer-dashboard', [CustomerController::class, 'index'])->name('customers.dashboard');
    Route::get('/customer-order-history', [CustomerController::class, 'orderHistory'])->name('customers.orderHistory');
    Route::get('/customer-address', [CustomerController::class, 'address'])->name('customers.address');
    Route::get('/customer-profile', [CustomerController::class, 'profile'])->name('customers.profile');
    Route::post('/customer-profile', [CustomerController::class, 'updateProfile'])->name('customers.updateProfile');

    # wishlist
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('customers.wishlist');
    Route::post('/add-to-wishlist', [WishlistController::class, 'store'])->name('customers.wishlist.store');
    Route::get('/delete-wishlist/{id}', [WishlistController::class, 'delete'])->name('customers.wishlist.delete');

    # checkout 
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.proceed');
    Route::post('/get-checkout-logistics', [CheckoutController::class, 'getLogistic'])->name('checkout.getLogistic');
    Route::post('/shipping-amount', [CheckoutController::class, 'getShippingAmount'])->name('checkout.getShippingAmount');
    Route::post('/checkout-complete', [CheckoutController::class, 'complete'])->name('checkout.complete');
    Route::get('/orders/{code}/invoice', [CheckoutController::class, 'success'])->name('checkout.success');

    # address
    Route::post('/new-address', [AddressController::class, 'store'])->name('address.store');
    Route::post('/edit-address', [AddressController::class, 'edit'])->name('address.edit');
    Route::post('/update-address', [AddressController::class, 'update'])->name('address.update');
    Route::get('/delete-address/{id}', [AddressController::class, 'delete'])->name('address.delete');

    # order tracking
    Route::get('/track-order', [OrderTrackingController::class, 'index'])->name('customers.trackOrder');
});

# media files routes
Route::group(['prefix' => '', 'middleware' => ['auth']], function () {
    Route::get('/media-manager/get-files', [MediaManagerController::class, 'index'])->name('uppy.index');
    Route::get('/media-manager/get-selected-files', [MediaManagerController::class, 'selectedFiles'])->name('uppy.selectedFiles');
    Route::post('/media-manager/add-files', [MediaManagerController::class, 'store'])->name('uppy.store');
    Route::get('/media-manager/delete-files/{id}', [MediaManagerController::class, 'delete'])->name('uppy.delete');
});

# payment gateways
Route::group(['prefix' => ''], function () {
    # paypal
    Route::get('/paypal/success', [PaypalController::class, 'success'])->name('paypal.success');
    Route::get('/paypal/cancel', [PaypalController::class, 'cancel'])->name('paypal.cancel');

    # stripe
    Route::any('/stripe/create-session', [StripePaymentController::class, 'checkoutSession'])->name('stripe.checkoutSession');
    Route::get('/stripe/success', [StripePaymentController::class, 'success'])->name('stripe.success');
    Route::get('/stripe/cancel', [StripePaymentController::class, 'cancel'])->name('stripe.cancel');

    # paytm
    Route::any('/paytm/callback', [PaytmPaymentController::class, 'callback'])->name('paytm.callback');

    # razorpay
    Route::post('razorpay/payment', [RazorpayController::class, 'payment'])->name('razorpay.payment');
});
