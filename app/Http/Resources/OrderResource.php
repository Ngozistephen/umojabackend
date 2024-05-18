<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $vendorId = Auth::user()->vendor->id;
        return [
            // 'data' => $this->makeHidden([
            //     'change_amount', 'distance', 'duration', 'grand_total',
            //     'service_charge', 'delivery_fee', 'delivered_time',
            //     'recieved_time', 'reject_reason', 'rejected_time',
            //     'served_time', 'transit_time', 'total'
            // ]),
            'id' => $this->id,
            'order_id' => $this->order_number,
            'read' => $this->read,
            'created_at' => $this->created_at,
            'customer_fullname'=> $this->shippingAddress?->shipping_full_name,
            'total' => $this->total_amount,
            'discount' => $this->discount_code?->discount_amount,
            'delivery_price' => $this->delivery_charge,
            'sub_total' => $this->sub_total,
            'payment_status' => $this->payment_status,
            // 'items' => $this->products->map(function ($product) use ($vendorId) {
            //     if ($product->pivot->vendor_id == $vendorId) {
            //         return [
            //             'id' => $product->id,
            //             'name' => $product->name,
            //             'qty' => $product->pivot->qty,
            //             'photo' => $product->photo,
            //             'cost_per_item' => $product->cost_per_item,
            //             'colors' => $product->colors,
            //             'price' => $product->pivot->price,
            //             'vendor_id' => $product->pivot->vendor_id
            //             // Add other fields you want to include
            //         ];
            //     }
            // })->filter(),
            'items' => $this->products->filter(function ($product) use ($vendorId) {
                return $product->pivot->vendor_id == $vendorId;
            })->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'qty' => $product->pivot->qty,
                    'photo' => $product->photo,
                    'cost_per_item' => $product->cost_per_item,
                    'colors' => $product->colors,
                    'price' => $product->pivot->price,
                    'vendor_id' => $product->pivot->vendor_id
                
                ];
            }),
            'fulfillment_status'=>$this->fulfillment_status,  
            'delivery_method' => $this->shippingMethod?->type,
            'delivery_duration' => $this->shippingMethod?->duration,
            'customer_email' => $this->shippingAddress?->shipping_email,
            'customer_phone' => $this->shippingAddress?->shipping_phone_number,
            'customer_address' => $this->shippingAddress?->shipping_address,
            'customer_city' => $this->shippingAddress?->shipping_city,
            'customer_region' => $this->shippingAddress?->shipping_region,
            'customer_postal_code' => $this->shippingAddress?->shipping_postal_code,
            'customer_country' => $this->shippingAddress?->shipping_country,
           
           

            
        ];

    }
}
