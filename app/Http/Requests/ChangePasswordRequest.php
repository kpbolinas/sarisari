<?php

namespace App\Http\Requests;

use App\Rules\VerificationPassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class ChangePasswordRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'password' => ['required', 'current_password'],
            'new_password' => [
                'required',
                Password::min(8)->mixedCase()->numbers()->symbols()
            ],
            'confirm_new_password' => 'required|same:new_password',
        ];
    }
}
