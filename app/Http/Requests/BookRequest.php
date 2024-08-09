<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
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
            'title' => 'required',
            'summary' => 'required',
            'stok' => 'required|numeric',
            'status' => 'nullable',
            'author' => 'nullable',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => ' Judul harus diisi',
            'summary.required' => 'Ringkasan harus diisi',
            'stok.required' => 'Stok is required || Stok harus diisi',
            'stok.numeric' => ' Stok harus berupa angka',
            'cover.image' => 'Cover harus berupa gambar',
            'cover.mimes' => 'Cover harus berupa file bertipe: jpeg, png, jpg, gif, svg',
            'cover.max' => 'Cover harus kurang dari 2MB',
            'category_id.required' => 'Kategori harus diisi',
            'category_id.exists' => 'Kategori tidak ditemukan',
        ];
    }
}
