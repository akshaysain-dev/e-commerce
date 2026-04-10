<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Middleware\AdminAuth;
use App\Http\Middleware\CustomerAuth;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AttributeController;
use App\Http\Controllers\cartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ProductTypeController;
use App\Http\Controllers\Admin\CouponController as AdminCouponController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\ReturnOrderController;
use App\Http\Controllers\MarginController;
use App\Http\Controllers\Admin\AdminManageController;
use App\Http\Controllers\TaxOrShippingChargeController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Auth\ForgotPasswordController;


Route::get('/', [FrontendController::class, 'home'])->name('home');
Route::get('/product/{id}', [FrontendController::class, 'showSingleProduct'])->name('view_product');
Route::get('/customer/register', [CustomerController::class, 'register'])->name('customer_register');
Route::post('/customer/register', [CustomerController::class, 'store'])->name('customer.store');
Route::get('/customer/login', [AuthController::class, 'customer_login_form'])->name('customer_login');
Route::post('/customer/login', [AuthController::class, 'customer_login'])->name('login_customer');
Route::get('/customer/logout', [AuthController::class, 'customer_logout'])->name('customer_logout');
Route::get('/products', [ProductController::class, 'all_products'])->name('all_products');
Route::get('/category/{id}/{name}', [CategoryController::class, 'view_category'])->name('category');
Route::get('/search', [ProductController::class, 'search'])->name('search');
Route::get('/search/suggest', [ProductController::class, 'suggest'])->name('search.suggest');
Route::post('/ajax/products-by-category', [FrontendController::class, 'productsByCategory'])->name('products.by.category');
Route::post('/product/save-recent', [ProductController::class, 'saveRecent'])->name('product.saveRecent');
Route::get('/sales', [FrontendController::class, 'salePage'])->name('sales.page');
Route::post('/forget-password',[ForgotPasswordController::class,'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetForm']);
Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword']);

Route::get('/customer/{product_id}/{variant_id}/add_cart', [cartController::class, 'addToCart'])->name('addToCart');
Route::get('customer/cart', [CartController::class, 'index'])->name('cart_index');
Route::delete('customer/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.remove');
Route::patch('customer/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');

Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::middleware([CustomerAuth::class])->group(function () {
    Route::get('/customer/profile', [CustomerController::class, 'customer_profile'])->name('customer_profile');
    Route::post('/customer/profile/update', [CustomerController::class, 'customer_profile_update'])->name('customer.profile.update');
    /* Route::get('/customer/{product_id}/{variant_id}/add_cart',[cartController::class,'addToCart'])->name('addToCart');
    Route::get('customer/cart', [CartController::class, 'index'])->name('cart_index');
    Route::delete('customer/cart/remove/{id}', [CartController::class, 'destroy'])->name('cart.remove');
    Route::patch('customer/cart/update/{id}', [CartController::class, 'update'])->name('cart.update'); */
    Route::get('customer/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('customer/placeOrder', [OrderController::class, 'placeOrder'])->name('place.order');
    Route::get('customer/order/success', function () {
        return view('frontend.order-success'); })->name('order.success');
    Route::get('/customer/orders', [CustomerController::class, 'myOrders'])->name('customer.orders');

    Route::post('/customer/checkout/stripe', [CheckoutController::class, 'stripeCheckout'])->name('stripe.payment');
    Route::get('/customer/stripe/success', [OrderController::class, 'stripeSuccess'])->name('stripe.success');
    Route::post('/customer/payment/stripe/initiate', [OrderController::class, 'initiateStripePayment'])->name('stripe.initiate');

    Route::get('/customer/paypal-success', [OrderController::class, 'paypalSuccess'])->name('paypal.success');

    Route::post('/customer/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/customer/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/customer/wishlist/add/{productId}', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/customer/wishlist/remove/{id}', [WishlistController::class, 'remove'])->name('wishlist.remove');

    Route::get('/customer/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::delete('/customer/notifications/clear/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::delete('/customer/notifications/clear-all', [NotificationController::class, 'clearAll'])->name('notifications.clearAll');
    Route::get('/customer/notifications/mark-read', [NotificationController::class, 'markAllRead'])->name('notifications.markRead');
    Route::post('/customer/notifications/read/{id}', [NotificationController::class, 'markOne'])->name('notifications.markOne');

    Route::get('/razorpay-callback', [CheckoutController::class, 'razorpayCallback'])->name('razorpay.callback');

    Route::post('/products/{product}/rate', [RatingController::class, 'store'])->name('ratings.store');
    Route::post('ratings/{rating}/helpful', [RatingController::class, 'helpful'])->name('ratings.helpful');
    Route::delete('product/{id}/ratings/{rating}', [RatingController::class, 'destroy'])->name('ratings.destroy');

    Route::post('/customer/coupon/apply', [CouponController::class, 'apply'])->name('coupon.apply');
    Route::post('/customer/coupon/remove', [CouponController::class, 'remove'])->name('coupon.remove');

    Route::get('/customer/order/{id}', [CustomerController::class, 'view_order'])->name('order');
    Route::post('/order/{id}/cancel', [OrderController::class, 'cancel_order'])->name('order.cancel');
    Route::post('/order/{id}/return', [ReturnOrderController::class, 'store'])->name('order.return');
    Route::get('/order/invoice/download/{id}', [OrderController::class, 'downloadInvoice'])->name('customer.invoice.download');
});

Route::get('/admin', function () {
    if (session('admin_id')) {
        return redirect()->route('admin_dashboard');
    } else {
        return view('admin.login');
    }
})->name('admin');

Route::post('/admin/login', [AuthController::class, 'admin_login'])->name('admin_login');

Route::middleware([AdminAuth::class])->group(function () {
    Route::get('/admin/dashboard', [AuthController::class, 'admin_dashboard'])->name('admin_dashboard');
    Route::get('/admin/logout', [AuthController::class, 'admin_logout'])->name('admin_logout');

    Route::get('/admin/category', [CategoryController::class, 'view'])->name('admin_category');
    Route::get('/admin/create_category', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('admin/add_category', [CategoryController::class, 'add_category'])->name('categories.store');
    Route::get('/admin/category/{id}/edit', [CategoryController::class, 'edit'])->name('category.edit');
    Route::get('/admin/category/{id}/delete', [CategoryController::class, 'delete'])->name('category.delete');
    Route::put('/admin/category/{id}', [CategoryController::class, 'update'])->name('category.update');

    Route::get('admin/product', [ProductController::class, 'show_'])->name('admin_product');
    Route::get('admin/product/create', [ProductController::class, 'add_product'])->name('admin_create_products');
    Route::post('admin/product/add', [ProductController::class, 'store'])->name('admin_add_products');
    Route::get('/products/edit/{id}', [ProductController::class, 'edit'])->name('products.edit');
    Route::get('/products/delete/{id}', [ProductController::class, 'delete'])->name('products.delete');
    Route::put('/products/update/{id}', [ProductController::class, 'update'])->name('products.update');

    Route::get('/admin/customers', [CustomerController::class, 'admin_view'])->name('admin_customers');
    Route::post('/customers/update-status', [CustomerController::class, 'updateStatus'])->name('customers.update-status');
    Route::get('/admin/customer/{id}/delete', [CustomerController::class, 'delete_customer'])->name('delete_customer');

    Route::get('admin/attributes', [AttributeController::class, 'index'])->name('admin.attributes.index');
    Route::post('/attributes', [AttributeController::class, 'storeAttribute'])->name('admin.attributes.store');
    Route::post('/attribute-values', [AttributeController::class, 'storeValue'])->name('admin.values.store');

    Route::get('/admin/orders', [OrderController::class, 'admin_orders'])->name('admin.orders');
    Route::patch('/admin/orders/{order}/status', [OrderController::class, 'update_order_status'])->name('admin.orders.status');

    Route::get('admin/banners', [BannerController::class, 'index'])->name('banners.index');
    Route::post('admin/banners/store', [BannerController::class, 'store'])->name('admin.banners.store');
    Route::get('admin/banners/{id}/edit', [BannerController::class, 'edit'])->name('admin.banners.edit');
    Route::put('admin/banners/{id}/update', [BannerController::class, 'update'])->name('banners.update');
    Route::delete('admin/banners/{id}/destroy', [BannerController::class, 'destroy'])->name('admin.banners.destroy');
    Route::put('/banners/{id}/update', [BannerController::class, 'update'])->name('admin.banners.update');
    Route::post('admin/banners/toggle-status/{id}', [BannerController::class, 'toggleStatus'])->name('banners.toggleStatus');

    Route::get('/product-types', [ProductTypeController::class, 'index'])->name('product-types.index');
    Route::post('/product-types', [ProductTypeController::class, 'store'])->name('product-types.store');

    Route::resource('admin/coupons', AdminCouponController::class)->names('admin.coupons');

    Route::resource('admin/sales', SaleController::class)->names('admin.sales');

    Route::get('admin/tags', [TagController::class, 'index'])->name('admin.tags.index');
    Route::post('admin/tags', [TagController::class, 'store'])->name('admin.tags.store');
    Route::put('admin/tags/{tag}', [TagController::class, 'update'])->name('admin.tags.update');
    Route::delete('admin/tags/{tag}', [TagController::class, 'destroy'])->name('admin.tags.destroy');
    Route::post('admin/products/{product}/tags', [TagController::class, 'assignToProduct'])->name('admin.tags.assign');
    Route::post('admin/tags/{tag}/assign-products', [TagController::class, 'assignProducts'])->name('admin.tags.assign-products');

    Route::get('admin/returns', [ReturnOrderController::class, 'index'])->name('admin.returns.index');

    Route::get('/returns/{id}', [ReturnOrderController::class, 'show'])->name('admin.returns.show');

    Route::post('/returns/{id}/process', [ReturnOrderController::class, 'processRefund'])->name('admin.returns.process');
    Route::get('/admin/stock/alert', [AuthController::class, 'stockAlert'])->name('admin.stock.alert');

    Route::resource('/admin/margins', MarginController::class)->names('admin.margins');

    Route::get('/admin/revenue', [AdminManageController::class, 'getRevenue'])->name('admin.revenue');
    Route::get('/admin/download/revenue', [AdminManageController::class, 'downloadRevenue'])->name('admin.download.revenue');
    Route::get('/admin/customer/orders-record/{id}', [CustomerController::class, 'customer_orders'])->name('admin.customer.orders.records');
    Route::get('/admin/customer/orders/export/{id}', [CustomerController::class, 'exportCustomerOrders'])
        ->name('admin.customer.orders.export');
    Route::get('/admin/tax-sgipping', [TaxOrShippingChargeController::class, 'index'])->name('tax_shipping');
    Route::post('admin/tax-shipping/store', [TaxOrShippingChargeController::class, 'store'])->name('admin.tax_shipping.store');
    Route::get('/admin/delete/{id}', [TaxOrShippingChargeController::class, 'delete'])->name('admin.tax_shipping.delete');

    Route::post('/categories/order', [CategoryController::class, 'updateOrder'])->name('category.order');
    Route::post('/categories/bulk', [CategoryController::class, 'bulkAction'])->name('category.bulk');

    // Sales Report Page (Filter + View)
    Route::get('/admin/sales-report', [ReportController::class, 'index'])->name('sales.report');

    // Export Excel / CSV
    Route::get('/admin/sales-report/export', [ReportController::class, 'export'])->name('sales.export');
    Route::get('/admin/chart-data', [AuthController::class, 'chartData']);
});

Route::get('/products/export', [ProductController::class, 'exportCsv'])->name('products.export');
Route::get('/products/import', [ProductController::class, 'showImportForm'])->name('products.import.form');
Route::post('/products/import', [ProductController::class, 'importCsv'])->name('products.import');
Route::get('/products/import/process', [ProductController::class, 'processImport'])->name('products.import.process');