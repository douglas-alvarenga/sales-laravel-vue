<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEditSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'amount' => 'required|numeric',
            'seller_id' => 'required|exists:sellers,id',
            'date' => 'required|date|date_format:Y-m-d H:i:s|before_or_equal:now',
            'seller_commision_percentage' => 'nullable|numeric',
        ];
    }
}
