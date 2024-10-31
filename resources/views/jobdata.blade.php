@extends('layouts.dashbordlayout')
@section('content')
<!-- Navbar (Gmail-style) -->
<nav class="navbar">
    <div class="navbar-brand">
        <i class="fas fa-bars" id="menu-toggle"></i> Job Data
    </div>
</nav>
<div id="loadingOverlay" style="display:none;">
    <div id="loadingSpinner"></div>
</div>
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Form Container -->
<div class="container">
    <div class="form-header">Job Data Form</div>
    <form>
        <!-- Account and Contact Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="account">Account <span class="text-danger">*</span></label>
                    <select id="account" name="account">
                        @foreach ($accounts as $account)
                        <option value="{{ $account->id }}">{{ $account->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contact">Contact <span class="text-danger">*</span></label>
                    <input hidden id="contact_id" name="contact_id" value="{{ $contact->id }}">
                    <input type="text" id="contact" name="contact" type="text" readonly
                        value="{{ $contact->name }}"></input>
                </div>
            </div>
        </div>

        <!-- Name Field -->
        <div class="form-group">
            <label for="name">Job Name <span class="text-danger">*</span></label>
            <input type="text" id="job_name" name="job_name" placeholder="Enter job name"
                value="{{ $mail->subject }}">
        </div>

        <!-- Job Type and Workflow Section -->
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="Job_Type">Job Type <span class="text-danger">*</span></label>
                    <select id="Job_Type" name="Job_Type">
                        <option value="" selected hidden></option>
                        @foreach ($jobTypes as $jobType)
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
                    <input type="text" id="deliveryDateTimezone" name="deliveryDateTimezone">
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
                        <option value="" selected hidden></option>
                        @foreach ($units as $unit)
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
                        @foreach ($sourceLanguages as $sourceLanguage)
                        <option value="{{ $sourceLanguage->id }}"
                            @if ($sourceLanguage->id == 2) selected @endif>
                            {{ $sourceLanguage->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="targetLanguage">Target Language <span class="text-danger">*</span></label>
                    <select id="targetLanguage">
                        <option value="" selected hidden></option>
                        @foreach ($targetLanguages as $targetLanguage)
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
                        @foreach ($subjectMatters as $subjectMatter)
                        <option value="{{ $subjectMatter->id }}"
                            @if ($subjectMatter->id == 80) selected @endif>{{ $subjectMatter->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="contentType">Content Type<span class="text-danger">*</span></label>
                    <select id="contentType">
                        @foreach ($contentTypes as $contentType)
                        <option value="{{ $contentType->id }}">{{ $contentType->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="autoPlanStrategy">Auto Plan Strategy <span class="text-danger">*</span></label>
                    <select id="autoPlanStrategy">
                        <option value="2">ratio</option>
                        <option value="1">productivity </option>

                    </select>
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-group">
                    <label for="selectionPlan">selection plan <span class="text-danger">*</span></label>
                    <select id="selectionPlan">
                        @foreach ($plans as $plan)
                        <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                        @endforeach
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

        <div class="form-group">
            <label for="online_source_files">
                <input type="checkbox" id="online_source_files" name="online_source_files" value="0">
                online source file
            </label>
        </div>

        <!-- Shared Instructions Section -->
        <div class="form-group">
            <label for="sharedInstructions">Shared Instructions</label>
            <textarea id="sharedInstructions" class="rich-text-editor" placeholder="Enter any special instructions..."></textarea>
        </div>
        <div>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="form-group">
                    <input type="hidden" name="email_id" id="email_id" value="{{ $mail->id }}">
                    <input type="file" class="file-upload" name="file[]" id="file" multiple required>
                </div>
            </form>
        </div>

        <div id="fetchedFiles" class="fetched-files">
            <h5>Files:</h5>
            <ul id="fileList" class="list-inline"></ul>
        </div>

        <div class="form-group">
            <label for="inFiles">In Folder</label>
            <div class="file-drop-zone">Drag and drop files here</div>

            <div id="drag-drop-area" class="file-upload-drop-zone"
                style="border: 2px dashed #ccc; padding: 20px; margin-top: 20px;">
            </div>
            <ul id="in-fileList"></ul>
        </div>

        <div class="form-group">
            <label for="instructionFiles">Instruction Folder</label>
            <div class="file-drop-zone">Drag and drop files here</div>

            <div id="drag-drop-area1" class="file-upload-drop-zone"
                style="border: 2px dashed #ccc; padding: 20px; margin-top: 20px;">
            </div>
            <ul id="instruction-fileList"></ul>
        </div>

        <div class="form-group">
            <label for="referenceFiles">Reference Folder</label>
            <div class="file-drop-zone">Drag and drop files here</div>

            <div id="drag-drop-area2" class="file-upload-drop-zone"
                style="border: 2px dashed #ccc; padding: 20px; margin-top: 20px;">
            </div>
            <ul id="reference-fileList"></ul>
        </div>
        <button id="get-files-btn">Get Dropped Files</button>
        <!-- Submit Button -->
        <div class="form-group text-center">
            <button type="button" class="btn-submit">Submit</button>
        </div>
    </form>
</div>

<!-- Auto Plan Strategy Modal -->
<div class="modal fade" id="autoPlanModal" tabindex="-1" aria-labelledby="autoPlanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title" id="autoPlanModalLabel">Auto Plan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div id="modal-body" class="modal-body">

            </div>

            <!-- Modal Footer with Save and Back Buttons -->
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="saveAutoPlanSpecs()">Save</button>
            </div>
        </div>
    </div>
</div>
@endsection



<!-- Include jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var mailId = <?= $mail->id ?>;
        $('#loadingOverlay').show();
        fetchJobData(mailId);
        fetchFiles(mailId);
        setupDragAndDrop();


        //////// autoplan button 
        const setupButton = document.querySelector(
            '[data-bs-target="#autoPlanModal"]'
        );
        setupButton.addEventListener("click", function() {
            getAutoPlanSpecs();
        });
        //////////


        ///////on change jobtype
        $('#Job_Type').change(function() {
            var jobTypeId = $(this).val();
            if (jobTypeId) {
                getWorkflow(jobTypeId);
            } else {
                $('#workflow').empty();
                $('#workflow').append('<option value="">Select Workflow</option>');
            }
        });
        //////////
        $(document).ready(function() {
    $('#get-files-btn').on('click', function() {
        var files = getDroppedFiles();

        // Access the arrays of file names from the returned object
        var inFolderFiles = files.inFolder;
        var instructionsFolderFiles = files.instructionsFolder;
        var referenceFolderFiles = files.referenceFolder;

        // You can now use the arrays however you like, for example, display an alert with the file names
        alert('In Folder Files: ' + inFolderFiles.join(', '));
        alert('Instructions Folder Files: ' + instructionsFolderFiles.join(', '));
        alert('Reference Folder Files: ' + referenceFolderFiles.join(', '));

        // Or log them to the console
        console.log('In Folder Files:', inFolderFiles);
        console.log('Instructions Folder Files:', instructionsFolderFiles);
        console.log('Reference Folder Files:', referenceFolderFiles);
    });
});


        // Trigger AJAX upload on file selection
        $('#file').on('change', function() {
            let formData = new FormData();
            formData.append('email_id', document.getElementById('email_id').value);
            let fileInput = document.getElementById('file');
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append('file[]', fileInput.files[i]); // Append each file
            }
            $.ajax({
                url: "{{ route('upload.file') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    mapUploadedFiles();
                },
                error: function(xhr, status, error) {
                    alert("Upload failed. Please try again.");
                }
            });
        });
    });

</script>
