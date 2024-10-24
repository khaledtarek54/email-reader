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
    public function extractData($id)
    {
        try {
            $result = $this->jobService->extractData($id);
            return response()->json($result, 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred.',
                'message' => $e->getMessage()
            ], 500); 
        }
    }
}
