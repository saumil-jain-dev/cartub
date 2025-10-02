<?php

namespace App\Http\Requests\Api\Cleaner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'name' => [
                'required',
                'regex:/^[A-Za-z\s]+$/',
                'max:255',
            ],
            'email'      => [
                'required',
                'email',
                Rule::unique('users', 'email')
                    ->where('role', $this->input('role'))
                    ->whereNull('deleted_at') // Ignore soft-deleted users
            ],
            'phone'      => [
                'required',
                'numeric',
                'digits:11',
                'regex:/^0[0-9]{10}$/',
                Rule::unique('users', 'phone')
                    ->where('role', $this->input('role'))
                    ->whereNull('deleted_at') // Ignore soft-deleted users
            ],
            'password'   => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'role' => 'required|in:cleaner',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            fail([], error_parse($validator->errors()), config('code.VALIDATION_ERROR_CODE'))
        );
    }
}
