<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Code extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'code' => ['required', 'numeric', 'digits:4']
        ];
    }

    /**
     * validation message
     * @return array
    */
    public function message(): array
    {
        return [
            'code.digits' => __('auth.invalidCode')
        ];
    }
}
