<?php

namespace App\Http\Controllers;
use App\Models\Notification;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    //
    public function index() {
        $notifications_main = Notification::where('customer_id', session('customer_id'))->latest()->get();
        return view('frontend.notifications', compact('notifications_main'));
    }

    public function destroy($id) {
        Notification::where('id', $id)
                    ->where('customer_id', session('customer_id'))
                    ->delete();
        return back()->with('success', 'Notification removed');
    }

    public function clearAll() {
        Notification::where('customer_id', session('customer_id'))->delete();
        return back()->with('success', 'All notifications cleared');
    }

    public function markAllRead(){
        Notification::where('customer_id', session('customer_id'))
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return back();
    }
}
