<?php

namespace App\Services;

use Exception;
use App\Services\MailService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;



class JobService
{
    protected $connection;
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->connection = DB::connection(env('EXTERNAL_DB_CONNECTION', 'transparentDB'));
        $this->mailService = $mailService;
    }
    public function autoPlan($data)
    {
        try {


            $job_specs = [
                //'user_id' => $data['user_id'],
                'amount' => $data['amount'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'JobAutoplanStrategyId' => $data['JobAutoplanStrategyId'],
                'job_type_id' => $data['job_type_id'],
                'unit_id' => $data['unit_id'],
                'plan_id' => $data['plan_id'],
                'phase_type_id' => $data['phase_type_id'] ?? "NULLVAL",
                'account_id' => $data['account_id'],
                'job_specifications[source_language_id]' => $data['source_language_id'],
                'job_specifications[target_language_id]' => $data['target_language_id'],
                'job_specifications[subject_matter_id]' => $data['subject_matter_id'],
                'job_specifications[content_type_id]' => $data['content_type_id'],

            ];

            // Send the POST request using Laravel's HTTP client
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode('transparent:1q2w3e'), // Replace with your credentials
                'Content-Type' => 'application/json',
            ])->post('https://stg.gotransparent.com/AutoplanScreen/Functions/bulidAutoPlanScrren', $job_specs);

            // Check if the response was successful
            if ($response->successful()) {
                // Handle the response data
                return $response->json();
            } else {    
                // Handle the error
                throw new \Exception('Error sending job data: ' . $response);
            }
            if (true) {
                throw new Exception('No accounts found for the provided contact ID.');
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
