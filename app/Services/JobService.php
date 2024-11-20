<?php

namespace App\Services;

use CURLFile;
use DateTime;
use Exception;
use DateInterval;
use Carbon\Carbon;
use App\Models\File;
use App\Models\Autoplan;
use App\Services\MailService;
use App\Traits\TimezoneUpdater;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;



class JobService
{
    use TimezoneUpdater;
    protected $connection;
    protected $mailService;
    protected $apiUrlCreateJob = 'https://stg.gotransparent.com/addJobAPI/FunctionsV2/createJobsForAiEmails';
    public $autoPlanSpecs;
    protected $apiLogService;

    public function __construct(MailService $mailService, ApiLogService $apiLogService)
    {
        $this->connection = DB::connection(env('EXTERNAL_DB_CONNECTION', 'transparentDB'));
        $this->mailService = $mailService;
        $this->apiLogService = $apiLogService;
    }
    public function autoPlan($data)
    {
        $response = $this->getAutoPlanFieldsFromTransparent($data);
        return $response;
    }
    public function getAutoPlanFieldsFromTransparent($data)
    {
        // $response = '{"weekDays":[{"WeekDay":{"id":"1","day":"Sunday","number":"0"}},{"WeekDay":{"id":"2","day":"Monday","number":"1"}},{"WeekDay":{"id":"3","day":"Tuesday","number":"2"}},{"WeekDay":{"id":"4","day":"Wednesday","number":"3"}},{"WeekDay":{"id":"5","day":"Thursday","number":"4"}},{"WeekDay":{"id":"6","day":"Friday","number":"5"}},{"WeekDay":{"id":"7","day":"Saturday","number":"6"}}],"weekEnd":{"6":"6"},"tasks":{"86":{"name":"MT Post-editing (MTPE)","start_date":"2024-10-05 16:00:00","end_date":"2024-10-27 06:24:00","amount":500,"plans":{"values":{"2":"ManualSelect_ManualAssign_60","3":"Inhouse_AutoAssign_40","5":"AT_AutoAssign_30","16":"Mail to Job Plan","21":"Auto-plan","23":"Trusted_Resources_AutoAssign_30"},"selected":"NULLVAL"},"filds":{"Translation Application":{"name":"TaskTranslationApplicationId","label":"Translation Application","values":{"32":"No Tool Required","3":"SDL Trados Studio","15":"Translator Workbench","22":"Translation Workspace","7":"SDLX","33":"Transparent TMS","29":"Google Translator Toolkit (GTT)","21":"Idiom","34":"memoQ","41":"MS LEAF","35":"Smartling","27":"Across","10":"Passolo","30":"Wordfast Pro","37":"Phrase","36":"XTM","18":"Oracle HyperHub","31":"SAP Translation Tools","13":"Tr-Aid","42":"Polyglot","17":"Microsoft LocStudio","14":"TRANSIT & TermStar","28":"Adobe Acrobat","1":"CATALYST","12":"D-Localizer","39":"Transifex","43":"Oracle OTC","4":"IBM Translation Manager","5":"Trados  8","2":"Deja Vu","8":"Multilizer","19":"POEdit","9":"RC-WinTrans","38":"Wordbee","55":"Freeway","54":"GienTrans","53":"Matecat","52":"HMI-Linguist","51":"Jira","50":"Global Link","49":"ATMS","48":"TTM","47":"Gengo","46":"Lokalise","45":"Smartcat ","44":"Crowdin ","40":"Transit","56":"FB Cat tool"},"selected":null},"Source Quality":{"name":"TaskSourceQualityId","label":"Source Quality","values":{"1":"Raw","2":"Readable"},"selected":null},"Translation Process":{"name":"TaskTranslationProcessId","label":"Translation Process","values":{"1":"Translation Only","2":"Translation & Revision (TE)","3":"Translation, Revision & Proofreading (TEP)","4":"Translation & Proofreading (TP)"},"selected":null},"Target Quality":{"name":"TaskTargetQualityId","label":"Target Quality","values":{"1":"Full MTPE","2":"Light MTPE"},"selected":null}},"unit_id":"2","unit_name":"Word"}}}';
        // return $response;
        try {
            $job_specs = [
                //'user_id' => Session::get('user_id'),
                'user_id' => "445476",
                'amount' => $data['amount'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'JobAutoplanStrategyId' => $data['JobAutoplanStrategyId'],
                'job_type_id' => $data['job_type_id'],
                'unit_id' => $data['unit_id'],
                'plan_id' => $data['plan_id'],
                'phase_type_id' => $data['phase_type_id'] ?? "NULLVAL",
                //'account_id' => $data['account_id'],
                'account_id' => "22511785",
                'job_specifications[source_language_id]' => $data['source_language_id'],
                'job_specifications[target_language_id]' => $data['target_language_id'],
                'job_specifications[subject_matter_id]' => $data['subject_matter_id'],
                'job_specifications[content_type_id]' => $data['content_type_id'],

            ];
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode(env('TRANSPARENT_API_USERNAME') . ':' . env('TRANSPARENT_API_PASSWORD')),
                'Content-Type' => 'application/json',
            ])->post('https://stg.gotransparent.com/transparent/FunctionsV2/bulidAutoPlanScrren', $job_specs);
            if ($response->successful()) {
                return $response->json();
            } else {
                throw new Exception('Error fetching auto plan fields: ' . $response);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        } finally {
            $this->apiLogService->log(
                'https://stg.gotransparent.com/transparent/FunctionsV2/bulidAutoPlanScrren',
                'post',
                [$job_specs],
                $response->json(),
                $response->status()
            );
        }
    }
    public function generateTaskObject($tasks)
    {
        $taskObject = [];

        foreach ($tasks['tasks'] as $taskId => $task) {
            /////////change date to GMT
            $formattedStartDate = isset($task['start_date']) ? $this->updateTimezone($task['start_date'], Session::get('time_zone')) : '';
            $formattedEndDate = isset($task['end_date']) ? $this->updateTimezone($task['end_date'], Session::get('time_zone')) : '';
            $selected_plan = $task['plans']['selected'];
            $taskPlanId = $selected_plan ? $selected_plan : "NULLVAL";
            $specification = [
                'TaskAmount' => $task['amount'] ? (string) $task['amount'] : '',
                'TaskMaxPrice' => $task['TaskMaxPrice'] ? $task['TaskMaxPrice'] : '',
                'TaskEstimated' => $task['TaskEstimated'] ? $task['TaskEstimated'] : '0',
                'TaskSharedPhaseInstructions' => $task['TaskSharedPhaseInstructions'] ? $task['TaskSharedPhaseInstructions'] : '',
                'TaskOtherTool' => $task['TaskOtherTool'] ? $task['TaskOtherTool'] : '',
                'TaskPlanId' => $taskPlanId,
            ];

            foreach ($task['filds'] as $field) {
                $specification[$field['name']] = $field['selected'] ? $field['selected'] : '';
            }

            $taskObject[$taskId] = [
                'start_date' => $formattedStartDate,
                'end_date' => $formattedEndDate,
                'sepecification' => $specification
            ];
        }

        return $taskObject;
    }
    public function getUpdatedJobAutoPlanFromTransparent($data)
    {
        $weekend_days_array = explode(",", $data['weekend_days']);
        $index = 0;
        foreach ($weekend_days_array as $week_days) {
            $job_data["weekend[$index]"] = $week_days;
            $index++;
        }
        $job_data['user_id'] = session::get('user_id');
        $job_data['amount'] = $data['plan_amount'];
        $job_data['start_date'] = $data['plan_start'];
        $job_data['end_date'] = $data['plan_end'];
        $job_data['JobAutoplanStrategyId'] = $data['autoplan_id'];
        $job_data['job_type_id'] = $data['job_type_id'];
        $job_data['unit_id'] = $data['unit_id'];
        $job_data['plan_id'] =  $data['rs_plan_id'] ? $data['rs_plan_id'] : "NULLVAL";
        $job_data['phase_type_id'] = $data['workflow_id'] ? $data['workflow_id'] : "NULLVAL";
        $job_data['account_id'] = $data['account_id'];


        //return $job_data;
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode(env('TRANSPARENT_API_USERNAME') . ':' . env('TRANSPARENT_API_PASSWORD')),
                'Content-Type' => 'application/json',
            ])->post('https://stg.gotransparent.com/transparent/FunctionsV2/OrRePlanJobTasks', $job_data);
            if ($response->successful()) {
                return $response->json();
            } else {
                throw new Exception('Error fetching auto plan fields: ' . $response);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        } finally {
            $this->apiLogService->log(
                'https://stg.gotransparent.com/transparent/FunctionsV2/OrRePlanJobTasks',
                'post',
                [$job_data],
                $response->json(),
                $response->status()
            );
        }
    }
    public function updateJobSpecs($data, $mailId)
    {
        $jsonArray = json_decode($data['oldSpecs'], true);
        // Ensure tasks contain default values for specified keys
        if (isset($jsonArray['tasks'])) {
            foreach ($jsonArray['tasks'] as &$task) {
                $task = array_merge([
                    'TaskEstimated' => 0, // Default unchecked
                    'TaskMaxPrice' => 0, // Default max price
                    'TaskOtherTool' => '', // Default other tool
                    'TaskSharedPhaseInstructions' => '' // Default shared phase instructions
                ], $task);
            }
        }

        // Process each field in $data
        foreach ($data as $key => $value) {
            // Check if key ends with an underscore followed by digits
            $lastUnderscorePos = strrpos($key, '_');
            $lastDashPos = strrpos($key, '-');
            $separatorPos = max($lastUnderscorePos, $lastDashPos);
            if ($separatorPos !== false) {
                // Extract task ID assuming it's the portion after the last separator
                $taskId = substr($key, $separatorPos + 1);

                // Validate if taskId is numeric
                if (is_numeric($taskId)) {
                    $fieldName = substr($key, 0, $separatorPos); // Get field name part

                    // Check if the task exists and update the corresponding fields
                    if (isset($jsonArray['tasks'][$taskId])) {
                        $task = &$jsonArray['tasks'][$taskId];

                        // Update task fields based on the extracted fieldName
                        switch ($fieldName) {
                            case 'start_date':
                                $task['start_date'] = $value;
                                break;
                            case 'end_date':
                                $task['end_date'] = $value;
                                break;
                            case 'TaskAmount':
                                $task['amount'] = $value;
                                break;
                            case 'TaskPlanId':
                                $task['plans']['selected'] = $value;
                                break;
                            case 'TaskEstimated':
                                $task['TaskEstimated'] = $value;
                                break;
                            case 'TaskMaxPrice':
                                $task['TaskMaxPrice'] = $value;
                                break;
                            case 'TaskSharedPhaseInstructions':
                                $task['TaskSharedPhaseInstructions'] = $value;
                                break;
                            default:
                                // Handle dynamic fields within 'filds'
                                foreach ($task['filds'] as &$field) {
                                    if ($field['name'] === $fieldName) {
                                        $field['selected'] = $value;
                                        break;
                                    }
                                }
                                break;
                        }
                    }
                }
            }
        }

        // Convert to JSON and update or create Autoplan record
        $transparent_specs_json = json_encode($this->generateTaskObject($jsonArray), true);
        $autoplan = Autoplan::firstOrNew(['mail_id' => $mailId]);
        $autoplan->specs = $transparent_specs_json;
        $autoplan->save();

        return $jsonArray;
    }

    public function createJob($data, $id)
    {
        //return $data;
        $mailAutoPlan = $this->mailService->fetchAutoPlanById($id);
        $userId = session::get('user_id');
        $jobData = $this->getJobData($data);
        if ($mailAutoPlan) {
            $task_estimation = json_decode($mailAutoPlan->specs, true);
        } else {
            $task_estimation = null;
        }

        ////////get file folders
        $inFolderFiles = explode(',', $data['inFolderFiles'] ?? '');
        $instructionsFolderFiles = explode(',', $data['instructionsFolderFiles'] ?? '');
        $referenceFolderFiles = explode(',', $data['referenceFolderFiles'] ?? '');
        $filesArray = [];
        $foldersArray = [];
        $this->addFilesToRequest($inFolderFiles, 2, $filesArray, $foldersArray);
        $this->addFilesToRequest($instructionsFolderFiles, 20, $filesArray, $foldersArray);
        $this->addFilesToRequest($referenceFolderFiles, 21, $filesArray, $foldersArray);
        ////////////////////

        $apiPayload = [
            'user_id' => "445476",
            //'user_id' => $userId,
            'draft_id' => $id,
            'jobData' => json_encode($jobData),
            'tasks_estimation' => json_encode($task_estimation),
        ];

        //return $apiPayload;

        $ch = curl_init($this->apiUrlCreateJob);
        curl_setopt($ch, CURLOPT_USERPWD, env('TRANSPARENT_API_USERNAME') . ':' . env('TRANSPARENT_API_PASSWORD'));
        $curlFiles = [];
        $curlFolders = [];
        // Attach files to the request
        foreach ($filesArray as $index => $filePath) {
            $fullPath = storage_path("app/{$filePath}");

            if (file_exists($fullPath)) {
                $curlFiles["files[$index]"] = new CURLFile($fullPath);
            } else {
                return response()->json(['error' => 'File not found.', 'path' => $fullPath], 404);
            }
            $curlFolders["folder[$index]"] = $foldersArray[$index];
        }

        $postFields = array_merge($apiPayload, $curlFiles, $curlFolders);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Cache-Control: no-cache, no-store, must-revalidate',
            'Pragma: no-cache',
            'Expires: 0'
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            return response()->json(['error' => 'An error occurred.', 'message' => $error_msg], 500);
        }
        curl_close($ch);
        $decodedResponse = json_decode($response, true);
        //return $postFields;
        $this->apiLogService->log(
            $this->apiUrlCreateJob,
            'post',
            [$postFields],
            $response,
            200
        );

        if (isset($decodedResponse['job_id']) && $decodedResponse['status'] == 200) {
            $jobId = $decodedResponse['job_id'];
            $this->mailService->updateMailWithJobId($jobId, $id);
        }
        return $decodedResponse;
    }
    private function getJobData($inputData)
    {
        $mappedData = [
            //'JobAccountId' => $inputData['account'] ?? null,
            'JobAccountId' => "22511785",
            'JobContactId' => $inputData['contact_id'] ?? null,
            //'JobContactId' => "1472957",
            'JobJobTypeId' => $inputData['Job_Type'] ?? null, // Adjust mapping if needed
            'JobName' => $inputData['job_name'] ?? null,
            'JobAmount' => $inputData['amount'] ?? null,
            'JobUnitId' => $inputData['unit'] ?? null,
            'JobStartDate' =>  $inputData['startDate'] ? $this->updateTimezone(Carbon::parse($inputData['startDate'])->format('Y-m-d H:i:s'),Session::get('time_zone')) : null,
            'JobDeliveryDate' => $inputData['deliveryDate'] ? $this->updateTimezone(Carbon::parse($inputData['deliveryDate'])->format('Y-m-d H:i:s'),Session::get('time_zone')) : null,
            'JobPhaseTypeId' => $inputData['workflow'] ?? null, // Adjust mapping if needed
            'JobAutoPlan' => "1", // Assuming it's the same
            'JobAutoplanStrategyId' => $inputData['autoPlanStrategy'] ?? null,
            'JobAutoassignment' => "1", // Adjust based on your logic
            'JobSelectionPlanId' => $inputData['selectionPlan'] ?? null,
            'JobSourceLanguageId' => $inputData['sourceLanguage'] ?? null,
            'JobTargetLanguageId' => $inputData['targetLanguage'] ?? null,
            'JobSubjectMatterId' => $inputData['subjectMatter'] ?? null,
            'JobContentTypeId' => $inputData['contentType'] ?? null,
        ];

        return $mappedData;
    }
    private function addFilesToRequest($fileNames, $folderId, &$filesArray, &$foldersArray)
    {

        foreach ($fileNames as $fileName) {
            $fileName = trim($fileName);
            $queryResult = File::where('file_name', $fileName)->get(); // Adjust the model and column name

            foreach ($queryResult as $file) {
                $filesArray[] = $file->file_path; // Open the file for reading
                $foldersArray[] = $folderId;
            }
        }
    }
}
