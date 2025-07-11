<?php

namespace App\Http\Requests\Channels;

use Illuminate\Foundation\Http\FormRequest;

class SendChatMessageRequest extends FormRequest
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
        $chSize = config("services.utils.max_chat_file_size");
        return [
            //
            "message" => ["nullable"],
            "files" => ["nullable"],
            "files.*" => ["max:$chSize"],
            "captions" => ["nullable"],
            "captions.*" => ["nullable"],
            "descriptions" => ["nullable"],
            "descriptions.*" => ["nullable"],
            "reaction" => ["nullable"],
            "sticker" => ["nullable"]
        ];
    }
}
