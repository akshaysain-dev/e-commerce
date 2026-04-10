<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use App\Models\Notification;

class WishlistController extends Controller
{
    // Wishlist page
    public function index()
    {
        $wishlists = Wishlist::with('product')
                             ->where('customer_id', session('customer_id'))
                             ->latest()
                             ->get();

        return view('frontend.wishlist', compact('wishlists'));
    }

    // Add or Remove toggle
    public function toggle(Request $request)
    {
        if (!session()->has('customer_id')) {
            return redirect()->route('customer_login')->with('error', 'Please login first.');
        }

        $request->validate(['product_id' => 'required|exists:products,id']);

        $existing = Wishlist::where('customer_id', session('customer_id'))
                            ->where('product_id', $request->product_id)
                            ->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Removed from wishlist.');
        } else {
            Wishlist::create([
                'customer_id' => session('customer_id'),
                'product_id'  => $request->product_id,
            ]);
            Notification::create([
                'customer_id' => session('customer_id'),
                'title' => 'New Product Added in Wishlist',
                'message' => 'Your new product Added in wishlist please check your wishlist and place order.',
            ]);
            return back()->with('success', 'Added to wishlist!');
        }
    }

    // Remove single item
    public function remove($id)
    {
        Wishlist::where('id', $id)
                ->where('customer_id', session('customer_id'))
                ->delete();

        return back()->with('success', 'Removed from wishlist.');
    }
}