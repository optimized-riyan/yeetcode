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
            'name' => [ 'required', 'max:127', $this->problem ? Rule::unique('problems')->ignore($this->problem->id) : Rule::unique('problems') ],
            'difficulty' => 'integer|lte:3|gte:1',
            'description' => 'required|max:1023',
            'scaffholdings' => 'required|array',
            'scaffholdings.*' => 'nullable|max:4095',
            'tc_parameters' => 'min:1',
            'tc_parameters.*.param' => 'required|max:127',
            'examples' => '',
            'examples.*.input' => 'required|max:1023',
            'examples.*.output' => 'required|max:1023',
            'examples.*.explaination' => 'max:1023',
            'constraints.*.constraint' => 'max:255',
            'testcases' => 'min:1',
            'testcases.*.testcase' => 'required|max:1023',
            'testcases.*.output' => 'required|max:1023',
            'testcases.*.is_trivial' => 'boolean',
            'selected_topics' => '',
            'selected_topics.*.id' => 'required|exists:topics,id',
            'selected_topics.*.name' => 'required|max:127',
            'new_topics' => '',
            'new_topics.*' => 'required',
            'similar_problems' => '',
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
            'tc_parameters.*.param.required' => 'Tc parameter #:position is empty',
            'tc_parameters.*.param.max' => 'Tc parameter #:position should be under :max characters',
            'constraints.*.constraint.required' => 'Constraint #:position is empty',
            'testcases.min' => 'Please enter at least :min testcase',
            'testcases.*.testcase.required' => 'Testcase #:position context is empty',
            'testcases.*.testcase.max' => 'Testcase #:position\'s context must be under :max characters',
            'testcases.*.output.required' => 'Testcase #:position output is empty',
            'testcases.*.output.max' => 'Testcase #:position\'s output must be under :max characters',
            'testcases.*.is_trivial.boolean' => 'Testcase #:position\'s \"is Trivial" value needs to be a boolean',
            'selected_topics.*.id.required' => 'Similar topic #:position\'s id is required',
            'selected_topics.*.id.exists' => 'Similar topic #:position does not exist',
            'selected_topics.*.name.required' => 'Similar topic #:position\'s name is required',
            'selected_topics.*.name.max' => 'Similar topic #:position\'s name must be under :max characters',
            'new_topics.*.required' => 'New topic #:position name is empty',
            'similar_problems.*.id.required' => 'Similar problem #:position id is empty',
            'similar_problems.*.id.exists' => 'Similar problem #:position does not exist',
            'similar_problems.*.name.required' => 'Similar problem #:position name is empty',
            'similar_problems.*.name.max' => 'Similar problem #:position name must be under :max characters',
            'hints.*.hint.required' => 'Hint #:position is empty',
            'hints.*.hint.max' => 'Hint #:position must be under :max characters',
        ];
    }
}
