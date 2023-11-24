<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:32', 'regex:/^[A-Za-z0-9\-]*$/'],
			'display_name' => ['string', 'max:255', 'regex:/^[^<>]*$/'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
			'custom_flair' => ['string', 'max:32', 'regex:/^[a-z\-]+$/']
        ];
    }

	public function messages() {
		return [
			'name.regex' => "Username should contain only alphanumeric characters and hyphens.",
			'custom_flair.regex' => "Custom flair should only contain lowercase letters and hyphens."
		];
	}
}
