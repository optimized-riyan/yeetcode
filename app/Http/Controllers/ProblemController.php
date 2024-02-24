<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Problem;
use Inertia\Inertia;

class ProblemController extends Controller
{
    public function show(Problem $problem)
    {
        return Inertia::render('EditorPage', [
            'problem' => $problem->load([
                'description',
                'testcases' => function ($query) {
                    $query->where('is_trivial', 1);
                },
                'tcParameters'
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
}
