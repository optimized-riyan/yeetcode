<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use App\Jobs\SubmitAndCheckAllTestcasesJob;

class SubmissionController extends Controller
{
    public function submitCode(Request $request)
    {
        $submission = Submission::make();
        $submission->user_id = $request->input("user_id");
        $submission->status = "processing";
        $submission->problem_id = $request->input("problem_id");
        $submission->code = $request->input("code");
        $submission->language_id = $request->input("language_id");
        $submission->save();

        SubmitAndCheckAllTestcasesJob::dispatchSync($request->input("code"), $request->input("problem_id"), $request->input("language_id"), $submission->id);

        return response()->json($submission->fresh());
    }

    public function getSubmissions(string $problemId, string $userId)
    {
        return response()->json("this is the get submissions endpoint. Problem id is".$problemId." and User id is ".$userId);
    }
}
