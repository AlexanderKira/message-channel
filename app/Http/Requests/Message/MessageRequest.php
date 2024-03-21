<?php

namespace App\Http\Requests\Message;

use App\Enums\MessageTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class MessageRequest extends FormRequest
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
        $messageType = MessageTypeEnum::tryFrom(data_get(request()->input(), 'type'));

        $rules = [
            'content' => ['required', 'string', 'max:65535'],
            'type' => ['required', new Enum(MessageTypeEnum::class)],
            'recipient_id' => ['nullable'],
        ];

        if ($messageType == MessageTypeEnum::PRIVATE) {
            $rules['recipient_id'][] = 'required';
        }

        return $rules;
    }
}
