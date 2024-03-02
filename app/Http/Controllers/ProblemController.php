<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProblemRequest;
use App\Models\Constraint;
use App\Models\Example;
use App\Models\Hint;
use Illuminate\Http\Request;
use App\Models\Problem;
use Inertia\Inertia;

class ProblemController extends Controller
{
    public function index()
    {
        return Inertia::render('ProblemSet', [
            'problemList' => Problem::with('difficulty')->get(),
        ]);
    }

    public function show(Problem $problem)
    {
        return Inertia::render('EditorPage', [
            'problem' => $problem->load([
                'testcases' => function ($query) {
                    $query->where('is_trivial', 1);
                },
            ])
        ]);
    }

    public function run(Request $request, Problem $problem)
    {
        $result = shell_exec('pwd');
        return response()->json([
            'result' => $result,
        ]);
    }

    public function create()
    {
        return Inertia::render('StoreProblem', []);
    }

    public function store(ProblemRequest $request)
    {
        $form = $request->validated();

        $problem = new Problem();
        $problem->name = $form->name;
        $problem->description = $form->description;

        foreach ($form->examples as $example) {
            $exampleModel = new Example();
            $exampleModel->input = $example->input;
            $exampleModel->output = $example->output;
            $exampleModel->explaination = $example->explaination;
            $problem->examples()->save($exampleModel);
        }
        foreach ($form->constraints as $constraint) {
            $constraintModel = new Constraint();
            $constraintModel->constraint = $constraint;
            $problem->constraints()->save($constraintModel);
        }
        foreach ($form->testcases as $testcase) {

        }
        foreach ($form->topics as $topic) {

        }
        foreach ($form->similar_problems as $sim) {

        }
        foreach ($form->hints as $index => $hint) {
            $hintModel = new Hint();
            $hintModel->hint_number = $index;
            $hintModel->brief = $hint;
            $problem->hints()->save($hintModel);
        }

        return to_route('problems.index');
    }

    public function getProblemsByTitle(Request $request)
    {
        $input = $request->input('like');

        $problems = Problem::problemsByTitle($input)->select('name')->get();
        return response()->json([
            'problems' => $problems,
        ]);
    }
}
