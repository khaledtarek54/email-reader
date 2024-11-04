<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\MailService;
use App\Services\JobSpecsService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

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
            $result = Cache::remember("contact_accounts_{$id}", 3600, function () use ($id) {
                return $this->jobSpecsService->extractContactAndAccounts($id);
            });
            $contact = $result['contact'];
            $accounts = $result['accounts'];

            $mail = Cache::remember("mail_{$id}", 3600, function () use ($id) {
                return $this->mailService->fetchMailById($id);
            });

            $jobTypes = Cache::remember('job_types', 3600, function () {
                return $this->jobSpecsService->fetchJobTypes();
            });

            $sourceLanguages = Cache::remember('source_languages', 3600, function () {
                return $this->jobSpecsService->fetchSourceLanguages();
            });

            $targetLanguages = Cache::remember('target_languages', 3600, function () {
                return $this->jobSpecsService->fetchTargetLanguages();
            });

            $units = Cache::remember('units', 3600, function () {
                return $this->jobSpecsService->fetchUnits();
            });

            $contentTypes = Cache::remember('content_types', 3600, function () {
                return $this->jobSpecsService->fetchContentTypes();
            });

            $subjectMatters = Cache::remember('subject_matters', 3600, function () {
                return $this->jobSpecsService->fetchSubjectMatters();
            });

            $plans = Cache::remember('plans', 3600, function () {
                return $this->jobSpecsService->fetchPlans();
            });
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred.',
                'message' => $e->getMessage()
            ], 500);
        }
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
    public function fetchFiles($id)
    {
        try {
            $files = $this->jobSpecsService->fetchFiles($id);
            return response()->json($files);
        } catch (Exception $e) {
            return response()->json(['error' => 'An error occurred while fetching files.'], 500);
        }
    }
}
