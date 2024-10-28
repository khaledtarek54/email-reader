<?php

namespace App\Services;

use DateTime;
use Exception;
use DateInterval;
use App\Services\MailService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;



class JobService
{
    protected $connection;
    protected $mailService;
    public $autoPlanSpecs;

    public function __construct(MailService $mailService)
    {
        $this->connection = DB::connection(env('EXTERNAL_DB_CONNECTION', 'transparentDB'));
        $this->mailService = $mailService;
    }
    public function autoPlan($data)
    {
        $response = $this->getAutoPlanFieldsFromTransparent($data);
        //return $response;
        ob_start();
        $this->renderAutoPlanHTML($response, $data);
        return ob_get_clean();
    }
    private function renderAutoPlanHTML($response, $data)
    {
        $data['user_id'] = session::get('user_id');
        $decoded = $response;
        $tasks = $decoded['tasks'];
        $weekDays = $decoded['weekDays'];
        $weekEnd = $decoded['weekEnd'];
        $weekDaysJson = htmlspecialchars(json_encode($weekDays));
?>
        <form action="" method="POST" name="job_specs_form" id="job_specs_form" enctype="multipart/form-data">
            <input type="hidden" name="oldSpecs" value='<?= htmlspecialchars(json_encode($decoded, true)); ?>'>
            <div class="tab-content">
                <div class="row">
                    <div class="col-md-4 mb-15">
                        <label class="font-bold control-label no-padding-top">
                            Plan Start
                        </label>
                        <input class="form-control" type="datetime-local" id="planstart" name="planstart" onchange="changeAutoPlan(<?= $weekDaysJson ?>,<?= $data['user_id'] ?>,'<?= $data['end_date'] ?>')" value="<?= htmlspecialchars($data['start_date']) ?>">

                        <label class="font-bold control-label no-padding-top">
                            Plan Amount
                        </label>
                        <input class="form-control" type="text" id="planamount" name="planamount" onchange="changeAutoPlan(<?= $weekDaysJson ?>,<?= $data['user_id'] ?>,'<?= $data['end_date'] ?>')" value="<?= $data['amount'] ?>">


                    </div>
                    <div class="col-md-1 col-md-offset-1 padd-top-40">
                        <label class="font-bold control-label no-padding-top">
                            Working Days
                        </label>
                    </div>
                    <!-- Checkboxes for Weekdays -->
                    <div class="col-md-6 padd-top-40">

                        <?php
                        foreach ($weekDays as $day):
                            $dayId = $day['WeekDay']['number'];
                            $dayName = substr(htmlspecialchars($day['WeekDay']['day']), 0, 3);
                            $checked = array_key_exists($dayId, $weekEnd) ? '' : 'checked';
                        ?>
                            <div class="day-div">
                                <label class="form-check-label  label-day" for="weekDay_<?= $dayId ?>">
                                    <?= $dayName ?>
                                </label>
                                <input class="form-check-input day-checkbox" type="checkbox" id="weekDay_<?= $dayId ?>" name="weekDay_<?= $dayId ?>" value="<?= $dayId ?>" <?= $checked ?> onchange="changeAutoPlan(<?= $weekDaysJson ?>,<?= $data['user_id'] ?>,'<?= $data['end_date'] ?>')">

                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="tabbable-spcs">
                    <ul class="nav nav-tabs tabs-cust" id="myTab">
                        <?php $first = true; ?>
                        <?php foreach ($tasks as $taskId => $task): ?>
                            <li class="tabs-li <?php echo $first ? 'active' : ''; ?>">
                                <a data-toggle="tab" href="#task<?php echo $taskId; ?>">
                                    <?php echo $task['name']; ?>
                                </a>
                            </li>
                            <?php $first = false; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="tab-content">
                    <?php $first = true; ?>
                    <?php foreach ($tasks as $taskId => $job): ?>
                        <div id="task<?php echo $taskId; ?>" class="tab-pane fade <?php echo $first ? 'in active' : ''; ?>">
                            <div class="row">
                                <div class="col-md-6 mb-15">
                                    <label class="font-bold control-label no-padding-top">
                                        Amount (<?= $job['unit_name'] ?>)
                                    </label>
                                    <input class="form-control" type="text" id="TaskAmount_<?= htmlspecialchars($taskId) ?>" name="TaskAmount_<?= htmlspecialchars($taskId) ?>" value="<?= htmlspecialchars($job['amount']) ?>">
                                </div>
                                <div class="col-md-6 mb-15">
                                    <label class="form-check-label" for="TaskEstimated_<?= $taskId ?>">
                                        Estimated
                                    </label>
                                    <br>
                                    <input type="hidden" name="TaskEstimated_<?= $taskId ?>" value="0">
                                    <input class="form-check-input" type="checkbox" id="TaskEstimated_<?= $taskId ?>" name="TaskEstimated_<?= $taskId ?>" value="1" style="margin-bottom: 17px;">

                                </div>
                                <div class="col-md-6 mb-15">
                                    <label class="font-bold control-label no-padding-top">
                                        Phase Start
                                    </label>
                                    <input class="form-control" type="datetime-local" id="start_date_<?= htmlspecialchars($taskId) ?>" name="start_date_<?= htmlspecialchars($taskId) ?>" value="<?= htmlspecialchars($job['start_date']) ?>">
                                </div>
                                <div class="col-md-6 mb-15">
                                    <label class="font-bold control-label no-padding-top">
                                        Phase End
                                    </label>
                                    <input class="form-control" type="datetime-local" id="end_date_<?= htmlspecialchars($taskId) ?>" name="end_date_<?= htmlspecialchars($taskId) ?>" value="<?= htmlspecialchars($job['end_date']) ?>">
                                </div>

                                <div class="col-md-6 mb-15">
                                    <label class="font-bold control-label no-padding-top">
                                        Selection Plan
                                    </label>
                                    <select id="TaskPlanId_<?= htmlspecialchars($taskId) ?>" name="TaskPlanId_<?= htmlspecialchars($taskId) ?>" data-placeholder="select plan" class="form-control">
                                        <option selected="selected"> </option>
                                        <?php foreach ($job['plans']['values'] as $plankey => $planvalue): ?>
                                            <option value="<?= htmlspecialchars($plankey) ?>" <?= htmlspecialchars($plankey) == htmlspecialchars($job['plans']['selected']) ? 'selected="selected"' : '' ?>><?= htmlspecialchars($planvalue) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-15">

                                    <label class="font-bold control-label no-padding-top">
                                        Max Price ($)
                                    </label>
                                    <input class="form-control" type="text" id="TaskMaxPrice_<?= htmlspecialchars($taskId) ?>" name="TaskMaxPrice_<?= htmlspecialchars($taskId) ?>">
                                </div>
                                <div class="col-md-12 mb-15">

                                    <label class="font-bold control-label no-padding-top">
                                        Shared Job Instructions
                                    </label>
                                    <textarea class="form-control" type="text" id="TaskSharedPhaseInstructions_<?= htmlspecialchars($taskId) ?>" rows="2" name="TaskSharedPhaseInstructions_<?= htmlspecialchars($taskId) ?>"></textarea>
                                </div>

                            </div>
                            <!-- row item -->
                            <div class="row">
                                <?php foreach ($job['filds'] as $spec => $specs): ?>

                                    <div class="col-md-6 mb-15 colu-item">
                                        <label class="font-bold control-label no-padding-top">
                                            <?= htmlspecialchars($specs['label']) ?>
                                        </label><br>
                                        <select id="<?= htmlspecialchars($specs['name']) ?>_<?= htmlspecialchars($taskId) ?>" name="<?= htmlspecialchars($specs['name']) ?>_<?= htmlspecialchars($taskId) ?>" data-placeholder="select <?= htmlspecialchars($specs['label']) ?>" class="form-control">
                                            <option selected="selected"> </option>
                                            <?php foreach ($specs['values'] as $specKey => $specValue): ?>
                                                <option value="<?= htmlspecialchars($specKey) ?>" <?= htmlspecialchars($specKey) == htmlspecialchars($specs['selected']) ? 'selected="selected"' : '' ?>><?= htmlspecialchars($specValue) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                <?php endforeach; ?>
                            </div>
                            <!-- END row item -->
                        </div>
                        <?php $first = false; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            </div>
        </form>
<?php
    }
    public function getAutoPlanFieldsFromTransparent($data)
    {
        // $response = '{"weekDays":[{"WeekDay":{"id":"1","day":"Sunday","number":"0"}},{"WeekDay":{"id":"2","day":"Monday","number":"1"}},{"WeekDay":{"id":"3","day":"Tuesday","number":"2"}},{"WeekDay":{"id":"4","day":"Wednesday","number":"3"}},{"WeekDay":{"id":"5","day":"Thursday","number":"4"}},{"WeekDay":{"id":"6","day":"Friday","number":"5"}},{"WeekDay":{"id":"7","day":"Saturday","number":"6"}}],"weekEnd":{"6":"6"},"tasks":{"86":{"name":"MT Post-editing (MTPE)","start_date":"2024-10-05 16:00:00","end_date":"2024-10-27 06:24:00","amount":500,"plans":{"values":{"2":"ManualSelect_ManualAssign_60","3":"Inhouse_AutoAssign_40","5":"AT_AutoAssign_30","16":"Mail to Job Plan","21":"Auto-plan","23":"Trusted_Resources_AutoAssign_30"},"selected":"NULLVAL"},"filds":{"Translation Application":{"name":"TaskTranslationApplicationId","label":"Translation Application","values":{"32":"No Tool Required","3":"SDL Trados Studio","15":"Translator Workbench","22":"Translation Workspace","7":"SDLX","33":"Transparent TMS","29":"Google Translator Toolkit (GTT)","21":"Idiom","34":"memoQ","41":"MS LEAF","35":"Smartling","27":"Across","10":"Passolo","30":"Wordfast Pro","37":"Phrase","36":"XTM","18":"Oracle HyperHub","31":"SAP Translation Tools","13":"Tr-Aid","42":"Polyglot","17":"Microsoft LocStudio","14":"TRANSIT & TermStar","28":"Adobe Acrobat","1":"CATALYST","12":"D-Localizer","39":"Transifex","43":"Oracle OTC","4":"IBM Translation Manager","5":"Trados  8","2":"Deja Vu","8":"Multilizer","19":"POEdit","9":"RC-WinTrans","38":"Wordbee","55":"Freeway","54":"GienTrans","53":"Matecat","52":"HMI-Linguist","51":"Jira","50":"Global Link","49":"ATMS","48":"TTM","47":"Gengo","46":"Lokalise","45":"Smartcat ","44":"Crowdin ","40":"Transit","56":"FB Cat tool"},"selected":null},"Source Quality":{"name":"TaskSourceQualityId","label":"Source Quality","values":{"1":"Raw","2":"Readable"},"selected":null},"Translation Process":{"name":"TaskTranslationProcessId","label":"Translation Process","values":{"1":"Translation Only","2":"Translation & Revision (TE)","3":"Translation, Revision & Proofreading (TEP)","4":"Translation & Proofreading (TP)"},"selected":null},"Target Quality":{"name":"TaskTargetQualityId","label":"Target Quality","values":{"1":"Full MTPE","2":"Light MTPE"},"selected":null}},"unit_id":"2","unit_name":"Word"}}}';
        // return $response;
        try {
            $job_specs = [
                'user_id' => Session::get('user_id'),
                'amount' => $data['amount'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'JobAutoplanStrategyId' => $data['JobAutoplanStrategyId'],
                'job_type_id' => $data['job_type_id'],
                'unit_id' => $data['unit_id'],
                'plan_id' => $data['plan_id'],
                'phase_type_id' => $data['phase_type_id'] ?? "NULLVAL",
                'account_id' => $data['account_id'],
                'job_specifications[source_language_id]' => $data['source_language_id'],
                'job_specifications[target_language_id]' => $data['target_language_id'],
                'job_specifications[subject_matter_id]' => $data['subject_matter_id'],
                'job_specifications[content_type_id]' => $data['content_type_id'],

            ];
            $response = Http::withHeaders([
                'Authorization' => 'Basic ' . base64_encode('transparent:1q2w3e'),
                'Content-Type' => 'application/json',
            ])->post('https://stg.gotransparent.com/transparent/FunctionsV2/bulidAutoPlanScrren', $job_specs);
            if ($response->successful()) {
                return $response->json();
            } else {
                throw new Exception('Error fetching auto plan fields: ' . $response);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function generateTaskObject($tasks)
    {
        $taskObject = [];

        foreach ($tasks['tasks'] as $taskId => $task) {
            /////////change date to GMT
            $formattedStartDate = isset($task['start_date']) ? $this->formatDate($task['start_date'], $_SESSION['timezone']) : '';
            $formattedEndDate = isset($task['end_date']) ? $this->formatDate($task['end_date'], $_SESSION['timezone']) : '';
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
    private function formatDate($date, $offset)
    {
        $datetime = new DateTime($date);
        $interval = new DateInterval("PT" . abs($offset) . "H");
        $datetime->sub($interval);
        return $datetime->format('Y-m-d H:i:s');
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
                'Authorization' => 'Basic ' . base64_encode('transparent:1q2w3e'),
                'Content-Type' => 'application/json',
            ])->post('https://stg.gotransparent.com/transparent/FunctionsV2/OrRePlanJobTasks', $job_data);
            if ($response->successful()) {
                return $response->json();
            } else {
                throw new Exception('Error fetching auto plan fields: ' . $response);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    public function saveAutoPlanSpecs($data)
    {

        $response =  $this->updateJobSpecs($data);
        return $response;
    }
    public function updateJobSpecs($data)
    {

        $jsonArray = json_decode($data['oldSpecs'], true);
        if (isset($jsonArray['tasks'])) {
            foreach ($jsonArray['tasks'] as &$task) {
                // Add the new keys with default values if they don't exist
                if (!isset($task['TaskEstimated'])) {
                    $task['TaskEstimated'] = 0; // Default value for unchecked
                }
                if (!isset($task['TaskMaxPrice'])) {
                    $task['TaskMaxPrice'] = 0; // Default value for max price
                }
                if (!isset($task['TaskSharedPhaseInstructions'])) {
                    $task['TaskSharedPhaseInstructions'] = ''; // Default value for shared phase instructions
                }
            }
        }
        foreach ($data as $key => $value) {
            // Skip "planstart", "planamount", and "weekDay"
            if (strpos($key, 'planstart') !== false || strpos($key, 'planamount') !== false || strpos($key, 'weekDay') !== false || strpos($key, 'unit_id') !== false || strpos($key, 'unit_name') !== false) {
                continue;
            }

            // Extract the task ID from the key
            if (preg_match('/_(\d+)$/', $key, $matches)) {
                $taskId = $matches[1]; // Extracted task ID from the key
                $fieldName = str_replace('_' . $taskId, '', $key);

                // Check if the task with the extracted task ID exists
                if (isset($jsonArray['tasks'][$taskId])) {
                    // Reference the specific task to be updated
                    $task = &$jsonArray['tasks'][$taskId];

                    // Update the task fields dynamically
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
                            // Update any other dynamic fields within 'filds'
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

        //$transparent_specs = $this->generateTaskObject($jsonArray);
        // $transparent_specs_json = VerifyInput(json_encode($transparent_specs, true));
        // $specs_json = VerifyInput(json_encode($jsonArray, true));
        // mysql_command("INSERT INTO job_auto_plan_spec_details (job_id,transparent_specs,unsaved_specs,created_at) VALUES ('" . $data['job_id'] . "','" . $transparent_specs_json . "','" . $specs_json . "','" . gmdate("Y-m-d H:i:s") . "') ON DUPLICATE KEY UPDATE transparent_specs='" . $transparent_specs_json . "',unsaved_specs='" . $specs_json . "', updated_at='" . gmdate("Y-m-d H:i:s") . "' ");


        return $jsonArray;
    }
}
