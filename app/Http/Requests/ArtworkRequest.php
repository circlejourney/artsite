<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArtworkRequest extends FormRequest
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
            'title' => ["required", "max:255"],
			'images.*' => ["mimes:jpg,jpeg,png,gif", "max:4096"],
			"artist.*" => ["string", "max:255", "nullable"],
			"tags" => ["string", "max:1000", "nullable"]
        ];
    }
}
