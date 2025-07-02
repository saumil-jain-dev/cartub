<?php

namespace App\Http\Requests\Api\Cleaner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
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
            //
            'email' => [
                'required',
                'email:rfc,dns',
                Rule::exists('users', 'email')->where('role', $this->input('role'))
            ],
            'password' => 'required',
            'role' => 'required|in:cleaner',
            'device_id' => 'required',
            'device_type' => 'required|in:android,ios',
            'device_token' => 'required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            fail([], error_parse($validator->errors()), config('code.VALIDATION_ERROR_CODE'))
        );
    }
}
