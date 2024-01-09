<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\SalePriceValidator;

class StorePostProductRequest extends FormRequest
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
            'name' => 'required|min:2|max:150',
            'categories_id' => 'numeric|required',
            'description' => 'required',
            'price' => ['required','numeric', new SalePriceValidator],
            'image' => ['required','max:2048'],
            'image.*' => 'mimes:jpg,png,jpeg,gif,svg',
            'quantity' => 'required|numeric',
            'status' => 'required|numeric',
            'color_id' => 'required',
            'size_id' => 'required',
            'tag_id' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Ten san pham khong duoc trong',
            'name.min' => 'Ten san pham phai nhieu hon :min ky tu',
            'name.max' => 'Ten san pham phai it hon :max ky tu',
            'categories_id.required' => 'Vui long chon danh muc',
            'categories_id.numeric' => 'Du lieu danh muc san pham khong chinh xac',
            'description.required' => 'Vui long nhap mo ta san pham',
            'price.required' => 'Vui long nhap gia san pham',
            'price.numeric' => 'Gia san pham phai la so',
            'image.required' => 'Vui long chon anh san pham',
            'image.*.mimes' => 'Anh chi chap nhan : jpg,png,jpeg,gif,svg',
            'image.max' => 'Kich thuoc anh khong vuot qua 2mb',
            'quantity.required' => 'Vui long nhap so luong san pham',
            'quantity.numeric' => 'So luong san pham phai la so',
            'status.required' => 'Vui long chon trang thai cho san pham',
            'status.numeric' => 'Du lieu trang thai cua san pham khong dung',
            'color_id.required' => 'Vui long chon mau sac cho san pham',
            'size_id.required' => 'Vui long chon size cho san pham',
            'tag_id.required' => 'Vui long chon tags cho san pham'
        ];
    }
}
