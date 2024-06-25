<?php

namespace App\Jobs;

use App\Models\Problem;
use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SubmitAndCheckAllTestcasesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $code;
    private $problemId;
    private $languageId;
    private $submissionId;

    /**
     * Create a new job instance.
     */
    public function __construct($code, $problemId, $languageId, $submissionId)
    {
        $this->code = $code;
        $this->problemId = $problemId;
        $this->languageId = $languageId;
        $this->submissionId = $submissionId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $testcases = $this->getAllTestcases();
        $submissionsUrl = "http://".env("JUDGE0_DOMAIN")."/submissions/batch";
        $reqData = [];
        $submissions = [];

        foreach ($testcases as $testcase) {
            $data = [
                "source_code" => $this->code,
                "language_id" => $this->languageId,
                "stdin" => $testcase["testcase"],
            ];
            $submissions[] = $data;
        }
        $reqData["submissions"] = $submissions;

        $res = Http::post($submissionsUrl, $reqData);

        $tokens = array_map(function ($token) {
            return $token["token"];
        }, $res->collect()->toArray());

        $getUrl = "http://".env("JUDGE0_DOMAIN")."/submissions";
        $stderr = "";
        $errorneousTc = "";
        $status = "right";
        foreach ($tokens as $index => $token) {
            do {
                $res = Http::get($getUrl."/".$token."?fields=status_id");
            } while ($res->json()["status_id"] == 1 || $res->json()["status_id"] == 2);

            $res = Http::get($getUrl."/".$token."?fields=stdout,stderr,time,status_id&base64_encoded=true");
            $body = $res->json();

            foreach ($body as $param => $value) {
                if ($value) {
                    $binaryData = base64_decode($value);
                    $utf8String = mb_convert_encoding($binaryData, "UTF-8", "UTF-8");
                    $body[$param] = $utf8String;
                }
            }

            if ($body["stderr"]) {
                $status = "error";
                $stderr = $body["stderr"];
                $errorneousTc = $testcases[$index]["testcase"];
                break;
            }
            else if ($body["status_id"] == 5) {
                $status = "tle";
                break;
            }
            else if (trim($body["stdout"]) != trim($testcases[$index]["expected_output"])) {
                $status = "wrong";
                $errorneousTc = $testcases[$index]["testcase"];
                break;
            }
        }

        $submission = Submission::find($this->submissionId);
        if ($status == "wrong" || $status == "error") {
            $submission->error = $stderr;
            $submission->errorneous_tc = $errorneousTc;
        }
        $submission->status = $status;
        $submission->save();
    }

    private function getAllTestcases(): Array
    {
        $problem = Problem::find($this->problemId);
        return $problem->testcases()->get(["testcase", "expected_output"])->toArray();
    }
}
