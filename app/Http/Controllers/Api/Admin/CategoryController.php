<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\SubcategoryResource;
use App\Http\Requests\UpdateCategoryRequest;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


/**
 * * @group Admin
 * @subgroup Category
 * 
 *
 */
class CategoryController extends Controller
{
    /**
     * GET Categories
     *
     * [Returns List of all Categories]
     *
     * 
     *
     * 
     */

    // public function index(Request $request)
    // {
    //     $perPage = $request->get('per_page', 20);
    //     $cacheKey = 'categories_' . $perPage . '_' . $request->page;

    //     $categories = Cache::remember($cacheKey, Carbon::now()->addDay(), function () use ($perPage) {
    //         return Category::paginate($perPage);
    //     });

    //     return CategoryResource::collection($categories);
    // }

    public function index(Request $request)
    {
        $cacheKey = 'categories_';

        $categories = Cache::remember($cacheKey, Carbon::now()->addDay(), function () {
            return Category::orderBy('id', 'desc')->get();
        });

        return CategoryResource::collection($categories);
    }


    // public function index(Request $request)
    // {
    //     $categories = Category::orderBy('created_at', 'desc')->take(4)->get();
    //     // $categories = Category::orderBy('created_at', 'desc')->take(4)->get();

      
    //     return CategoryResource::collection($categories);
    // }
   /**
     * POST Categories
     *
     * Create new Category
     *
     * @bodyParam name string required Name of Category. Example: "Clothing"
     *
     * 
     */
    public function store(StoreCategoryRequest $request)
    {
        
        $category = auth()->user()->categories()->create($request->validated());
         
        $uploadedFiles = $this->upload($request);

        return new CategoryResource($category);
    }

    
   

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show(Category $category)
    // {
    //     $this->authorize('all-access');

    //     if ($category->user_id != auth()->id()) {
    //         abort(403, 'Unauthorized');
    //     }
    //     // it gets the subcategories that is related to the passed category
    //     $subcategories = $category->subcategories;
    //     return SubcategoryResource::collection($subcategories);
 
    //     // return new CategoryResource($category);
    // }

    public function show(Category $category)
    {
        $this->authorize('all-access');

        if ($category->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }
        $cacheKey = 'subcategories_' . $category->id;

        $subcategories = Cache::remember($cacheKey, Carbon::now()->addDay(), function () use ($category) {
            return $category->subcategories;
        });
        return SubcategoryResource::collection($subcategories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Category $category, UpdateCategoryRequest $request)
    {
        if ($category->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }
    
        $category->update($request->validated());
    
        return new CategoryResource($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $this->authorize('all-access');

        if ($category->user_id != auth()->id()) {
            abort(403, 'Unauthorized');
        }

        if ($category->photo && !empty($category->photo)) {
            $publicId = Cloudinary::getPublicIdFromUrl($category->photo);
            if ($publicId) {
                Cloudinary::destroy($publicId);
            }
        }
 
        $category->delete();
 
        return response()->noContent();
    }

    
    public function upload(Request $request)
    {
        // Specify the folder name where you want to upload the file
        $folder = 'category_photo';
    
        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            request()->validate([
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);
    
            // Upload the file to Cloudinary with folder specified
            $cloudinaryResponse = Cloudinary::upload($file->getRealPath(), [
                'folder' => $folder,
                'transformation' => [
                    ['width' => 400, 'height' => 400, 'crop' => 'fit'],
                    ['quality' => 'auto', 'fetch_format' => 'auto']
                ]
            ]);
    
            // Get the secure URL of the uploaded file
            $secureUrl = $cloudinaryResponse->getSecurePath();
            // Return the secure URL of the uploaded file
            return response()->json(['secure_url' => $secureUrl], 200);
        } else {
            // Handle case where no file is uploaded
            return response()->json(['error' => 'No file uploaded'], 400);
        }
    }
    

 
    
}
