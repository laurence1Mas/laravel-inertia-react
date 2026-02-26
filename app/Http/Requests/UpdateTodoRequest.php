<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTodoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $todo = $this->route('todo');
        return $todo && $this->user()->id === $todo->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'completed' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.max' => 'Le titre ne peut pas dépasser 255 caractères.',
            'completed.boolean' => 'Le statut complété doit être un booléen.',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('completed') && is_string($this->completed)) {
            $this->merge([
                'completed' => $this->completed === 'true' || $this->completed === '1',
            ]);
        }
    }
}
