<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

    public function getSubmissions(User $user)
    {

    }
}
