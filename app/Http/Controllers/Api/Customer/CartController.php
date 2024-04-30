<?php

namespace App\Http\Controllers\Api\Customer;

use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AddCartRequest;
use App\Http\Resources\CartItemResource;

class CartController extends Controller
{
    public function getCartItems(Request $request)
    {
        $user = $request->user();
        $cartItems = $user->cartItems()->with('product')->get(); 
        return CartItemResource::collection($cartItems);
    }


    public function addcart(AddCartRequest $request, Product $product)
    {
        $user = Auth::user();

        // Retrieve the existing cart item, if any, for the given product
        $existingCartItem = $user->cartItems()->where('product_id', $product->id)->first();
    
        if ($existingCartItem) {
            // If the product is already in the cart, update the quantity
            $existingCartItem->update([
                'quantity' => $existingCartItem->quantity + $request->quantity
            ]);
        } else {
            // If the product is not in the cart, create a new cart item
            $user->cartItems()->create([
                'product_id' => $product->id,
                'quantity' => $request->quantity
            ]);
        }
    
        // Return a success response
        return response()->json(['message' => 'Product added to cart successfully'], 200);
    }



    //  public function removecart( CartItem $cartItem, Product $product)
    // {

        
    //     if (Auth::id() !== $cartItem->user_id) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     $cartItem->delete();

    //     // Return a success response
    //     return response()->json(['message' => 'Cart item removed successfully'], 200);
    // }

    public function removecart(Request $request, $productId, $cartItemId)
{
    // Fetch the cart item
    $cartItem = CartItem::find($cartItemId);

    // Check if cart item exists
    if (!$cartItem) {
        return response()->json(['error' => 'Cart item not found'], 404);
    }

    // Check if the authenticated user is authorized to delete this cart item
    if (Auth::id() !== $cartItem->user_id) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    // Check if the cart item belongs to the specified product
    if ($cartItem->product_id != $productId) {
        return response()->json(['error' => 'Cart item does not belong to the specified product'], 400);
    }

    // Delete the cart item
    $cartItem->delete();

    // Return a success response
    return response()->json(['message' => 'Cart item removed successfully'], 200);
}


    public function updateCartItemQuantity(Request $request, CartItem $cartItem )
    {
        $request->validate([
            'quantity' => 'required|integer|min:1', // Assuming quantity should be at least 1
        ]);
    
        // Ensure the authenticated user owns the cart item
        if (Auth::id() !== $cartItem->user_id) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        // Update the cart item's quantity
        $cartItem->update([
            'quantity' => $request->quantity
        ]);
    
        // Return a success response
        return response()->json(['message' => 'Cart item quantity updated successfully'], 200);
    }


    public function clearCart()
    {
        $user = Auth::user();
    
        $user->cartItems()->delete();
    
        return response()->json(['message' => 'Cart cleared successfully'], 200);
    }
}
