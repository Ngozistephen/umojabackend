<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Post;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
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
        $vendor = Auth::user();
        $posts = Post::where('vendor_id', $vendor->id)->with('products')->get();
        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        $vendor = Auth::user();

        $validatedData = $request->validated();
        $validatedData['vendor_id'] = $vendor->id;

        $post = Post::create($validatedData);

        if ($request->has('product_ids')) {
            $productIds = $request->input('product_ids');

            foreach ($productIds as $productId) {
                $product = Product::findOrFail($productId);
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
        $vendor = Auth::user();
        if ($vendor->id !== $post->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new PostResource($post->load('products'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $vendor = Auth::user();
        if ($vendor->id !== $post->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validatedData = $request->validated();

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

        $post->update($validatedData);

        return response()->json(['message' => 'Product updated successfully', 'post' => new PostResource($post->load('products'))], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $vendor = Auth::user();
        if ($vendor->id !== $post->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully'], 204);
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
