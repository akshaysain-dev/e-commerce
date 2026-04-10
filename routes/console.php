<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Notification;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $customer = Customer::first(); 

    if ($customer) {
        Log::info("Notification created at " . now() . " for Customer ID: " . $customer->id);

        Notification::create([
            'customer_id' => $customer->id,
            'title'       => 'This is added cron notification.',
            'message'     => 'New product Added in Cart for processed in checkout.',
        ]);
    } else {
        Log::warning("No customer found to send notification.");
    }
})->everyMinute();

Schedule::command('queue:work --stop-when-empty --tries=3')
    ->everyMinute()
    ->withoutOverlapping();
