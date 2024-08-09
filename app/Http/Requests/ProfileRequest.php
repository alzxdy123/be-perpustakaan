<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'age' => 'required|integer|min:1',
            'address' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'age.required' => 'Age is required || Umur harus diisi',
            'age.integer' => 'Age must be an integer || Umur harus berupa angka bulat',
            'age.min' => 'Age must be at least 1 || Umur harus minimal 1 tahun',
            'address.required' => 'Address is required || Alamat harus diisi',
            'address.string' => 'Address must be a string || Alamat harus berupa teks',
            'address.max' => 'Address may not be greater than 255 characters || Alamat tidak boleh lebih dari 255 karakter',
            'bio.string' => 'Bio must be a string || Bio harus berupa teks',
            'avatar.image' => 'Avatar must be an image || Avatar harus berupa gambar',
            'avatar.mimes' => 'Avatar must be a file of type: jpeg, png, jpg, gif, svg || Avatar harus berupa file dengan tipe: jpeg, png, jpg, gif, svg',
            'avatar.max' => 'Avatar may not be greater than 2048 kilobytes || Ukuran avatar tidak boleh lebih dari 2048 kilobyte',
        ];
    }
}
