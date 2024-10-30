<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'user'=>'required',
            'password'=>'required',
        ];
    }
    public function messages(){
        return [
            'user.required'=>'El USER es obligatorio',
            'password.required'=>'El password es obligatorio',
        ];
    }
}
