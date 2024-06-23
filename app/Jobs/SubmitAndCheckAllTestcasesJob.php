<?php

namespace App\Jobs;

use App\Models\Problem;
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

    /**
     * Create a new job instance.
     */
    public function __construct($code, $problemId, $languageId)
    {
        $this->code = $code;
        $this->problemId = $problemId;
        $this->languageId = $languageId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $testcases = $this->getAllTestcases();
        Log::channel("debug")->info(print_r($testcases));
        $submissionsUrl = "http://".env("JUDGE0_DOMAIN")."/submissions/batch";
        $reqData = [];

        foreach ($testcases as $testcase) {
            $reqData["source_code"] = $this->code;
            $reqData["language_id"] = $this->languageId;
            $reqData["stdin"] = $testcase;
        }

        $res = Http::post($submissionsUrl, $reqData);
        Log::channel("debug")->info($res);
    }

    private function getAllTestcases(): Array
    {
        $problem = Problem::find($this->problemId);
        return $problem->testcases()->get(['testcase'])->toArray();
    }
}
