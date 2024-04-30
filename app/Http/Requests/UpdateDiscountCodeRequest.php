<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDiscountCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return  Gate::allows('all-access');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // $discountCodeId = $this->route('discountCode')->id; 

        return [
            'code' => 'required|unique:discount_codes,code',
            // 'code' => 'required|unique:discount_codes,code,' . $discountCodeId,
            'discount_amount' => 'required|numeric|min:0',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date|after:valid_from',
            'usage_limit' => 'nullable|integer|min:1',
        ];
    }
}
