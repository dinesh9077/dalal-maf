<?php

namespace App\Http\Requests\Agent;

use App\Rules\ImageMimeTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use DB; 
class RegisterRequest extends FormRequest
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
            'image' => [
                'required',
                new ImageMimeTypeRule()
            ],
            'username' => 'required|max:255|unique:agents,username|regex:/^\S*$/u',
            'email' => 'required|email:rfc,dns|unique:agents,email',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => [
                'required',
                function ($attribute, $value, $fail) {
                    $existsInUsers = DB::table('users')->where('phone', $value)->exists();
                    $existsInVendors = DB::table('vendors')->where('phone', $value)->exists();
                    $existsInAgents = DB::table('agents')->where('phone', $value)->exists();

                    if ($existsInUsers || $existsInVendors || $existsInAgents) {
                        $fail('The phone number is already registered.');
                    }
                },
            ],
            // 'password' => 'required|confirmed|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[a-zA-Z\d@$!%*?&]{8,}$/',
            // 'password_confirmation' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'username.regex' => 'Space are not allowed in the username field.',
            'password.required' => 'The password field is required.',
            'password.min' => 'The password must be at least 8 characters long.',
            'password.regex' => 'The password must contain at least one uppercase letter, one lowercase letter, one digit, and one special character (@$!%*?&).',
            'password.confirmed' => 'Password confirmation does not match.',
            'password_confirmation.required' => 'The confirm password field is required.'
        ];
    }
}
