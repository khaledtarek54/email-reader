<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MailService;
use App\Services\ExtractorService;
use Illuminate\Routing\Controller;

class ExtractorController extends Controller
{
    protected $extractorService;
    

    public function __construct(ExtractorService $extractorService)
    {
        $this->extractorService = $extractorService;
        
    }
    public function extractApi($id)
    {
        $response = $this->extractorService->mapExtractedValues($id);
       
        return $response;
    }
   
}
