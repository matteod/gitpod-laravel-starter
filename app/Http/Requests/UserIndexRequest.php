<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Prepare inputs for validation
     */
    protected function prepareForValidation()
    {
        // Transform relations into array
        if ($this->has('with')) {
            $this->merge(['with' => explode(',', $this->with)]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'with' => 'array|nullable',
            'text' => 'string|nullable',
        ];
    }
}