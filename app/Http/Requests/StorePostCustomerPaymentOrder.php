<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePostCustomerPaymentOrder extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'required',
            'phone' => 'required|numeric',
            'email' => 'required|email',
            'shipping_address' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'Vui long nhap ho ten',
            'phone.required' => 'Vui long nhap so dien thoai',
            'phone.numeric' => 'So dien thoai chi duoc phep nhap chu so',
            'email.required' => 'Vui long nhap email',
            'email.email' => 'Email cua ban khong dung',
            'shipping_address.required' => 'Vui long nhap dia chi giao hang'
        ];
    }
}
