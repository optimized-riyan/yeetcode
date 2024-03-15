<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
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
            'name' => [ 'required', 'max:127', Rule::unique('problems')->ignore($this->problem->id) ],
            'difficulty' => 'integer|lte:3|gte:1',
            'description' => 'required|max:1023',
            'scaffholding' => 'max:2047',
            'tc_parameters' => 'min:1',
            'tc_parameters.*.param' => 'required|max:127',
            'examples' => 'min:1',
            'examples.*.input' => 'required|max:1023',
            'examples.*.output' => 'required|max:1023',
            'examples.*.explaination' => 'max:1023',
            'constraints.*.constraint' => 'max:255',
            'testcases' => 'min:1',
            'testcases.*.testcase' => 'required|max:1023',
            'testcases.*.expected_output' => 'required|max:1023',
            'testcases.*.is_trivial' => 'boolean',
            'selected_topics.*.id' => 'required|exists:topics,id',
            'selected_topics.*.name' => 'required|max:127',
            'new_topics.*' => 'required',
            'similar_problems.*.id' => 'required|exists:problems,id',
            'similar_problems.*.name' => 'required|max:127',
            'hints.*.hint' => 'required|max:2047',
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
