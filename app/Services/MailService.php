<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class MailService
{
    protected $connection;

    public function __construct()
    {
        // Using the external connection defined in your config
        $this->connection = DB::connection(env('EXTERNAL_DB_CONNECTION_Mail', 'transparentDBMail'));
    }

    public function fetchMails()
    {
        $mails = $this->connection->table('mails')->select(
                'id',
                'from',
                DB::raw('SUBSTRING(subject, 1, 50) as subject'), // Limit subject to 100 chars
                DB::raw('SUBSTRING(html_body, 1, 250) as html_body'), // Limit html_body to 100 chars
                'mail_datetime'
            )
            ->where('TP_PM_ID', 449756)
            ->paginate(5);

        return $mails;
    }
}
