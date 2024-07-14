<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Problem;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\SubmitAndCheckAllTestcasesJob;

class SubmissionController extends Controller
{
    public function submitCode(Request $request)
    {
        $userId = $request->input("user_id");
        $problemId = $request->input("problem_id");

        $submission = Submission::make();
        $submission->user_id = $request->input("user_id");
        $submission->status = "processing";
        $submission->problem_id = $request->input("problem_id");
        $submission->code = $request->input("code");
        $submission->language_id = $request->input("language_id");
        $submission->save();

        $user = User::find($userId);
        $problem = Problem::find($problemId);
        if (!($user->exploredProblems()->where("id", $problemId)->exists())) {
            $user->exploredProblems()->attach($problem, [ "status" => "attempted" ]);
        }

        SubmitAndCheckAllTestcasesJob::dispatchSync($request->input("code"), $request->input("problem_id"), $request->input("language_id"), $submission->id);

        $submission->refresh();
        if ($submission->status == "right") {
            $currentProblem = ($user->exploredProblems()->where("problem_id", "=", $problemId)->get())[0];
            $currentProblem->pivot->status = "solved";
            $currentProblem->pivot->save();
        }

        return response()->json($submission);
    }

    public function getSubmissions(string $problemId, string $userId)
    {
        $submissions = User::find($userId)->submissions()->where('problem_id', $problemId)->orderByDesc("created_at")->get();
        return response()->json($submissions);
    }
}
