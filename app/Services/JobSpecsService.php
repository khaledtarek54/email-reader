<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\File;

class JobSpecsService
{
    protected $connection;

    public function __construct()
    {
        // Using the external connection defined in your config
        $this->connection = DB::connection(env('EXTERNAL_DB_CONNECTION', 'transparentDB'));
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
        $files = File::where('email_id', $id)
            ->get();

        return $files;
    }
}
