<?php

namespace App\Http\Controllers\Api\Vendor;

use Log;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendor = Auth::user()->vendor;
        $posts = Post::where('vendor_id', $vendor->id)->with('products')->get();
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
 
    public function store(StorePostRequest $request)
    {
        $vendor = Auth::user()->vendor;

        $validatedData = $request->validated();
        $validatedData['vendor_id'] = $vendor->id;

        if (empty($validatedData['scheduled_at']) && (!isset($validatedData['is_draft']) || !$validatedData['is_draft'])) {
            $validatedData['published_at'] = now();
        }


        $post = Post::create($validatedData);

        if ($request->has('product_ids')) {
            $productIds = $request->input('product_ids');

            $products = Product::whereIn('id', $productIds)->get();
            foreach ($products as $product) {
                if ($product->vendor_id !== $vendor->id) {
                    return response()->json(['message' => 'Unauthorized'], 403);
                }
            }

            $post->products()->attach($productIds);
        }

        $uploadedFiles = $this->upload($request);

        return response()->json([
            'message' => 'Post created successfully',
            'post' => new PostResource($post->load('products'))
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $this->authorize('product-manage');
        $vendor = Auth::user()->vendor;
        if ($vendor->id !== $post->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->load('products');
        return new PostResource($post);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $vendor = Auth::user()->vendor;
        if ($vendor->id !== $post->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validated();
        // $validatedData['published_at'] = now();

        if ($request->has('product_ids')) {
            $productIds = $request->input('product_ids');

            foreach ($productIds as $productId) {
                $product = Product::findOrFail($productId);
                if ($product->vendor_id !== $vendor->id) {
                    return response()->json(['message' => 'Unauthorized'], 403);
                }
            }

            $post->products()->sync($productIds);
        }

        if (empty($validatedData['scheduled_at']) && !$validatedData['is_draft']) {
            $validatedData['published_at'] = now();
        }
    
        $post->update($validatedData);

        return response()->json(['message' => 'Product updated successfully', 'post' => new PostResource($post->load('products'))], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $vendor = Auth::user()->vendor;
        if ($vendor->id !== $post->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 204);
    }

    public function draft(StorePostRequest $request)
    {
        $vendor = Auth::user()->vendor;
        $validatedData = $request->validated();
        $validatedData['vendor_id'] = $vendor->id;
        $validatedData['is_draft'] = true; 
        $validatedData['published_at'] = null; 

        $post = Post::create($validatedData);

        if ($request->has('product_ids')) {
            $productIds = $request->input('product_ids');
            $products = Product::whereIn('id', $productIds)->get();
            foreach ($products as $product) {
                if ($product->vendor_id !== $vendor->id) {
                    return response()->json(['message' => 'Unauthorized'], 403);
                }
            }
            $post->products()->attach($productIds);
        }

        $uploadedFiles = $this->upload($request);

        return response()->json([
            'message' => 'Post saved as draft successfully',
            'post' => new PostResource($post->load('products'))
        ], 201);
    }

    public function schedule(StorePostRequest $request)
    {
        $vendor = Auth::user()->vendor;
        $validatedData = $request->validated();
        $validatedData['vendor_id'] = $vendor->id;
        $validatedData['is_draft'] = false; 

        
        if (empty($validatedData['scheduled_at'])) {
            return response()->json(['message' => 'The scheduled_at field is required for scheduling.'], 422);
        }

        $validatedData['published_at'] = null; 

        $post = Post::create($validatedData);

        if ($request->has('product_ids')) {
            $productIds = $request->input('product_ids');
            $products = Product::whereIn('id', $productIds)->get();
            foreach ($products as $product) {
                if ($product->vendor_id !== $vendor->id) {
                    return response()->json(['message' => 'Unauthorized'], 403);
                }
            }
            $post->products()->attach($productIds);
        }

        $uploadedFiles = $this->upload($request);

        return response()->json([
            'message' => 'Post scheduled successfully',
            'post' => new PostResource($post->load('products'))
        ], 201);
    }

    public function publish(Request $request, Post $post)
    {
        $vendor = Auth::user()->vendor;

        if ($vendor->id !== $post->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->is_draft = false;

    
        if (!$post->scheduled_at) {
            $post->published_at = Carbon::now();
        }

        $post->save();

        return response()->json([
            'message' => 'Post published successfully',
            'post' => new PostResource($post->load('products'))
        ], 200);
    }

    public function view(Request $request, Post $post)
    {
        $ipAddress = $request->ip();
        $cacheKey = 'view_post_' . $post->id . '_ip_' . $ipAddress;
        $cacheExpiration = now()->addDay(); 

    
        if (Cache::has($cacheKey)) {
            return response()->json([
                'message' => 'You have already viewed this post today'
            ], 403);
        }

        $post->increment('views');

        Cache::put($cacheKey, true, $cacheExpiration);

        return response()->json([
            'message' => 'Post view count incremented successfully',
            'post' => new PostResource($post)
        ], 200);
    }

    public function like(Request $request, Post $post)
    {
        $ipAddress = $request->ip();
        $cacheKey = 'like_post_' . $post->id . '_ip_' . $ipAddress;
        $cacheExpiration = now()->addDay(); 
    
        if (Cache::has($cacheKey)) {
            return response()->json([
                'message' => 'You have already liked this post today'
            ], 403);
        }
        $post->increment('likes');
    
        Cache::put($cacheKey, true, $cacheExpiration);
    
        return response()->json([
            'message' => 'Post like count incremented successfully',
            'post' => new PostResource($post)
        ], 200);
    }

   



    public function upload(Request $request)
    {
        $folder = 'featured_img';
    
        if ($request->hasFile('featured_img')) {
            $file = $request->file('featured_img');
            request()->validate([
                'featured_img. *' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:6048',
               
            ]);
    
            $cloudinaryResponse = Cloudinary::upload($file->getRealPath(), [
                'folder' => $folder,
                'transformation' => [
                    ['width' => 400, 'height' => 400, 'crop' => 'fit'],
                    ['quality' => 'auto', 'fetch_format' => 'auto']
                ]
            ]);
    
            $secureUrl = $cloudinaryResponse->getSecurePath();
            return response()->json(['secure_url' => $secureUrl], 200);
        } else {
            return response()->json(['error' => 'No file uploaded'], 400);
        }
    }
}
