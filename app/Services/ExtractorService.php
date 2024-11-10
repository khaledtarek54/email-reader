<?php

namespace App\Services;

use DateTime;
use Exception;
use App\Models\Job;
use DateTimeImmutable;
use App\Models\Savedjob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;


class ExtractorService
{
    private const NOT_FOUND = 'not found';

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
        $mail = $this->mailService->fetchMailById($mailId);
        $jsonResponse = $this->createChecker($mail);

        $jobData = $jsonResponse['data']['Requesting Job Order']['Requesting Job Order'][0];

        $mappedJobType = $this->mapJobTypes($jobData['Job Type']);
        $mappedSourceLanguage = $this->mapSourceLanguage($jobData['Source Language']);
        $mappedTargetLanguage = $this->mapTargetLanguage($jobData['target language']);
        $mappedUnit = $this->mapUnit($jobData['Unit']);
        $mappedContentType = $this->mapContentType($jobData['Content type']);
        $mappedSubjectMatter = $this->mapSubjectMatter($jobData['Subject Matter']);
        $mappedPlan = $this->mapPlan($jobData['Selection Plan']);

        $mappedAmount = $jobData['Amount'] != self::NOT_FOUND ? $jobData['Amount'] : null;
        $mappedStartDate = null;
        $mappedDeliverytime = null;
        if ($jobData['Start Date'] != self::NOT_FOUND) {
            try {
                $mappedStartDate = $this->parseDate($jobData['Start Date']);
            } catch (Exception $e) {
                throw new Exception("Date format not recognized");
            }
        }
        if ($jobData['Delivery time'] != self::NOT_FOUND) {
            try {
                $mappedDeliverytime = $this->parseDate($jobData['Delivery time']);
            } catch (Exception $e) {
                throw new Exception("Date format not recognized");
            }
        }

        $mappedSharedInstructions = $jobData['Shared Instructions'] != self::NOT_FOUND ? $jobData['Shared Instructions'] : null;
        $mappedUnitPrice = $jobData['Unit Price'] != self::NOT_FOUND ? $jobData['Unit Price'] : null;
        $mappedCurrency = $jobData['Currency'] != self::NOT_FOUND ? $jobData['Currency'] : null;
        $mappedInfolder = $jobData['In folder'][0] != self::NOT_FOUND ? $jobData['In folder'] : null;
        $mappedInstructionsfolder = $jobData['Instructions folder'][0] != self::NOT_FOUND ? $jobData['Instructions folder'] : null;
        $mappedReferencefolder = $jobData['Reference folder'][0] != self::NOT_FOUND ? $jobData['Reference folder'] : null;
        $mappedDeliveryTimeZone  = $jobData['Delivery Time Zone'] != self::NOT_FOUND ? $jobData['Delivery Time Zone'] : null;
        $mappedOnlineSourceFiles = $jobData['In folder'][0] == self::NOT_FOUND && $jobData['Instructions folder'][0] == self::NOT_FOUND && $jobData['Reference folder'][0] == self::NOT_FOUND ? true : false;

        $job = Savedjob::create([
            'mail_id' => $mailId,
            'mail_id_tp' => $mail->mail_id,
            'source_language' => $mappedSourceLanguage,
            'target_language' => $mappedTargetLanguage,
            'job_type' => $mappedJobType,
            'amount' => $mappedAmount,
            'unit' => $mappedUnit,
            'start_date' => $mappedStartDate ? $mappedStartDate : null,
            'delivery_time' => $mappedDeliverytime ? $mappedDeliverytime : null,
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
            'mail_attachment_fetched' => false
        ]);
        return $job;
    }
    function parseDate($dateString)
    {
        // Define accepted date formats
        $formats = [
            'm/d/Y h:i:s A',   // 09/07/2020 11:59:00 PM
            'Y-m-d H:i:s',     // 2020-09-07 23:59:00
            'd-m-Y H:i',       // 07-09-2020 23:59
            'Y/m/d H:i:s',     // 2020/09/07 23:59:00
            'm/d/Y',           // 11/10/2020 
        ];

        // Try parsing each format
        foreach ($formats as $format) {
            $date = DateTimeImmutable::createFromFormat($format, $dateString);
            if ($date !== false) {
                // Successfully parsed date, format it to standard database format
                return $date->format('Y-m-d H:i:s');
            }
        }

        // If no format matched, handle the error
        throw new Exception("Date format not recognized: $dateString");
    }
    public function mapJobTypes($jobtype)
    {
        if ($jobtype == self::NOT_FOUND) {
            return null;
        }
        $mappedJobType = $this->connection->table('job_types')
            ->where('name', $jobtype)
            ->where('record_status', "a")
            ->whereIn('id', [28, 29, 30, 31, 32])
            ->first();
        if (!$mappedJobType) {
            return null;
        }
        return $mappedJobType->id;
    }

    public function mapSourceLanguage($SourceLanguage)
    {
        if ($SourceLanguage == self::NOT_FOUND) {
            return null;
        }
        $SourceLanguage = $this->connection->table('source_languages')
            ->where(function ($query) use ($SourceLanguage) {
                $query->where('code2', $SourceLanguage)
                    ->orWhere('name', $SourceLanguage);
            })
            ->where('record_status', "a")
            ->first();
        if (!$SourceLanguage) {
            return null;
        }
        return $SourceLanguage->id;
    }

    public function mapTargetLanguage($TargetLanguage)
    {
        if ($TargetLanguage == self::NOT_FOUND) {
            return null;
        }
        $TargetLanguage = $this->connection->table('target_languages')
            ->where(function ($query) use ($TargetLanguage) {
                $query->where('code2', $TargetLanguage)
                    ->orWhere('name', $TargetLanguage);
            })
            ->where('record_status', "a")
            ->first();
        if (!$TargetLanguage) {
            return null;
        }
        return $TargetLanguage->id;
    }

    public function mapUnit($unit)
    {
        if ($unit == self::NOT_FOUND) {
            return null;
        }
        $unit = $this->connection->table('units')
            ->where('name', $unit)
            ->where('record_status', "a")
            ->first();
        if (!$unit) {
            return null;
        }
        return $unit->id;
    }

    public function mapContentType($ContentType)
    {
        if ($ContentType == self::NOT_FOUND) {
            return null;
        }
        $ContentType = $this->connection->table('content_types')
            ->where('name', $ContentType)
            ->where('record_status', "a")
            ->first();
        if (!$ContentType) {
            return null;
        }
        return $ContentType->id;
    }

    public function mapSubjectMatter($SubjectMatter)
    {
        if ($SubjectMatter == self::NOT_FOUND) {
            return null;
        }
        $SubjectMatter = $this->connection->table('subject_matters')
            ->where('name', $SubjectMatter)
            ->where('record_status', "a")
            ->first();
        if (!$SubjectMatter) {
            return null;
        }
        return $SubjectMatter->id;
    }

    public function mapPlan($Plan)
    {
        if ($Plan == self::NOT_FOUND) {
            return null;
        }
        $Plan = $this->connection->table('plans')
            ->where('name', $Plan)
            ->where('record_status', "a")
            ->first();
        if (!$Plan) {
            return null;
        }
        return $Plan->id;
    }

    public function createChecker($mail)
    {
        $response = Http::post($this->apiUrl, [
            'apiKey' => $this->apiKey,
            'checker_id' => $this->checkerId,
            'checker_type' => $this->checkerType,
            'extract_content' => strip_tags($mail->html_body),
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return $response;
    }
}
