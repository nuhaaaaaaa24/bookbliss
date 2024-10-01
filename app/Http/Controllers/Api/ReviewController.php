<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Review;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string',
            'review_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('review_image')) {
            $imagePath = $request->file('review_image')->store('reviews', 'public');
            $validated['review_image'] = $imagePath;
        }

        // Create the review
        $review = Review::create($validated);

        return response()->json([
            'message' => 'Review submitted successfully',
            'review' => $review
        ], 201);
    }

    // Fetch all reviews
    public function index()
    {
        $reviews = Review::with('user')->get();
        return response()->json($reviews);
    }
}
