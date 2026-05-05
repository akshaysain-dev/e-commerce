<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReviewReply;
use App\Models\Rating;

class RatingReplyController extends Controller
{
    public function store(Request $request)
    {
        try {

            // ✅ Validation
            $request->validate([
                'rating_id' => 'required|exists:ratings,id',
                'body'      => 'required|string|max:500',
            ]);

            // ✅ Auth check (customer session)
            if (!session('customer_id')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Login required'
                ], 401);
            }

            $customerId   = session('customer_id');
            $customerName = session('customer_name', 'User');

            // ✅ Check rating exists
            $rating = Rating::find($request->rating_id);
            if (!$rating) {
                return response()->json([
                    'success' => false,
                    'message' => 'Review not found'
                ]);
            }

            // ✅ SAVE REPLY
            $reply = ReviewReply::create([
                'rating_id'   => $request->rating_id,
                'customer_id' => $customerId,
                'body'        => $request->body,
                'author_name' => $customerName,
                'is_seller'   => false, // customer reply
            ]);

            // ✅ RESPONSE (JS expects this format)
            return response()->json([
                'success' => true,
                'reply' => [
                    'id'          => $reply->id,
                    'author_name' => $reply->author_name,
                    'body'        => $reply->body,
                    'is_seller'   => $reply->is_seller,
                ]
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }
}