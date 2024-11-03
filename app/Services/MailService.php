<?php

namespace App\Services;

use App\Models\User;
use App\Models\Autoplan;
use App\Models\Savedjob;
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
            ->where('trash', 0)
            ->orderBy('creation_time', 'desc')
            ->paginate(5);

        return $mails;
    }
    public function fetchTrashMails()
    {
        $mails = $this->connection->table('mails')->select(
            'id',
            'from',
            DB::raw('SUBSTRING(subject, 1, 50) as subject'), // Limit subject to 100 chars
            DB::raw('SUBSTRING(html_body, 1, 250) as html_body'), // Limit html_body to 100 chars
            'mail_datetime'
        )
            ->where('TP_PM_ID', 449756)
            ->where('trash', 1)
            ->orderBy('creation_time', 'desc')
            ->paginate(5);

        return $mails;
    }
    public function fetchMailById($id)
    {
        $mail = $this->connection->table('mails')->select(
            'id',
            'mail_id',
            'from',
            'subject',
            'html_body',
            'contact_id',
            'mail_datetime',
            'trash'
        )
            ->where('id', $id)
            ->first();

        return $mail;
    }
    public function trashMail($id)
    {
        $result = $this->connection->table('mails')
            ->where('id', $id)
            ->update([
                'trash' => 1,
                'trashed_at' => now()
            ]);

        return $result;
    }
    public function recoverMail($id)
    {
        $result = $this->connection->table('mails')
            ->where('id', $id)
            ->update([
                'trash' => 0,
                'trashed_at' => null
            ]);

        return $result;
    }
    public function fetchAutoPlanById($id)
    {
        $autoPlan = Autoplan::where('mail_id', $id)->first();

        return $autoPlan;
    }
    public function fetchAttachmentStatus($id)
    {
        $mailid = Savedjob::select('mail_id')
            ->where('mail_id_tp', $id)
            ->where('mail_attachment_fetched', 0)
            ->first();

        return $mailid;
    }
    public function updateAttachmentStatus($id)
    {

        // Attempt to update the `mail_attachment_fetched` status
        Savedjob::where('mail_id', $id)
            ->update(['mail_attachment_fetched' => 1]);
    }
}
