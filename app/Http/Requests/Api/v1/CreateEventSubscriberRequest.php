<?php

namespace App\Http\Requests\Api\v1;

use App\Http\Requests\Api\ApiRequest;

class CreateEventSubscriberRequest extends ApiRequest
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
        return  [
            'name' => 'required',
            'email' => 'required|email|unique:event_subscribers',
        ];
    }
}
