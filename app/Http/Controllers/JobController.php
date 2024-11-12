<?php

namespace App\Http\Controllers;

use Exception;
use App\Services\JobService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\View\Components\AutoPlanForm;

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
            $data = $request;
            $response = $result;
            //return $data;
            $componentHtml = (new AutoPlanForm($response, $data))->render()->render();
            return response()->json([
                'html' => $componentHtml
            ]);
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
    public function saveAutoPlanSpecs(Request $request, $id)
    {
        try {
            $result = $this->jobService->saveAutoPlanSpecs($request->input(), $id);
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function createJob(Request $request, $id)
    {
        try {
            $result = $this->jobService->createJob($request->input(), $id);
            return response()->json($result);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
