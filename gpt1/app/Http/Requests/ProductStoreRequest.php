<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
        // return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'product_code' => 'nullable|string',
            'name' =>  'required|string|min:3|unique:products,name',
            'price' => 'required|numeric',
            'unit_id' => 'nullable|integer',
            'category_id' => 'required|integer',
            'brand_id' => 'required|integer',
            // 'images' => 'required|array',
            'images.*' => 'required',

        ];
    }

    public function messages()
    {
        return [
            'product_code' => 'Product Code must be string',
            'name' =>  'Name is required or must be greater than 3',
            'price.required' => 'Price is required',
            'price.numeric' => 'Price is must be number',
            'unit_id' => 'unit_id is required',
            'category_id.required' => 'Product category is required',
            'brand_id.required' => 'Product brand is required',
            // 'images.' => 'Image must be image file ',
            'options.name.required' => 'option name be image file ',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()->first()
        ], 403));
    }
}
