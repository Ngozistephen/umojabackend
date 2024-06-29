<?php

namespace App\Http\Controllers\Api\Customer;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ReviewResource;
use App\Http\Requests\StoreReviewRequest;
use App\Notifications\ReviewNotification;
use App\Http\Requests\UpdateReviewRequest;
use App\Http\Requests\UpdateReviewReplyRequest;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendor = Auth::user()->vendor;
        
        if (!$vendor) {
            return response()->json(['message' => 'No associated vendor found for the authenticated user'], 404);
        }
    
        $reviewsQuery = Review::where('vendor_id', $vendor->id)
            ->with(['product', 'user', 'vendor'])
            ->orderBy('created_at', 'desc');
    
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
    
    




    



    


    /**
     * Store a newly created resource in storage.
     */
   
     public function store(StoreReviewRequest $request)
     {
         // Get validated data from the request
         $data = $request->validated();
     
         if (!isset($data['review_status'])) {
             $data['review_status'] = 'pending';
         }
     
         $review = auth()->user()->reviews()->create($data);
     
       
        $review->load(['product.vendor', 'user']);

    
        if ($review->product->vendor) {
            $review->product->vendor->notify(new ReviewNotification($review->user, $review, $review->rating, $review->product->name, $review->review_comment));
        }
        return new ReviewResource($review);

     }
 

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
       

        return new ReviewResource($review->load(['product', 'user', 'vendor']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        if ($review->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }
        $review->update($request->validated());
        return new ReviewResource($review);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        $this->authorize('product_manage');

        if ($review->vendor_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }
 
  
        $review->delete();
        return response()->noContent();
    }

    public function reply(UpdateReviewReplyRequest $request, $id)
    {
        $review = Review::findOrFail($id);
        $vendor = $request->user()->vendor;
    
        // Check if the vendor associated with the review matches the authenticated vendor
        if ($review->vendor_id !== $vendor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
    
        // Update review reply and optionally review status
        $data = [
            'review_reply' => $request->input('review_reply'),
        ];

        if ($request->has('review_status') && $request->input('review_status') === 'approved') {
            $data['review_status'] = 'approved';
            $data['published_at'] = Carbon::now();
        }
        $review->update($data);

        return new ReviewResource($review->load(['product', 'user', 'vendor']));
    }


    public function like(Request $request, Review $review)
    {
        $user = $request->user();
    
        // Check if the user already liked the review
        if ($review->likes()->where('user_id', $user->id)->exists()) {
            return response()->json(['message' => 'You already liked this review'], 400);
        }
    
        $review->likes()->create(['user_id' => $user->id]);
    
        return response()->json(['message' => 'Review liked successfully']);
    }
    
    public function unlike(Request $request, Review $review)
    {
        $user = $request->user();
    
        $like = $review->likes()->where('user_id', $user->id)->first();
    
        if (!$like) {
            return response()->json(['message' => 'You have not liked this review'], 400);
        }
    
        $like->delete();
    
        return response()->json(['message' => 'Review unliked successfully']);
    }
    

    public function editReply(UpdateReviewReplyRequest $request, Review $review)
    {
        
        $vendor = $request->user()->vendor;

        // Check if the vendor associated with the review matches the authenticated vendor
        if ($review->vendor_id !== $vendor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Update the review reply
        $review->update(['review_reply' => $request->input('review_reply')]);

        // Optionally, you can return the updated review with its relations
        return response()->json([
            'message' => 'Review reply updated successfully',
            'review' => new ReviewResource($review->load(['product', 'user', 'vendor'])),
        ]);
    }

    public function deleteReply(Request $request, $id)
    {
        $review = Review::findOrFail($id);
        $vendor = $request->user()->vendor;

        // Check if the vendor associated with the review matches the authenticated vendor
        if ($review->vendor_id !== $vendor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete the review reply
        $review->update(['review_reply' => null]);

        // Optionally, you can return a success message
        return response()->json(['message' => 'Review reply deleted successfully']);
    }


    public function markAsPending(UpdateReviewReplyRequest $request, $id)
    {
        $review = Review::findOrFail($id);
        $vendor = $request->user()->vendor;

        // Check if the vendor associated with the review matches the authenticated vendor
        if ($review->vendor_id !== $vendor->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

      
        $review->update(['review_status' => 'pending']);

        return new ReviewResource($review->load(['product', 'user', 'vendor']));
    }

}
