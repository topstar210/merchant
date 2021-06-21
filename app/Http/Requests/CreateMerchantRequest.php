<?php

namespace App\Http\Requests;


use App\Extend\FormRequest;

class CreateMerchantRequest extends FormRequest
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
            'merchant_name'=> 'required|unique:merchants_rev,merchant_name',
            'merchant_address'=> 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'country_id' => 'required|exists:countries,id',
            'city' => 'required',
            'commission' => 'required|numeric',
            'logo' => 'nullable|url',
            'site_url' => 'nullable|url',
        ];
    }

    protected $stopOnFirstFailure = true;
}
