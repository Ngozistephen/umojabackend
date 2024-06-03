<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReviewResource;

class ReviewSearchController extends Controller
{
    public function search(Request $request)
    {
        $vendor = Auth::user()->vendor;
        
        if (!$vendor) {
            return response()->json(['message' => 'No associated vendor found for the authenticated user'], 404);
        }

        $searchTerm = $request->input('search');

        $reviewsQuery = Review::where('vendor_id', $vendor->id)
            ->with(['product.category', 'user', 'vendor'])
            ->orderBy('created_at', 'desc');

        // Add search functionality
        if ($searchTerm) {
            $reviewsQuery->whereHas('product', function ($query) use ($searchTerm) {
                $query->where('name', 'LIKE', "%{$searchTerm}%")
                    ->orWhereHas('category', function ($query) use ($searchTerm) {
                        $query->where('name', 'LIKE', "%{$searchTerm}%");
                    });
            });
        }

        $reviews = $reviewsQuery->paginate(10);

        if ($reviews->isEmpty()) {
            return response()->json(['message' => 'No reviews found for this vendor'], 404);
        }

        // Calculate the average rating and total reviews without pagination
        $averageRating = $reviewsQuery->avg('rating');
        $totalReviews = $reviewsQuery->count();

        // Count the number of each rating without pagination
        $ratingsCount = Review::where('vendor_id', $vendor->id)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->pluck('count', 'rating');

        // Initialize all possible ratings with 0 counts
        $allRatingsCount = [
            1 => 0,
            2 => 0,
            3 => 0,
            4 => 0,
            5 => 0,
        ];

        // Merge the counts from the query into the initialized array, maintaining keys
        foreach ($ratingsCount as $rating => $count) {
            $allRatingsCount[$rating] = $count;
        }

        return response()->json([
            'data' => ReviewResource::collection($reviews),
            'average_rating' => $averageRating,
            'total_reviews' => $totalReviews,
            'ratings_count' => $allRatingsCount,
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total(),
            ],
        ]);
    }

}
