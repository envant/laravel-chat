<?php

namespace Envant\Chat\Requests\Conversation;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'model_type' => 'required|string',
            'model_id' => 'required|numeric',
            'name' => 'required|string',
        ];
    }
}
