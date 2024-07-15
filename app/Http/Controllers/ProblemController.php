<?php

namespace App\Http\Controllers;

use App\Models\Hint;
use App\Models\Scaffholding;
use App\Models\Testcase;
use Inertia\Inertia;
use App\Models\Topic;
use App\Models\Example;
use App\Models\Problem;
use App\Models\Constraint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProblemRequest;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class ProblemController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('ProblemSet', [
            'problemList' => Problem::where("name", "like", "%" . $request->input("name") . "%")->with('difficulty')->paginate(10),
            "exploredProblems" => function () {
                $user = User::find(auth()->user()->id);
                $probIds = [];
                foreach ($user->exploredProblems as $prob) {
                    $probIds[$prob->pivot->problem_id] = $prob->pivot->status;
                }
                return $probIds;
            },
            'avatarImage' => auth()->user()->avatar_image,
        ]);
    }

    public function show(Problem $problem)
    {
        return Inertia::render('EditorPage', [
            'problem' => $problem->load([
                'testcases' => function ($query) {
                    $query->where('is_trivial', 1);
                },
                "examples",
                "constraints",
                "topics",
                "hints",
                "similarProblems",
            ]),
            "scaffholdings" => function () use ($problem) {
                $scaffArray = [];
                foreach ($problem->scaffholdings()->get() as $scaff) {
                    $scaffArray[$scaff->language_id] = $scaff->scaffholding;
                }
                return $scaffArray;
            },
            "user" => function () {
                $user = auth()->user();
                $newArr["id"] = $user["id"];
                $newArr["avatarImage"] = $user["avatar_image"];
                return $newArr;
            },
        ]);
    }

    public function create()
    {
        Gate::authorize("upsert-problem");

        return Inertia::render('StoreProblem', [
            'url' => route('problems.store'),
            'method' => 'post',
        ]);
    }

    public function edit(Problem $problem)
    {
        Gate::authorize("upsert-problem");

        $form = [
            'name' => $problem->name,
            'description' => $problem->description,
            // 'scaffholdings' => $problem->scaffholdings,
            "scaffholdings" => function () use ($problem) {
                $scaffArray = [];
                foreach ($problem->scaffholdings()->get() as $scaff) {
                    $scaffArray[$scaff->language_id] = $scaff->scaffholding;
                }
                return $scaffArray;
            },
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
                        'is_trivial' => $testcase->is_trivial == 0 ? false : true,
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
            'url' => route('problems.update', ['problem' => $problem]),
            'method' => 'put',
        ]);
    }

    public function update(Problem $problem, ProblemRequest $request)
    {
        Gate::authorize("upsert-problem");

        $form = $request->validated();
        // Log::channel("debug")->info($form["scaffholdings"]);
        $this->processProblemRequest($form, $problem);
        return to_route('problems.show', ['problem' => $problem]);
    }

    public function store(ProblemRequest $request)
    {
        Gate::authorize("upsert-problem");

        $form = $request->validated();
        $this->processProblemRequest($form, new Problem());
        return to_route('problems.index');
    }

    private function processProblemRequest(array $form, Problem $problem)
    {
        $problem->name = $form['name'];
        $problem->difficulty_id = $form['difficulty'];
        $problem->description = $form['description'];

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

        $problem->scaffholdings()->delete();
        $scaffModels = [];
        foreach ($form["scaffholdings"] as $languageId => $scaff) {
            $scaffModel = new Scaffholding();
            $scaffModel->language_id = $languageId;
            if ($scaff)
                $scaffModel->scaffholding = $scaff;
            array_push($scaffModels, $scaffModel);
        }
        $problem->scaffholdings()->saveMany($scaffModels);

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

    public function runTrivial(Request $request)
    {
        $data["submissions"] = $request->json("submissions");
        $postUrl = "http://" . env("JUDGE0_DOMAIN") . "/submissions/batch?base64_encoded=true";

        $resBody = [];
        $response = Http::post($postUrl, $data);

        $tokens = array_map(fn($token) => $token["token"], $response->json());
        $getUrl = fn($token) => "http://" . env("JUDGE0_DOMAIN") . "/submissions/" . $token . "?";

        $testcaseOutputs = [];
        for ($i = 0; $i < count($tokens); $i++) {
            do {
                $response = Http::get($getUrl($tokens[$i]) . "fields=status_id");
            } while ($response->json("status_id") == 2 || $response->json("status_id") == 1);
            $response = Http::get($getUrl($tokens[$i]) . "base64_encoded=true");

            if ($response->json("stderr") || $response->json("compile_output")) {
                $resBody["error"] = $response->json("stderr") ? $response->json("stderr") : $response->json("compile_output");
                break;
            } else {
                $testcaseOutputs[] = $response->json("stdout");
            }
        }
        $resBody["outputs"] = $testcaseOutputs;
        return response()->json($resBody);
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
