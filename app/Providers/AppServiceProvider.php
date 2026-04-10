<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use App\Models\Cart;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use App\Models\Wishlist;
use App\Models\Notification;
use Illuminate\Support\ServiceProvider;
use Stripe\StripeClient;
use App\Services\Payment\{
    StripePaymentService,
    PayPalPaymentService,
    RazorpayPaymentService,
    CodPaymentService,
    PaymentGatewayFactory,
    StripeCallbackService,
    PayPalCallbackService
};
use App\Services\{CartService, AddressResolver, OrderService};
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(StripeClient::class, fn() =>
            new StripeClient(config('services.stripe.secret'))
        );

        // Core services
        $this->app->singleton(OrderService::class);
        $this->app->singleton(CartService::class);
        $this->app->singleton(AddressResolver::class);

        // Payment gateway services
        $this->app->singleton(StripePaymentService::class);
        $this->app->singleton(PayPalPaymentService::class);
        $this->app->singleton(RazorpayPaymentService::class);
        $this->app->singleton(CodPaymentService::class);
        $this->app->singleton(PaymentGatewayFactory::class);

        // Callback services (stripeSuccess / paypalSuccess)
        $this->app->singleton(StripeCallbackService::class);
        $this->app->singleton(PayPalCallbackService::class);
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $cart_count     = 0;
            $wishlist_count = 0;
            $notif_count    = 0;
            $notifications  = collect();
            $category_globle = Category::all();

            if (session()->has('customer_id')) {
                $customerId = Session::get('customer_id');

                $cart_count     = Cart::where('customer_id', $customerId)->count();
                $wishlist_count = Wishlist::where('customer_id', $customerId)->count();
                $notif_count    = Notification::where('customer_id', $customerId)->where('is_read', 0)->count();
                $notifications  = Notification::where('customer_id', $customerId)->where('is_read', 0)->latest()->take(5)->get();
            }

            $view->with('cart_count',     $cart_count);
            $view->with('wishlist_count', $wishlist_count);
            $view->with('notif_count',    $notif_count);
            $view->with('notifications',  $notifications);
            $view->with('category_globle', $category_globle);
        });

        Paginator::useBootstrapFive();
        /* URL::forceRootUrl(config('app.url')); */
    }
}