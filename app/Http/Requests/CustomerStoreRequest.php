<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerStoreRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|string|max:20|regex:/(^([a-zA-Z]+)(\d+)?$)/u',
            'last_name' => 'required|string|max:20|regex:/(^([a-zA-Z]+)(\d+)?$)/u',
            'email' => 'email|unique:customers',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'photo' => 'nullable|image',
        ];
    }
}
