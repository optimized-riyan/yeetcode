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
        return Inertia::render('StoreProblem', [
            'url' => route('problems.store'),
            'method' => 'post',
        ]);
    }

    public function edit(Problem $problem)
    {
        $form = [
            'name' => $problem->name,
            'description' => $problem->description,
            'scaffholding' => $problem->scaffholding,
            'tc_parameters' => function () use ($problem) {
                $paramArray = [];
                foreach (explode(' ', $problem->tc_parameters) as $param) {
                    $paramArray[] = [
                        'param' => $param,
                    ];
                }
                return $paramArray;
            },
            'examples' => function () use ($problem) {
                $examplesArray = [];
                foreach ($problem->examples()->get() as $example) {
                    $data = [
                        'input' => $example->input,
                        'output' => $example->output,
                    ];
                    $data['explaination'] = $example->explaination ? $example->explaination : '';
                    $examplesArray[] = $data;
                }
                return $examplesArray;
            },
            'constraints' => function () use ($problem) {
                $constraintArray = [];
                foreach ($problem->constraints()->get() as $constraint) {
                    $constraintArray[] = [
                        'constraint' => $constraint->constraint,
                    ];
                }
                return $constraintArray;
            },
            'testcases' => function () use ($problem) {
                $testcaseArray = [];
                foreach ($problem->testcases()->get() as $testcase) {
                    $testcaseArray[] = [
                        'testcase' => $testcase->testcase,
                        'output' => $testcase->expected_output,
                        'is_trivial' => $testcase->is_trivial,
                    ];
                }
                return $testcaseArray;
            },
            'new_topics' => [],
            'selected_topics' => function () use ($problem) {
                $topicArray = [];
                foreach ($problem->topics()->get() as $topic) {
                    $topicArray[] = [
                        'id' => $topic->id,
                        'name' => $topic->name,
                    ];
                }
                return $topicArray;
            },
            'similar_problems' => function () use ($problem) {
                $simProblemArray = [];
                foreach ($problem->similarProblems()->get() as $sim) {
                    $simProblemArray[] = [
                        'id' => $sim->id,
                        'name' => $sim->name,
                    ];
                }
                return $simProblemArray;
            },
            'hints' => function () use ($problem) {
                $hintArray = [];
                foreach ($problem->hints()->orderBy('hint_number')->get() as $hint) {
                    $hintArray[] = [
                        'hint_number' => $hint->hint_number,
                        'hint' => $hint->brief,
                    ];
                }
                return $hintArray;
            }
        ];
        return Inertia::render('StoreProblem', [
            'prefilledForm' => $form,
            Log::channel('debug')->info(json_encode($form)),
            'url' => route('problems.update', [ 'problem' => $problem ]),
            'method' => 'put',
        ]);
    }

    public function update(Problem $problem, ProblemRequest $request)
    {
        try {
            $form = $request->validated();
            $this->processProblemRequest($form, $problem);
            return to_route('problems.show', [ 'problem' => $problem ]);
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function store(ProblemRequest $request)
    {
        try {
            $form = $request->validated();
            $this->processProblemRequest($form, new Problem());
            return to_route('problems.index');
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function processProblemRequest(Array $form, Problem $problem)
    {
        $problem->name = $form['name'];
        $problem->difficulty_id = $form['difficulty'];
        $problem->description = $form['description'];
        $problem->scaffholding = $form['scaffholding'];

        $tc_parameters_conc = '';
        foreach ($form['tc_parameters'] as $tc_param) {
            $tc_parameters_conc = $tc_parameters_conc . ' ' . $tc_param['param'];
        }
        $problem->tc_parameters = trim($tc_parameters_conc);
        $problem->save();

        $problem->examples()->delete();
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

        $problem->constraints()->delete();
        $constraintModels = [];
        foreach ($form['constraints'] as $constraint) {
            $constraintModel = new Constraint();
            $constraintModel->constraint = $constraint['constraint'];
            array_push($constraintModels, $constraintModel);
        }
        $problem->constraints()->saveMany($constraintModels);

        $problem->testcases()->delete();
        $testcaseModels = [];
        foreach ($form['testcases'] as $testcase) {
            $testcaseModel = new Testcase();
            $testcaseModel->testcase = $testcase['testcase'];
            $testcaseModel->expected_output = $testcase['output'];
            $testcaseModel->is_trivial = $testcase['is_trivial'];
            array_push($testcaseModels, $testcaseModel);
        }
        $problem->testcases()->saveMany($testcaseModels);

        // new and selected topics
        $problem->topics()->delete();
        $topicModels = [];
        foreach ($form['new_topics'] as $new_topic) {
            $topicModel = new Topic();
            $topicModel->name = $new_topic;
            $topicModel->save();
            array_push($topicModels, $topicModel->id);
        }

        $ids = array_map(fn($selected_topic) => $selected_topic['id'], $form['selected_topics']);
        foreach (Topic::whereIn('id', $ids)->get() as $topicModel) {
            array_push($topicModels, $topicModel->id);
        }

        $simProbIds = [];
        foreach ($form['similar_problems'] as $sim) {
            $problemModel = Problem::findOrFail($sim['id']);
            array_push($simProbIds, $problemModel->id);
        }
        $problem->similarProblems()->sync($simProbIds);

        $problem->hints()->delete();
        $hintModels = [];
        foreach ($form['hints'] as $index => $hint) {
            $hintModel = new Hint();
            $hintModel->hint_number = $index;
            $hintModel->brief = $hint['hint'];
            array_push($hintModels, $hintModel);
        }
        $problem->hints()->saveMany($hintModels);
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
