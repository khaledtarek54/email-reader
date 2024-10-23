<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;


class ExtractorService
{
    protected $apiUrl;
    protected $apiKey;
    protected $checkerId;
    protected $checkerType;

    public function __construct()
    {
        $this->apiUrl = env('API_EXTRACTOR_URL');
        $this->apiKey = env('API_EXTRACTOR_KEY');
        $this->checkerId = env('API_EXTRACTOR_ID');
        $this->checkerType = env('API_EXTRACTOR_TYPE');
    }

    public function createChecker($mailBody)
    {
        $response = Http::post($this->apiUrl, [
            'apiKey' => $this->apiKey,
            'checker_id' => $this->checkerId,
            'checker_type' => $this->checkerType,
            'extract_content' => $mailBody,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw(); 
    }
}
