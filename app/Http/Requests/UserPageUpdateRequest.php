<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPageUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'profile_html' => ['string', 'nullable'],
			'avatar' => ['mimes:jpg,jpeg,png,gif', 'max:1024'],
			'banner' => ['mimes:jpg,jpeg,png,gif', 'max:2048']
        ];
    }
}
