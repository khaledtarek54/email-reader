<?php

// app/Services/ApiLogService.php
namespace App\Services;

use App\Models\ApiLog;

class ApiLogService
{
    protected $enableDatabaseLogging;

    public function __construct()
    {
        $this->enableDatabaseLogging = config('api_logging.enable_database_logging');
    }

    public function log(string $endpoint, string $method, $requestPayload, $responsePayload, int $statusCode)
    {
        if (!$this->enableDatabaseLogging) {
            return;
        }
        ApiLog::create([
            'endpoint' => $endpoint,
            'method' => $method,
            'request_payload' => json_encode($requestPayload),
            'response_payload' => json_encode($responsePayload),
            'status_code' => $statusCode,
        ]);
    }
}
