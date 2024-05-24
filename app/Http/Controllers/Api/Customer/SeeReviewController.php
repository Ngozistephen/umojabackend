<?php

namespace App\Http\Controllers\Api\Customer;

use App\Models\Review;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;

class SeeReviewController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
          // Get the product_id from the request
          $productId = $request->query('product_id');
    
          // Fetch reviews with the specified product_id, review_status 'approved', and non-null published_at
          $reviews = Review::with(['product', 'user', 'vendor'])
              ->where('product_id', $productId)
              ->where('review_status', 'approved')
              ->whereNotNull('published_at')
              ->paginate();
  
              if ($reviews->isEmpty()) {
                  return response()->json(['message' => 'No reviews found for the specified criteria'], 404);
              }
      
          return ReviewResource::collection($reviews);
    }
}
