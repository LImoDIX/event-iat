<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VisitorProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        // Если загружается только фото, делаем name и email необязательными
        if ($this->hasFile('profile_photo')) {
            return [
                'name' => ['sometimes', 'string', 'max:255'],
                'email' => [
                    'sometimes',
                    'string',
                    'lowercase',
                    'email',
                    'max:255',
                    Rule::unique(User::class)->ignore($this->user()->id),
                ],
                'profile_photo_path' => ['nullable', 'string', 'max:255'],
                'profile_photo' => ['nullable', 'file', 'image', 'max:2048'], // 2MB максимум
            ];
        }
        
        // Если обновляются данные профиля, требуем name и email
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'profile_photo_path' => ['nullable', 'string', 'max:255'],
            'profile_photo' => ['nullable', 'file', 'image', 'max:2048'], // 2MB максимум
        ];
    }
}
