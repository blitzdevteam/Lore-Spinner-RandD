<?php

declare(strict_types=1);

namespace App\Http\Requests\User\Authentication;

use App\Enums\GenderEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class UpdateCompleteProfileRequest extends FormRequest
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
            'username' => [
                'required',
                'string',
                'min:3',
                'max:24',
                'regex:/^[a-zA-Z][a-zA-Z0-9_]*$/',
                Rule::unique('users', 'username')->ignore($this->user()->id),
            ],
            'first_name' => ['required', 'string', 'min:3', 'max:64'],
            'last_name' => ['required', 'string', 'min:3', 'max:64'],
            'gender' => ['required', 'string', Rule::in(GenderEnum::values())],
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'username' => $this->string('username')->lower()->value(),
            'gender' => 'male',
        ]);
    }
}
