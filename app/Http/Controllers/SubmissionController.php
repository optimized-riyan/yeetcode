<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SubmissionController extends Controller
{
    public function submitCode(Request $request)
    {
        $submission = Submission::make();
        $submission->user_id = $request->input("user_id");
        $submission->status = "processing";
        $submission->problem_id = $request->input("problem_id");
        $submission->save();

        return response()->json(["submission_id" => $submission->id]);
    }

    public function getSubmissionResult(Request $request)
    {

    }
}
