<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProblemRequest;
use App\Models\Example;
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

        $problem = new Problem;
        $problem->name = $form->name;
        $problem->description = $form->description;

        foreach ($form->examples as $example) {
            $exampleModel = new Example;
            $exampleModel->input = $example->input;
            $exampleModel->output = $example->output;
            $exampleModel->explaination = $example->explaination;
            $problem->examples()->save($exampleModel);
        }
        foreach ($form->constraints as $constraint) {
        }

        return to_route('problems.index');
    }
}
