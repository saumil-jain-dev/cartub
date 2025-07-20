<?php

namespace App\Http\Requests\Api\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class BookingRequest extends FormRequest
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
            'customer_id' => [
                'required',
                'numeric',
                Rule::exists('users', 'id')->where(function ($query) {
                    $query->where('role', 'customer');
                }),
            ],
            'vehicle_id' => 'required|numeric|exists:vehicles,id',
            'add_ons_id' => 'nullable|string', // Assuming add_ons_id can be a string or null
            'service_id' => 'required|numeric|exists:services,id',
            'address' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            
            'notes' => 'nullable',
            'scheduled_date' => 'required|date',
            'scheduled_time' => 'required|date_format:H:i:s',
            'coupon_id' => 'nullable|numeric|exists:coupons,id',
            'gross_amount' => 'required|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'payment_method' => [
                'required',
                Rule::in(['card', 'google_pay', 'apple_pay']),
            ],
            'payment_status' => [
                'required',
                Rule::in(['pending', 'paid', 'failed']),
            ],
            'transaction_id' => 'required|string|max:255',
        ];
    }
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();

            // If coupon_id is present, then gross_amount, discount_amount, and coupon_code are required
            if (!empty($data['coupon_id'])) {
                if (empty($data['discount_amount'])) {
                    $validator->errors()->add('discount_amount', 'The discount amount is required when discount is applied.');
                }
                
            }
        });
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            fail([], error_parse($validator->errors()), config('code.VALIDATION_ERROR_CODE'))
        );
    }
}
