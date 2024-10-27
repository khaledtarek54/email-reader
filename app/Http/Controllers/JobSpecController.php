<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\MailService;
use App\Services\JobSpecsService;
use Illuminate\Routing\Controller;

class JobSpecController extends Controller
{
    protected $jobSpecsService;
    protected $jobService;
    protected $mailService;

    public function __construct(JobSpecsService $jobSpecsService, MailService $mailService)
    {
        $this->jobSpecsService = $jobSpecsService;
        $this->mailService = $mailService;
    }
    public function jobData($id)
    {
        try {
            $result = $this->jobSpecsService->extractContactAndAccounts($id);
            $contact = $result['contact'];
            $accounts = $result['accounts'];
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred.',
                'message' => $e->getMessage()
            ], 500);
        }
        $mail = $this->mailService->fetchMailById($id);
        $jobTypes = $this->jobSpecsService->fetchJobTypes();
        $sourceLanguages = $this->jobSpecsService->fetchSourceLanguages();
        $targetLanguages = $this->jobSpecsService->fetchTargetLanguages();
        $units = $this->jobSpecsService->fetchUnits();
        $contentTypes = $this->jobSpecsService->fetchContentTypes();
        $subjectMatters = $this->jobSpecsService->fetchSubjectMatters();
        $plans = $this->jobSpecsService->fetchPlans();
        return view('jobdata', compact('mail', 'contact', 'accounts', 'jobTypes', 'sourceLanguages', 'targetLanguages', 'units', 'contentTypes', 'subjectMatters', 'plans'));
    }
    public function Workflows(Request $request)
    {
        // Fetch workflows based on the job type ID
        $jobTypeId = $request->input('job_type_id');

        // Assuming you have a method to fetch workflows by job type
        $workflows = $this->jobSpecsService->fetchWorkflows($jobTypeId);

        return response()->json($workflows);
    }
    public function fetchFiles(Request $request,$id)
    {
        try {
            $files = $this->jobSpecsService->fetchFiles($id);
            return response()->json($files);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching files.'], 500);
        }
    }
}
