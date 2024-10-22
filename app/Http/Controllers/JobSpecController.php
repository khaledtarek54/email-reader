<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\JobSpecsService;
use Illuminate\Routing\Controller;

class JobSpecController extends Controller
{
    protected $jobSpecsService;

    public function __construct(JobSpecsService $jobSpecsService)
    {
        $this->jobSpecsService = $jobSpecsService;
    }
    public function jobData($id)
    {
        $jobTypes = $this->jobSpecsService->fetchJobTypes();
        $sourceLanguages = $this->jobSpecsService->fetchSourceLanguages();
        $targetLanguages = $this->jobSpecsService->fetchTargetLanguages();
        $units = $this->jobSpecsService->fetchUnits();
        $contentTypes = $this->jobSpecsService->fetchContentTypes();
        $subjectMatters = $this->jobSpecsService->fetchSubjectMatters();
        $plans = $this->jobSpecsService->fetchPlans();
        return view('jobdata', compact('jobTypes', 'sourceLanguages', 'targetLanguages', 'units', 'contentTypes', 'subjectMatters', 'plans'));
    }
    public function Workflows(Request $request)
    {
        // Fetch workflows based on the job type ID
        $jobTypeId = $request->input('job_type_id');

        // Assuming you have a method to fetch workflows by job type
        $workflows = $this->jobSpecsService->fetchWorkflows($jobTypeId);

        return response()->json($workflows);
    }
}
