<?php

namespace AppHttpRequests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class EditorialProjectShowRequest extends FormRequest
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
     * Prepare for validation
     */
    protected function prepareForValidation()
    {
        //explode trasforma una stringa in una array
        // author,log => explode => ['author','log']
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
            //
        ];
    }
}
