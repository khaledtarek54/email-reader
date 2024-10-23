<?php

namespace App\Services;

use Exception;
use App\Services\MailService;
use Illuminate\Support\Facades\DB;



class JobService
{
    protected $connection;
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->connection = DB::connection(env('EXTERNAL_DB_CONNECTION', 'transparentDB'));
        $this->mailService = $mailService;
    }
    public function extractData($mailId)
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
}
