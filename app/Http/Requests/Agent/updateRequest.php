<?php

namespace App\Http\Requests\Agent;

use App\Rules\ImageMimeTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use DB;

class updateRequest extends FormRequest
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
            'image' => $this->hasFile('image') ? new ImageMimeTypeRule() : '',

            'username' => [
                'required',
                'max:255',
                Rule::unique('agents')->ignore($this->id),
                'regex:/^\S*$/u'
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::unique('agents')->ignore($this->id)
            ],
            'phone' => [
                'required',
                Rule::unique('users', 'phone')->ignore($this->id),  // allow same user phone

                function ($attribute, $value, $fail)  {

                    // Check in VENDORS table
                    $existsInVendors = DB::table('vendors')
                        ->where('phone', $value)
                        ->where('id', '!=', $this->id)
                        ->exists();

                    // Check in AGENTS table
                    $existsInAgents = DB::table('agents')
                        ->where('phone', $value)
                        ->where('id', '!=', $this->id)
                        ->exists();

                    // If duplicate found in any other table → fail
                    if ($existsInVendors || $existsInAgents) {
                        $fail('The phone number is already registered.');
                    }
                }
            ],
        ];
    }
    public function messages()
    {
        return [

            'username.regex' => 'Space are not allowed in the username field.',
        ];
    }
}
