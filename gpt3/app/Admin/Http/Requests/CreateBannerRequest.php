<?php

namespace App\Admin\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Admin\Models\Banner;

class CreateBannerRequest extends FormRequest
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
            'title' => 'required|string|min:2|max:20|unique:banners,title,',
            'expiry_date' => 'required|date',
            'description'   => 'nullable|min:3',
            'target_url'    => 'required',
            'images' =>  'required'
        ];
    }

    // public function messages()
    // {
    //     return [
    //         'title.string' => 'Title should not contain number',
    //         'name.required' => 'Name is required!',
    //         'password.required' => 'Password is required!'
    //     ];
    // }
}
