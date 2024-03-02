<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProblemRequest extends FormRequest
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
            'name' => 'required|max:127',
            'description' => 'required|max:1023',
            'examples' => 'min:1|array:input,output,explaination',
            'examples.*' => 'required|max:1023',
            'testcases' => 'min:1',
            'topics' => 'min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'Name must be under :max characters',
            'description.max' => 'Description must be under :max characters',
            'examples.min' => 'Please enter at least :min example',
            'examples.*.max' => 'Example must be under :max characters',
            'testcases.min' => 'Please enter at least :min testcase',
        ];
    }
}
