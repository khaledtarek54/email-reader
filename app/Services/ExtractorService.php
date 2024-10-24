<?php

namespace App\Services;

use DateTime;
use App\Models\Job;
use App\Models\Savedjob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class ExtractorService
{
    protected $connection;
    protected $apiUrl;
    protected $apiKey;
    protected $checkerId;
    protected $checkerType;
    protected $mailService;
    protected $jobSpecsService;

    public function __construct(MailService $mailService, JobSpecsService $jobSpecsService)
    {
        $this->connection = DB::connection(env('EXTERNAL_DB_CONNECTION', 'transparentDB'));
        $this->apiUrl = env('API_EXTRACTOR_URL');
        $this->apiKey = env('API_EXTRACTOR_KEY');
        $this->checkerId = env('API_EXTRACTOR_ID');
        $this->checkerType = env('API_EXTRACTOR_TYPE');
        $this->mailService = $mailService;
        $this->jobSpecsService = $jobSpecsService;
    }
    public function mapExtractedValues($mailId)
    {

        $existingJob = Savedjob::where('mail_id', $mailId)->first();
        if ($existingJob) {
            return $existingJob;
        }
        // $mail = $this->mailService->fetchMailById($mailId);
        // $jsonResponse = $this->createChecker($mail);
        $jsonResponse = '{"data":{"Requesting Job Order":{"Requesting Job Order":[{"Source Language":"English (United States)","target language":"Persian (Iran)","Job Type":"not found","Amount":"not found","Unit":"not found","Start Date":"not found","Delivery time":"Wed 25th Sept 2024","Shared Instructions":"not found","Unit Price":"not found","Currency":"not found","In folder":"not found","Instructions folder":"not found","Reference folder":"not found","Content type":"not found","Subject Matter":"not found","Auto Plan Strategy":"not found","Auto Assignment":"not found","Selection Plan":"not found","Delivery Time Zone ":"America\/Santiago"}]}}}';
        $decodedResponse = json_decode($jsonResponse, true);
        $jobData = $decodedResponse['data']['Requesting Job Order']['Requesting Job Order'][0];

        $mappedJobType = $this->mapJobTypes($jobData['Job Type']);
        $mappedSourceLanguage = $this->mapSourceLanguage($jobData['Source Language']);
        $mappedTargetLanguage = $this->mapTargetLanguage($jobData['target language']);
        $mappedUnit = $this->mapUnit($jobData['Unit']);
        $mappedContentType = $this->mapContentType($jobData['Content type']);
        $mappedSubjectMatter = $this->mapSubjectMatter($jobData['Subject Matter']);
        $mappedPlan = $this->mapPlan($jobData['Selection Plan']);

        $mappedAmount = $jobData['Amount'] != "not found" ? $jobData['Amount'] : null;
        $mappedUnit = $jobData['Unit'] != "not found" ? $jobData['Unit'] : null;


        $dateTime = DateTime::createFromFormat('D dS M Y', $jobData['Start Date']);
        $mappedStartDate = $jobData['Start Date'] != "not found" ? $dateTime->format('Y-m-d H:i:s') : null;
        $dateTime = DateTime::createFromFormat('D dS M Y', $jobData['Delivery time']);
        $mappedDeliverytime = $jobData['Delivery time'] != "not found" ? $dateTime->format('Y-m-d H:i:s') : null;


        $mappedSharedInstructions = $jobData['Shared Instructions'] != "not found" ? $jobData['Shared Instructions'] : null;
        $mappedUnitPrice = $jobData['Unit Price'] != "not found" ? $jobData['Unit Price'] : null;
        $mappedCurrency = $jobData['Currency'] != "not found" ? $jobData['Currency'] : null;
        $mappedInfolder = $jobData['In folder'] != "not found" ? $jobData['In folder'] : null;
        $mappedInstructionsfolder = $jobData['Instructions folder'] != "not found" ? $jobData['Instructions folder'] : null;
        $mappedReferencefolder = $jobData['Reference folder'] != "not found" ? $jobData['Reference folder'] : null;
        $mappedDeliveryTimeZone  = $jobData['Delivery Time Zone '] != "not found" ? $jobData['Delivery Time Zone '] : null;
        $mappedOnlineSourceFiles = $jobData['In folder'] == "not found" && $jobData['Instructions folder'] == "not found" && $jobData['Reference folder'] == "not found" ? true : false;

        $job = Savedjob::create([
            'mail_id' => $mailId,
            'source_language' => $mappedSourceLanguage,
            'target_language' => $mappedTargetLanguage,
            'job_type' => $mappedJobType,
            'amount' => $mappedAmount,
            'unit' => $mappedUnit,
            'start_date' => $mappedStartDate,
            'delivery_time' => $mappedDeliverytime,
            'delivery_timezone' => $mappedDeliveryTimeZone,
            'shared_instructions' => $mappedSharedInstructions,
            'unit_price' => $mappedUnitPrice,
            'currency' => $mappedCurrency,
            'in_folder' => $mappedInfolder ? json_encode($mappedInfolder) : null,
            'instructions_folder' => $mappedInstructionsfolder ? json_encode($mappedInstructionsfolder) : null,
            'reference_folder' => $mappedReferencefolder ? json_encode($mappedReferencefolder) : null,
            'online_source_files' => $mappedOnlineSourceFiles,
            'content_type' => $mappedContentType,
            'subject_matter' => $mappedSubjectMatter,
            'auto_plan_strategy' =>  null,
            'auto_assignment' =>  null,
            'selection_plan' => $mappedPlan,
        ]);
        return $job;
    }
    public function mapJobTypes($jobtype)
    {
        if ($jobtype == "not found") {
            return null;
        }
        $mappedJobType = $this->connection->table('job_types')
            ->where('name', $jobtype)
            ->where('record_status', "a")
            ->first();
        return $mappedJobType->id;
    }
    public function mapSourceLanguage($SourceLanguage)
    {
        if ($SourceLanguage == "not found") {
            return null;
        }
        $SourceLanguage = $this->connection->table('source_languages')
            ->where('name', $SourceLanguage)
            ->where('record_status', "a")
            ->first();
        return $SourceLanguage->id;
    }
    public function mapTargetLanguage($TargetLanguage)
    {
        if ($TargetLanguage == "not found") {
            return null;
        }
        $TargetLanguage = $this->connection->table('target_languages')
            ->where('name', $TargetLanguage)
            ->where('record_status', "a")
            ->first();
        return $TargetLanguage->id;
    }
    public function mapUnit($unit)
    {
        if ($unit == "not found") {
            return null;
        }
        $unit = $this->connection->table('units')
            ->where('name', $unit)
            ->where('record_status', "a")
            ->first();
        return $unit->id;
    }
    public function mapContentType($ContentType)
    {
        if ($ContentType == "not found") {
            return null;
        }
        $ContentType = $this->connection->table('content_types')
            ->where('name', $ContentType)
            ->where('record_status', "a")
            ->first();
        return $ContentType->id;
    }
    public function mapSubjectMatter($SubjectMatter)
    {
        if ($SubjectMatter == "not found") {
            return null;
        }
        $SubjectMatter = $this->connection->table('subject_matters')
            ->where('name', $SubjectMatter)
            ->where('record_status', "a")
            ->first();
        return $SubjectMatter->id;
    }
    public function mapPlan($Plan)
    {
        if ($Plan == "not found") {
            return null;
        }
        $Plan = $this->connection->table('plans')
            ->where('name', $Plan)
            ->where('record_status', "a")
            ->first();
        return $Plan->id;
    }
    public function createChecker($mail)
    {
        $response = Http::post($this->apiUrl, [
            'apiKey' => $this->apiKey,
            'checker_id' => $this->checkerId,
            'checker_type' => $this->checkerType,
            'extract_content' => $mail->html_body,
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return $response->throw();
    }
}
