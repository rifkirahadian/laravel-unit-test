<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|max:100',
            'description' => 'nullable',
            'price' => 'required|integer',
            'is_show' => 'required|boolean',
            'category' => 'required|string',
        ];
    }
}
