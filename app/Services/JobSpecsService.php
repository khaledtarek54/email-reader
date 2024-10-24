<?php

namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\File;

class JobSpecsService
{
    protected $connection;
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        // Using the external connection defined in your config
        $this->connection = DB::connection(env('EXTERNAL_DB_CONNECTION', 'transparentDB'));
        $this->mailService = $mailService;
    }
    public function extractContactAndAccounts($mailId)
    {
        try {
            // Fetch contact by mail ID
            $contact = $this->fetchContactByMailId($mailId);
            if (!$contact) {
                throw new Exception('Contact not found for the provided mail ID.');
            }

            // Fetch accounts by contact ID
            $accounts = $this->fetchAccountsByContactId($contact->id);
            if (!$accounts || $accounts->isEmpty()) {
                throw new Exception('No accounts found for the provided contact ID.');
            }

            return [
                'success' => true,
                'contact' => $contact,
                'accounts' => $accounts
            ];
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function fetchContactByMailId($mailId)
    {
        $mail = $this->mailService->fetchMailById($mailId);

        $contact = $this->connection->table('contacts')
            ->where('id', $mail->contact_id)
            ->first();


        return $contact;
    }
    public function fetchAccountsByContactId($contactId)
    {
        $accounts = $this->connection->table('accounts_contacts')
            ->join('accounts', 'accounts_contacts.account_id', '=', 'accounts.id')
            ->where('accounts_contacts.contact_id', $contactId)
            ->select('accounts.*')
            ->get();

        return $accounts;
    }
    public function fetchJobTypes()
    {
        $jobTypes = $this->connection->table('job_types')
            ->whereIn('id', [28, 29, 30, 31, 32])
            ->get();

        return $jobTypes;
    }
    public function fetchSourceLanguages()
    {
        $source_languages = $this->connection->table('source_languages')
            ->where('record_status', "a")
            ->get();

        return $source_languages;
    }
    public function fetchTargetLanguages()
    {
        $source_languages = $this->connection->table('target_languages')
            ->where('record_status', "a")
            ->get();

        return $source_languages;
    }
    public function fetchUnits()
    {
        $units = $this->connection->table('units')
            ->where('record_status', "a")
            ->get();

        return $units;
    }
    public function fetchContentTypes()
    {
        $content_types = $this->connection->table('content_types')
            ->where('record_status', "a")
            ->get();

        return $content_types;
    }
    public function fetchSubjectMatters()
    {
        $subject_matters = $this->connection->table('subject_matters')
            ->where('record_status', "a")
            ->get();

        return $subject_matters;
    }
    public function fetchPlans()
    {
        $plans = $this->connection->table('plans')
            ->where('record_status', "a")
            ->get();

        return $plans;
    }
    public function fetchWorkflows($id)
    {
        $phase_types = $this->connection->table('phase_types')
            ->where('job_type_id', $id)
            ->where('record_status', "a")
            ->get();

        return $phase_types;
    }
    public function fetchFiles($id)
    {
        return File::where('email_id', $id)->get();
    }
}
