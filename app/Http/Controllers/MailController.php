<?php

namespace App\Http\Controllers;

use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MailController extends Controller
{
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }
    public function showAllMails()
    {
        $mails = $this->mailService->fetchMails();
        return view('mails', compact('mails'));
    }

       public function showMail($id)
    {
        $mail = $this->mailService->fetchMailById($id);
        return view('mailview', compact('mail'));
    }
    

}
