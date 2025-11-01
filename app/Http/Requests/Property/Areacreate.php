<?php

namespace App\Http\Requests\Property;

use App\Models\BasicSettings\Basic;
use App\Models\Language;
use App\Models\Property\Country;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Areacreate extends FormRequest
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
        $rules = [
            'name' => 'required',
            'status' => 'required|numeric'
        ];
        return $rules;
    }
    public function messages()
    {
        $languages = Language::all();
        $message['name.required_if'] = 'The name field is required.';
     
        return $message;
    }
}
