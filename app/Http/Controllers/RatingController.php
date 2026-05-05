<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    // ⭐ STORE REVIEW
    public function store(Request $request, $id)
    {
        if (!session('customer_id')) {
            return redirect()->route('customer.login')
                ->with('error', 'Please login to submit a review.');
        }

        $customerId = session('customer_id');

        // ✅ CHECK VERIFIED PURCHASE
        $isPurchased = OrderItem::where('product_id', $id)
            ->whereHas('order', function ($q) use ($customerId) {
                $q->where('customer_id', $customerId)
                  ->where('status', 'delivered');
            })
            ->exists();

        if (!$isPurchased) {
            return back()->with('error', 'Only verified buyers can review this product.');
        }

        $product = Product::findOrFail($id);

        // ✅ DUPLICATE CHECK
        $existing = Rating::where('product_id', $id)
            ->where('customer_id', $customerId)
            ->first();

        if ($existing) {
            return back()->with('error', 'You already reviewed this product.');
        }

        // ✅ VALIDATION
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title'  => ['nullable', 'string', 'max:100'],
            'review' => ['nullable', 'string', 'min:10', 'max:2000'],
            'images.*' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        // ✅ IMAGE UPLOAD
        $images = [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $images[] = $file->store('reviews', 'public');
            }
        }

        Rating::create([
            'customer_id' => $customerId,
            'product_id'  => $id,
            'rating'      => $request->rating,
            'title'       => $request->title,
            'review'      => $request->review,
            'images'      => count($images) ? $images : null,
            'is_verified_purchase' => 1,
        ]);

        return back()->with('success', 'Review submitted! Waiting for approval.');
    }

    // 👍 HELPFUL / NOT HELPFUL
    public function helpful(Request $request, Rating $rating)
    {
        $request->validate([
            'vote' => ['required', 'in:yes,no']
        ]);

        if ($request->vote === 'yes') {
            $rating->increment('helpful_yes');
        } else {
            $rating->increment('helpful_no');
        }

        return back();
    }

    // ❌ DELETE REVIEW
    public function destroy(Rating $rating)
    {
        if (session('customer_id') != $rating->customer_id) {
            abort(403);
        }

        // delete images also
        if ($rating->images) {
            foreach (json_decode($rating->images, true) as $img) {
                \Storage::disk('public')->delete($img);
            }
        }

        $rating->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
}