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
    public function showAllTrashedMails()
    {
        $mails = $this->mailService->fetchTrashMails();
        return view('trash', compact('mails'));
    }

    public function showMail($id)
    {
        $mail = $this->mailService->fetchMailById($id);
        return view('mailview', compact('mail'));
    }
    public function refreshMails()
    {
        $mails = $this->mailService->fetchMails();
        return view('mails', compact('mails'));
    }
    public function trashMail($id)
    {
        $result = $this->mailService->trashMail($id);
        if ($result) {
            return redirect('/mails')->with('success', 'Mail successfully trashed.');
        } else {
            return redirect()->back()->with('error', 'Failed to move mail to trash.');
        }
    }
    public function recoverMail($id)
    {
        $result = $this->mailService->recoverMail($id);
        if ($result) {
            return redirect('/mails')->with('success', 'Mail successfully recovered.');
        } else {
            return redirect()->back()->with('error', 'Failed to recover mail.');
        }
    }
    
}
