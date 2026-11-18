<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'profile_photo.uploaded' => 'La photo n’a pas pu être téléversée. Elle doit peser au maximum 8 Mo. Vérifiez aussi les limites de téléversement PHP.',
            'profile_photo.image' => 'Le fichier choisi doit être une image valide.',
            'profile_photo.mimes' => 'La photo doit être au format JPG, PNG ou WEBP.',
            'profile_photo.max' => 'La photo ne peut pas dépasser 8 Mo.',
        ];
    }
}
