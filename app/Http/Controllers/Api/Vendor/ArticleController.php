<?php

namespace App\Http\Controllers\Api\Vendor;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ArticleResource;
use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index()
     {
         $vendor = Auth::user()->vendor;
         $articles = Article::where('vendor_id', $vendor->id)->orderBy('created_at', 'desc')->paginate(10);
         return ArticleResource::collection($articles);
     }

    public function allarticles()
    {
       
       
    
        $articles = Article::with(['vendor', 'category'])->orderBy('created_at', 'desc')->paginate(5);
        return ArticleResource::collection($articles);

       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        $vendor = Auth::user()->vendor;
        if (!$vendor) {
            return response()->json(['error' => 'Vendor not found for the authenticated user'], 404);
        }
        $validatedData = $request->validated();
        $validatedData['vendor_id'] = $vendor->id;

        if (!isset($validatedData['published_at'])) {
            $validatedData['published_at'] = now();
        }
        $article = Article::create($validatedData);

      

        $uploadedFiles = $this->upload($request);

        return response()->json([
            'message' => 'Article created successfully',
            'article' => new ArticleResource($article)
        ], 201);



    }

   

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        $this->authorize('product-manage');
        $vendor = Auth::user()->vendor;
        if ($vendor->id !== $article->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return new ArticleResource($article);
    }

    
    
    public function showArticle(Article $article)
    {
      

        return new ArticleResource($article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $vendor = Auth::user()->vendor;
        if ($vendor->id !== $article->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $article->update($request->validated());

        return response()->json(['message' => 'Article updated successfully', 'article' => new ArticleResource($article)], 200);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $vendor = Auth::user()->vendor;
        if ($vendor->id !== $article->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $article->delete();

        return response()->json(['message' => 'Article deleted successfully'], 204);
    }

    public function upload(Request $request)
    {
        $folder = 'article_cover_image';
    
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            request()->validate([
                'cover_image. *' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
               
            ]);
    
            $cloudinaryResponse = Cloudinary::upload($file->getRealPath(), [
                'folder' => $folder,
                
            ]);
    
            $secureUrl = $cloudinaryResponse->getSecurePath();
            return response()->json(['secure_url' => $secureUrl], 200);
        } else {
            return response()->json(['error' => 'No file uploaded'], 400);
        }
    }

    
}
