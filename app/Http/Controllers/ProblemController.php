<?php

namespace App\Http\Controllers;

use App\Models\Hint;
use App\Models\Testcase;
use Inertia\Inertia;
use App\Models\Topic;
use App\Models\Example;
use App\Models\Problem;
use App\Models\Constraint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProblemRequest;
use Exception;

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
        $problem = null;
        try {
            $form = $request->validated();
            // Log::channel('debug')->info(json_encode($form));

            $problem = new Problem();
            $problem->name = $form['name'];
            $problem->difficulty_id = $form['difficulty'];
            $problem->description = $form['description'];
            $problem->scaffholding = $form['scaffholding'];

            $tc_parameters_conc = '';
            foreach ($form['tc_parameters'] as $tc_param) {
                $tc_parameters_conc = $tc_parameters_conc . ' ' . $tc_param['param'];
            }
            $problem->tc_parameters = $tc_parameters_conc;

            $problem->save();

            $exampleModels = [];
            foreach ($form['examples'] as $example) {
                $exampleModel = new Example();
                $exampleModel->input = $example['input'];
                $exampleModel->output = $example['output'];
                if ($example['explaination'])
                    $exampleModel->explaination = $example['explaination'];
                array_push($exampleModels, $exampleModel);
            }
            $problem->examples()->saveMany($exampleModels);

            $constraintModels = [];
            foreach ($form['constraints'] as $constraint) {
                $constraintModel = new Constraint();
                $constraintModel->constraint = $constraint['constraint'];
                array_push($constraintModels, $constraintModel);
            }
            $problem->constraints()->saveMany($constraintModels);

            $testcaseModels = [];
            foreach ($form['testcases'] as $testcase) {
                $testcaseModel = new Testcase();
                $testcaseModel->testcase = $testcase['testcase'];
                $testcaseModel->expected_output = $testcase['output'];
                $testcaseModel->is_trivial = $testcase['is_trivial'];
                array_push($testcaseModels, $testcaseModel);
            }
            $problem->testcases()->saveMany($testcaseModels);

            foreach ($form['similar_problems'] as $sim) {
                $problemModel = Problem::findOrFail($sim['id']);
                $problemModel->similarProblems()->attach($problem);
                $problemModel->save();
            }

            $hintModels = [];
            foreach ($form['hints'] as $index => $hint) {
                $hintModel = new Hint();
                $hintModel->hint_number = $index;
                $hintModel->brief = $hint['hint'];
                array_push($hintModels, $hintModel);
            }
            $problem->hints()->saveMany($hintModels);

            return to_route('problems.index');
        }
        catch (Exception $e) {
            // if ($problem->exists())
            //     $problem->delete();

            throw $e;
        }
    }

    public function getProblemsByTitle(Request $request)
    {
        $input = $request->input('probs');

        $problems = Problem::problemsByTitle($input)->select(['id', 'name'])->get();
        return response()->json([
            'problems' => $problems,
        ]);
    }

    public function getTopicsWithFilter(Request $request)
    {
        $input = $request->input('topics');

        $topics = Topic::topicsWithFilter($input)->select('id', 'name')->get();
        return response()->json([
            'topics' => $topics,
        ]);
    }
}
