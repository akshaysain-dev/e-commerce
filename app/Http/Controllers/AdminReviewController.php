<?php

namespace App\Http\Controllers;

use App\Models\Rating;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;

        $query = Rating::with(['customer','product'])->latest();

        if ($status) {
            $query->where('status', $status);
        }

        $reviews = $query->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    // ✅ APPROVE
    public function approve($id)
    {
        Rating::where('id', $id)->update(['status' => 'approved']);

        return back()->with('success', 'Review approved');
    }

    // ❌ REJECT
    public function reject($id)
    {
        Rating::where('id', $id)->update(['status' => 'rejected']);

        return back()->with('success', 'Review rejected');
    }

    // 🗑 DELETE
    public function delete($id)
    {
        $review = Rating::findOrFail($id);

        // delete images
        if ($review->images) {
            foreach ($review->images as $img) {
                \Storage::disk('public')->delete($img);
            }
        }

        $review->delete();

        return back()->with('success', 'Review deleted');
    }
}