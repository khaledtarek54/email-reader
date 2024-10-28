<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\JobService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class JobController extends Controller
{
    protected $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }
    public function autoPlan(Request $request)
    {
        try {
            $result = $this->jobService->autoPlan($request);
            return response()->json(['html' => $result]);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function autoPlanEdit(Request $request)
    {
        try {
            $result = $this->jobService->getUpdatedJobAutoPlanFromTransparent($request);
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function saveAutoPlanSpecs(Request $request)
    {
        try {
            $result = $this->jobService->saveAutoPlanSpecs($request);
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
