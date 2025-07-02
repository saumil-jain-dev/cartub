<?php

namespace App\Http\Requests\Api\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class OtpVerificationRequest extends FormRequest
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
            'phone' => [
                'required',
                'numeric',
                'digits:10',
            ],
            'otp' => [
                'required',
                'numeric',
                'digits:6',
            ],
            'role' => 'required|in:customer',
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
