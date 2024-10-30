<form action="" method="POST" name="job_specs_form" id="job_specs_form" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="oldSpecs" value="{{ json_encode($response) }}">
    <div class="tab-content">
        <div class="row">
            <div class="col-md-4 mb-15">
                <label class="font-bold control-label no-padding-top">
                    Plan Start
                </label>
                <input class="form-control" type="datetime-local" id="planstart" name="planstart"
                    onchange="changeAutoPlan('{{ $weekDaysJson }}', '{{ $data->end_date}}')"
                    value="{{ $data['start_date'] }}">

                <label class="font-bold control-label no-padding-top">
                    Plan Amount
                </label>
                <input class="form-control" type="text" id="planamount" name="planamount"
                    onchange="changeAutoPlan('{{ $weekDaysJson }}', '{{ $data->end_date }}')"
                    value="{{ $data['amount'] }}">
            </div>

            <div class="col-md-1 col-md-offset-1 padd-top-40">
                <label class="font-bold control-label no-padding-top">
                    Working Days
                </label>
            </div>

            <div class="col-md-6 padd-top-40">
                @foreach ($weekDays as $day)
                @php
                $dayId = $day['WeekDay']['number'];
                $dayName = substr($day['WeekDay']['day'], 0, 3);
                $checked = array_key_exists($dayId, $weekEnd) ? '' : 'checked';
                @endphp
                <div class="day-div">
                    <label class="form-check-label label-day" for="weekDay_{{ $dayId }}">
                        {{ $dayName }}
                    </label>
                    <input class="form-check-input day-checkbox" type="checkbox" id="weekDay_{{ $dayId }}"
                        name="weekDay_{{ $dayId }}" value="{{ $dayId }}" {{ $checked }}
                        onchange="changeAutoPlan('{{ $weekDaysJson }}', '{{ $data->end_date }}')">
                </div>
                @endforeach
            </div>
        </div>

        <nav>
            <div class="nav nav-tabs mt-4 mb-3" id="nav-tab" role="tablist">
                @php $first = true; @endphp
                @foreach ($tasks as $taskId => $task)
                <button class="nav-link {{ $first ? 'active' : '' }}" id="nav-home-tab" data-bs-toggle="tab"
                    data-bs-target="#task{{ $taskId }}" type="button" role="tab"
                    aria-controls="task{{ $taskId }}"
                    aria-selected="{{ $first ? 'true' : 'false' }}">
                    {{ $task['name'] }}
                </button>
                @php $first = false; @endphp
                @endforeach
            </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">
            @php $isFirst = true; @endphp
            @foreach ($tasks as $taskId => $job)
            <div class="tab-pane fade {{ $isFirst ? 'show active' : '' }}" id="task{{ $taskId }}" role="tabpanel"
                aria-labelledby="nav-home-tab" tabindex="0">
                <div class="row">
                    <div class="col-md-6 mb-15">
                        <label class="font-bold control-label no-padding-top">
                            Amount ({{ $job['unit_name'] }})
                        </label>
                        <input class="form-control" type="text" id="TaskAmount_{{ $taskId }}"
                            name="TaskAmount_{{ $taskId }}" value="{{ $job['amount'] }}">
                    </div>

                    <div class="col-md-6 mb-15">
                        <label class="form-check-label" for="TaskEstimated_{{ $taskId }}">
                            Estimated
                        </label><br>
                        <input type="hidden" name="TaskEstimated_{{ $taskId }}" value="0">
                        <input class="form-check-input" type="checkbox" id="TaskEstimated_{{ $taskId }}"
                            name="TaskEstimated_{{ $taskId }}" value="1" style="margin-bottom: 17px;">
                    </div>

                    <div class="col-md-6 mb-15">
                        <label class="font-bold control-label no-padding-top">
                            Phase Start
                        </label>
                        <input class="form-control" type="datetime-local" id="start_date_{{ $taskId }}"
                            name="start_date_{{ $taskId }}" value="{{ $job['start_date'] }}">
                    </div>

                    <div class="col-md-6 mb-15">
                        <label class="font-bold control-label no-padding-top">
                            Phase End
                        </label>
                        <input class="form-control" type="datetime-local" id="end_date_{{ $taskId }}"
                            name="end_date_{{ $taskId }}" value="{{ $job['end_date'] }}">
                    </div>

                    <div class="col-md-6 mb-15">
                        <label class="font-bold control-label no-padding-top">
                            Selection Plan
                        </label>
                        <select id="TaskPlanId_{{ $taskId }}" name="TaskPlanId_{{ $taskId }}" data-placeholder="select plan" class="form-control">
                            <option selected="selected"> </option>
                            @foreach ($job['plans']['values'] as $plankey => $planvalue)
                            <option value="{{ $plankey }}" {{ $plankey == $job['plans']['selected'] ? 'selected="selected"' : '' }}>
                                {{ $planvalue }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-15">
                        <label class="font-bold control-label no-padding-top">
                            Max Price ($)
                        </label>
                        <input class="form-control" type="text" id="TaskMaxPrice_{{ $taskId }}"
                            name="TaskMaxPrice_{{ $taskId }}">
                    </div>

                    <div class="col-md-12 mb-15">
                        <label class="font-bold control-label no-padding-top">
                            Shared Job Instructions
                        </label>
                        <textarea class="form-control" id="TaskSharedPhaseInstructions_{{ $taskId }}" rows="2"
                            name="TaskSharedPhaseInstructions_{{ $taskId }}"></textarea>
                    </div>
                </div>

                <div class="row">
                    @foreach ($job['filds'] as $spec => $specs)
                    <div class="col-md-6 mb-15 colu-item">
                        <label class="font-bold control-label no-padding-top">
                            {{ $specs['label'] }}
                        </label><br>
                        <select id="{{ $specs['name'] }}_{{ $taskId }}"
                            name="{{ $specs['name'] }}_{{ $taskId }}"
                            data-placeholder="select {{ $specs['label'] }}" class="form-control">
                            <option selected="selected"> </option>
                            @foreach ($specs['values'] as $specKey => $specValue)
                            <option value="{{ $specKey }}" {{ $specKey == $specs['selected'] ? 'selected="selected"' : '' }}>
                                {{ $specValue }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    @endforeach
                </div>
            </div>
            @php $isFirst = false; @endphp
            @endforeach
        </div>
    </div>
</form>