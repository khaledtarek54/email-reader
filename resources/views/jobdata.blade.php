@extends('layouts.dashbordlayout')
@section('content')
<!-- Navbar (Gmail-style) -->
<nav class="navbar">
    <div class="navbar-brand">
        <i class="fas fa-bars" id="menu-toggle"></i> Job Data
    </div>
</nav>

<!-- Form Container -->
<div class="container">
    <div class="form-header">Job Data Form</div>
    <form>
        <!-- Account and Contact Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="account">Account <span class="text-danger">*</span></label>
                    <input type="text" id="account" name="account" readonly></input>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contact">Contact <span class="text-danger">*</span></label>
                    <input type="text" id="contact" name="contact" type="text" readonly></input>
                </div>
            </div>
        </div>

        <!-- Name Field -->
        <div class="form-group">
            <label for="name">Job Name <span class="text-danger">*</span></label>
            <input type="text" id="job_name" name="job_name" placeholder="Enter job name">
        </div>

        <!-- Job Type and Workflow Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="Job_Type">Job Type <span class="text-danger">*</span></label>
                    <select id="Job_Type" name="Job_Type">
                        @foreach($jobTypes as $jobType)
                        <option value="{{ $jobType->id }}">{{ $jobType->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="workflow">Initial Workflow <span class="text-danger">*</span></label>
                    <select id="workflow">
                        
                    </select>
                </div>
            </div>
        </div>

        <!-- Start Date and Delivery Date Section -->
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="startDate">Start Date <span class="text-danger">*</span></label>
                    <input type="datetime-local" id="startDate">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="deliveryDate">Delivery Date <span class="text-danger">*</span></label>
                    <input type="datetime-local" id="deliveryDate">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="deliveryDateTimezone">Delivery Date Timezone<span class="text-danger">*</span></label>
                    <input type="datetime-local" id="deliveryDateTimezone" name="deliveryDateTimezone">
                </div>
            </div>
        </div>

        <!-- Amount and Unit Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="amount">Amount</label>
                    <input type="text" id="amount" placeholder="Enter amount">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="unit">Unit <span class="text-danger">*</span></label>
                    <select id="unit">
                        @foreach($units as $unit)
                        <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Source and Target Language Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="sourceLanguage">Source Language <span class="text-danger">*</span></label>
                    <select id="sourceLanguage">
                        @foreach($sourceLanguages as $sourceLanguage)
                        <option value="{{ $sourceLanguage->id }}">{{ $sourceLanguage->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="targetLanguage">Target Language <span class="text-danger">*</span></label>
                    <select id="targetLanguage">
                        @foreach($targetLanguages as $targetLanguage)
                        <option value="{{ $targetLanguage->id }}">{{ $targetLanguage->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <!-- Subject Matter Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="subjectMatter">Subject Matter <span class="text-danger">*</span></label>
                    <select id="subjectMatter">
                        @foreach($subjectMatters as $subjectMatter)
                        <option value="{{ $subjectMatter->id }}">{{ $subjectMatter->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contentType">Content Type<span class="text-danger">*</span></label>
                    <select id="contentType">
                        @foreach($contentTypes as $contentType)
                        <option value="{{ $contentType->id }}">{{ $contentType->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-11">
                <div class="form-group">
                    <label for="autoPlanStrategy">Auto Plan Strategy <span class="text-danger">*</span></label>
                    <select id="autoPlanStrategy">
                        <option value="1">productivity </option>
                        <option value="2">ratio</option>
                    </select>
                </div>
            </div>
            <!-- Popup Trigger Button -->
            <div class="col-md-1">
                <div class="form-group" style="margin-top: 21px;">
                    <button type="button" class="btn-submit" data-bs-toggle="modal"
                        data-bs-target="#autoPlanModal">
                        Setup
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="sourceLanguage">Auto assignment <span class="text-danger">*</span></label>
                    <select id="sourceLanguage">
                        <option value="english_us">English (United States)</option>
                        <!-- More options can be added -->
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="targetLanguage">selection plan <span class="text-danger">*</span></label>
                    <select id="targetLanguage">
                        @foreach($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>


        <!-- File Uploads Section -->
        <div class="section-title">File Uploads</div>

        <div class="form-group">
            <label for="inFiles">In Folder</label>
            <input type="file" id="inFiles" class="file-upload" multiple>
        </div>
        <div class="form-group">
            <label for="instructionFiles">Instruction Folder</label>
            <input type="file" id="instructionFiles" class="file-upload" multiple>
        </div>
        <div class="form-group">
            <label for="referenceFiles">Reference Folder</label>
            <input type="file" id="referenceFiles" class="file-upload" multiple>
        </div>

        <!-- Shared Instructions Section -->
        <div class="form-group">
            <label for="sharedInstructions">Shared Instructions</label>
            <textarea id="sharedInstructions" class="rich-text-editor" placeholder="Enter any special instructions..."></textarea>
        </div>



        <!-- Submit Button -->
        <div class="form-group text-center">
            <button type="button" class="btn-submit">Submit</button>
        </div>
    </form>
</div>

<!-- Auto Plan Strategy Modal -->
<div class="modal fade" id="autoPlanModal" tabindex="-1" aria-labelledby="autoPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="autoPlanModalLabel">Auto Plan Strategy Configuration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <!-- Add your form elements for configuring the strategy -->
                <div class="form-group">
                    <label for="strategyDetails">Details</label>
                    <textarea id="strategyDetails" class="form-control" rows="4" placeholder="Enter strategy details..."></textarea>
                </div>
            </div>

            <!-- Modal Footer with Save and Back Buttons -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
                <button type="button" class="btn btn-success">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection



<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $('#Job_Type').change(function() {
            var jobTypeId = $(this).val();

            if (jobTypeId) {
                $.ajax({
                    url: '{{ route("Workflows") }}',
                    type: 'GET',
                    data: { job_type_id: jobTypeId },
                    success: function(response) {
                        $('#workflow').empty();
                        $.each(response, function(key, workflow) {
                            $('#workflow').append('<option value="' + workflow.id + '">' + workflow.name + '</option>');
                        });
                    },
                    error: function() {
                        alert('Failed to load workflows');
                    }
                });
            } else {
                $('#workflow').empty();
                $('#workflow').append('<option value="">Select Workflow</option>');
            }
        });
    });
</script>
