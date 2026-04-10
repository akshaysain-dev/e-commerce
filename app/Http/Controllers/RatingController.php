<?php

namespace App\Http\Controllers;
use App\Models\Rating;
use App\Models\Product;

use Illuminate\Http\Request;

class RatingController extends Controller
{
    //
    public function store(Request $request, $id)
    {
        if (!session('customer_id')) {
            return redirect()->route('customer.login')
                            ->with('error', 'Please login to submit a review.');
        }

        $product = Product::findOrFail($id);

        $existing = $product->ratings()
                            ->where('customer_id', session('customer_id'))
                            ->first();

        if ($existing) {
            return back()->with('error', 'Aapne already is product ko review kiya hua hai.');
        }

        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'title'  => ['nullable', 'string', 'max:100'],
            'review' => ['nullable', 'string', 'min:10', 'max:2000'],
        ]);

        Rating::create([
            'customer_id' => session('customer_id'),
            'product_id'  => $id,
            'rating'      => $request->rating,
            'title'       => $request->title,
            'review'      => $request->review,
        ]);

        return redirect()->back()->with('success', 'Review submit ho gaya, shukriya!');
    }

    public function helpful(Request $request, Rating $rating)
    {
        $request->validate(['vote' => ['required', 'in:yes,no']]);

        if ($request->vote === 'yes') {
            $rating->increment('helpful_yes');
        } else {
            $rating->increment('helpful_no');
        }

        return back();
    }

    public function destroy($id, Rating $rating)
    {
        if (session('customer_id') != $rating->customer_id) {
            abort(403);
        }

        $rating->delete();

        return redirect()->back()->with('success', 'Review delete ho gaya.');
    }
}
