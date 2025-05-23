<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEditUserRequest extends FormRequest
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
            'name' => 'required|min:3|max:100',
            'username' => [
                'required',
                'regex:/^[A-Za-z0-9_.]+$/',
                'min:3',
                'max:20',
                Rule::unique('users', 'username')->ignore($this->user),
            ],
            'email' => ['required', 'email', 'max:50', Rule::unique('users', 'email')->ignore($this->user)],
            'password' => $this->isMethod('post')
                ? 'required|min:4|max:20'
                : 'nullable|min:4|max:20',
        ];
    }
}
