<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'username' => 'required|unique:users,username',
            'email' => 'required|email:rfc,dns|unique:users,email|',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required|same:password',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => 'Username harus diisi',
            'username.unique' => 'Username sudah ada',
            'email.required' => 'Email harus diisi',
            'email.unique' => 'Email sudah ada',
            'email.email' => 'Email harus berupa alamat email yang valid',
            'email.dns' => 'Email harus berupa alamat email yang valid',
            'email.rfc' => 'Email harus berupa alamat email yang valid',
            'password.required' => 'Kata sandi harus diisi',
            'password.min' => 'Kata sandi harus minimal 6 karakter',
            'password.confirmed' => 'Kata sandi dan konfirmasi kata sandi harus sama',
            'password_confirmation.required' => 'Konfirmasi kata sandi harus diisi',
            'password_confirmation.same' => 'Kata sandi dan konfirmasi kata sandi harus sama',
        ];
    }
}
