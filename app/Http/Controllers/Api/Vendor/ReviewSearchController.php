<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReviewResource;
use Illuminate\Support\Facades\Schema;

class ReviewSearchController extends Controller
{
    public function search(Request $request)
    {
        $vendor = Auth::user()->vendor;
        
        if (!$vendor) {
            return response()->json(['message' => 'No associated vendor found for the authenticated user'], 404);
        }
    
        $searchGlobal = $request->input('search_global');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
    
        $reviewsQuery = Review::where('vendor_id', $vendor->id)
            ->with(['product.category', 'user', 'vendor'])
            ->orderBy('created_at', 'desc');
    
        // Add global search functionality
        if ($searchGlobal) {
            $searchTerm = '%' . $searchGlobal . '%';
            $reviewsQuery->where(function ($query) use ($searchTerm) {
                $query->where('rating', 'like', $searchTerm)
                    ->orWhere('review_status', 'like', $searchTerm)
                    ->orWhere('review_comment', 'like', $searchTerm)
                    ->orWhere('review_reply', 'like', $searchTerm)
                    ->orWhereHas('product', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', $searchTerm)
                            ->orWhereHas('category', function ($query) use ($searchTerm) {
                                $query->where('name', 'like', $searchTerm);
                            });
                    })
                    ->orWhereHas('user', function ($query) use ($searchTerm) {
                        $query->where('first_name', 'like', $searchTerm)
                              ->orWhere('last_name', 'like', $searchTerm);
                    });
            });
        }
    
        // Add date filtering functionality
        if ($startDate && $endDate) {
            $reviewsQuery->whereBetween('created_at', [$startDate, $endDate]);
        } elseif ($startDate) {
            $reviewsQuery->where('created_at', '>=', $startDate);
        } elseif ($endDate) {
            $reviewsQuery->where('created_at', '<=', $endDate);
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


    public function filter(Request $request)
    {
        $vendor = Auth::user()->vendor;
        
        if (!$vendor) {
            return response()->json(['message' => 'No associated vendor found for the authenticated user'], 404);
        }

        $reviewsQuery = Review::where('vendor_id', $vendor->id)
            ->with(['product.category', 'user', 'vendor'])
            ->orderBy('created_at', 'desc');

        // Handle dynamic filtering for review columns
        foreach ($request->all() as $key => $value) {
            if (in_array($key, Schema::getColumnListing('reviews')) && !is_null($value)) {
                $reviewsQuery->where($key, 'LIKE', "%{$value}%");
            }
        }

        // Handle filtering by product category
        if ($request->has('category')) {
            $category = $request->input('category');
            $reviewsQuery->whereHas('product.category', function ($query) use ($category) {
                $query->where('name', 'LIKE', "%{$category}%");
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

    

    // public function search(Request $request)
    // {
    //     $vendor = Auth::user()->vendor;
        
    //     if (!$vendor) {
    //         return response()->json(['message' => 'No associated vendor found for the authenticated user'], 404);
    //     }

    //     $searchTerm = $request->input('search');

    //     $reviewsQuery = Review::where('vendor_id', $vendor->id)
    //         ->with(['product.category', 'user', 'vendor'])
    //         ->orderBy('created_at', 'desc');

    //     // Add search functionality
    //     if ($searchTerm) {
    //         $reviewsQuery->whereHas('product', function ($query) use ($searchTerm) {
    //             $query->where('name', 'LIKE', "%{$searchTerm}%")
    //                 ->orWhereHas('category', function ($query) use ($searchTerm) {
    //                     $query->where('name', 'LIKE', "%{$searchTerm}%");
    //                 });
    //         });
    //     }

    //     $reviews = $reviewsQuery->paginate(10);

    //     if ($reviews->isEmpty()) {
    //         return response()->json(['message' => 'No reviews found for this vendor'], 404);
    //     }

    //     // Calculate the average rating and total reviews without pagination
    //     $averageRating = $reviewsQuery->avg('rating');
    //     $totalReviews = $reviewsQuery->count();

    //     // Count the number of each rating without pagination
    //     $ratingsCount = Review::where('vendor_id', $vendor->id)
    //         ->selectRaw('rating, COUNT(*) as count')
    //         ->groupBy('rating')
    //         ->pluck('count', 'rating');

    //     // Initialize all possible ratings with 0 counts
    //     $allRatingsCount = [
    //         1 => 0,
    //         2 => 0,
    //         3 => 0,
    //         4 => 0,
    //         5 => 0,
    //     ];

    //     // Merge the counts from the query into the initialized array, maintaining keys
    //     foreach ($ratingsCount as $rating => $count) {
    //         $allRatingsCount[$rating] = $count;
    //     }

    //     return response()->json([
    //         'data' => ReviewResource::collection($reviews),
    //         'average_rating' => $averageRating,
    //         'total_reviews' => $totalReviews,
    //         'ratings_count' => $allRatingsCount,
    //         'pagination' => [
    //             'current_page' => $reviews->currentPage(),
    //             'last_page' => $reviews->lastPage(),
    //             'per_page' => $reviews->perPage(),
    //             'total' => $reviews->total(),
    //         ],
    //     ]);
    // }

}
