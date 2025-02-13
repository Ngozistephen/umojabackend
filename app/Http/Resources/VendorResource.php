<?php

namespace App\Http\Resources;

use App\Models\Post;
use App\Models\Order;
use App\Models\Review;
use App\Models\Article;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\PolicyResource;
use App\Http\Resources\ShippingZoneResource;
use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $postCount = Post::where('vendor_id', $this->id)->count();
        $articleCount = Article::where('vendor_id', $this->id)->count();
        $orderCount = Order::whereHas('products', function ($query) {
            $query->where('order_product.vendor_id', $this->id);
        })->count();
        $productCount = Product::where('vendor_id', $this->id)->count();
        $reviewCount = Review::where('vendor_id', $this->id)->count();

        $promoCount = Product::where('vendor_id', $this->id)
                    ->whereNotNull('compare_at_price')
                    ->where('compare_at_price', '>', 0)
                    ->count();

        $unreadNotificationCount = $this->unreadNotifications()->count();   
        $shippingMethod = $this->shippingMethods->first();
        $shippingMethodDetails = $shippingMethod ? [
            'id' => $shippingMethod->id,
            'name' => $shippingMethod->name,
        ] : null;
        return [
            'id' => $this->id,
            // 'language' => $this->language,
            // 'gender' => $this->gender,   
            'country_name' => $this->country_name,
            'busniess_phone_number' => $this->busniess_phone_number,
            'company' => $this->company,
            'address' => $this->address,
            'complex_building_address' => $this->complex_building_address,
            'rep_country' => $this->rep_country,
            'profile_photo' => $this->profile_photo,
            'cover_image' => $this->cover_image,
            // 'vendor_id_number' => $this->vendor_id_number,
            'state' => $this->state,
            'city' => $this->city,
            'postal_code' => $this->postal_code,
            'completed_stripe_onboarding' => $this->completed_stripe_onboarding,
            // 'zipcode' => $this->zipcode,
            'business_bio' => $this->business_bio,
            'twitter_handle' => $this->twitter_handle,
            'facebook_handle' => $this->facebook_handle,
            'instagram_handle' => $this->instagram_handle,
            'youtube_handle' => $this->youtube_handle,
            'building_name' => $this->building_name,
            'business_type_id' => $this->business_type_id,
            'business_type' => $this->business_type?->name,
            'business_name' => $this->business_name,
            'business_website' => $this->business_website,
            'office_country' => $this->office_country,
            'office_state' => $this->office_state,
            'office_city' => $this->office_city,
            'office_address' => $this->office_address,
            'busniess_email' => $this->busniess_email,
            'business_image' => $this->business_image,
            // 'business_number' => $this->business_number,
            // 'tax_id_number' => $this->tax_id_number,
            // 'utility_photo' => $this->utility_photo,
            // 'business_number_photo' => $this->business_number_photo,
            'bank_name' => $this->bank_name,
            'bank_account_number' => $this->bank_account_number,
            'name_on_account' => $this->name_on_account,
            'created_at' => $this->created_at,
            'sort_code' => $this->sort_code,
            'swift_code' => $this->swift_code,
            'iban' => $this->iban,
            'post_count' => $postCount,
            'article_count' => $articleCount,
            'order_count' => $orderCount,
            'product_count' => $productCount,
            'review_count' => $reviewCount,
            'promo_count' =>  $promoCount,
            'followers_count' => $this->followersCount(),
            'unread_notification_count' => $unreadNotificationCount,
            'shipping_method' => $shippingMethodDetails,
            'total_ratings' => $this->total_ratings,
            'policy' => new PolicyResource($this->whenLoaded('policy')),
               
           
            // 'shipping_method' => $shippingMethod ? [
            //     'id' => $shippingMethod->id,
            //     'type' => $shippingMethod->type,
            //     // 'duration' => $shippingMethod->duration,
            //     // 'amount' => $shippingMethod->amount,
            // ] : null,
           
        ];
    }
}
