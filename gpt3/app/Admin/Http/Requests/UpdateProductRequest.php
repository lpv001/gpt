<?php

namespace App\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Admin\Models\Product;

class UpdateProductRequest extends FormRequest
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
        $rules = Product::$rules;

        //dd($this->file('images'));
        if ($this->hasFile('images')) {
            $images = count($this->file('images'));
            foreach (range(0, $images) as $index) {
                // $rules['images.' . $index] = 'nullable|image|mimes:jpg,jpeg,png|max:20000';
            }
        }

        return $rules;
        //return Product::$rules;
    }
}
