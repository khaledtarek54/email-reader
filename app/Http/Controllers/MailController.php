<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MailService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }
    public function index()
    {
        // Load the first 5 mails for initial page load
        $mails = $this->mailService->fetchMails();
        return view('mails', compact('mails'));
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
    public function loadMoreEmails(Request $request)
    {
        // Only for AJAX requests; loads the next set of mails
        if ($request->ajax()) {
            // Load the next set based on the pagination `page` query parameter
            $mails = $this->mailService->fetchMails();

            // Return the partial view with just the email items
            return view('partials.email_items', compact('mails'))->render();
        }

        return response()->json(['error' => 'Bad request'], 400); // Only allow AJAX
    }
    public function loadMoreTrash(Request $request)
    {
        // Only for AJAX requests; loads the next set of mails
        if ($request->ajax()) {
            // Load the next set based on the pagination `page` query parameter
            $mails = $this->mailService->fetchTrashMails();

            // Return the partial view with just the email items
            return view('partials.email_items', compact('mails'))->render();
        }

        return response()->json(['error' => 'Bad request'], 400); // Only allow AJAX
    }
    
}
