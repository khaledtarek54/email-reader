<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MailService;
use App\Services\ExtractorService;
use Illuminate\Routing\Controller;

class ExtractorController extends Controller
{
    protected $extractorService;
    protected $mailService;

    public function __construct(ExtractorService $extractorService,MailService $mailService)
    {
        $this->extractorService = $extractorService;
        $this->mailService = $mailService;
    }
    public function extractApi($id)
    {
        $mail = $this->mailService->fetchMailById($id);
        $response = $this->extractorService->createChecker($mail->html_body);
       
        return $response;
    }
   
}
