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
                <div class="col-md-11">
                    <div class="form-group">
                        <label for="autoPlanStrategy">Auto Plan Strategy <span class="text-danger">*</span></label>
                        <select id="autoPlanStrategy">
                            <option value="2">ratio</option>
                            <option value="1">productivity </option>

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
                        <label for="autoAssignment">Auto assignment <span class="text-danger">*</span></label>
                        <select id="autoAssignment">

                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="targetLanguage">selection plan <span class="text-danger">*</span></label>
                        <select id="targetLanguage">
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->id }}">{{ $plan->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div>
                <label for="online_source_files">
                    <input type="checkbox" id="online_source_files" name="online_source_files" value="0">
                    online source file
                </label>
            </div>
            
            <div id="fetchedFiles" class="fetched-files">
    <h5>Files:</h5>
    <ul id="fileList" class="list-inline"></ul>
    
</div>


            <!-- File Uploads Section -->
            <div class="section-title">File Uploads</div>

            <div class="form-group">
                <label for="inFiles">In Folder</label>
                <div class="file-drop-zone">Drag and drop files here</div>
    <input type="file" id="inFiles" class="file-upload" multiple>
    <div id="drag-drop-area" class="file-upload-drop-zone" style="border: 2px dashed #ccc; padding: 20px; margin-top: 20px;">
</div>

<ul id="fileList"></ul>

    </div>
            
            <div class="form-group">
                <label for="instructionFiles">Instruction Folder</label>
                <div class="file-drop-zone">Drag and drop files here</div>
    <input type="file" id="instructionFiles" class="file-upload" multiple>
    </div>
            
            <div class="form-group">
                <label for="referenceFiles">Reference Folder</label>
                <div class="file-drop-zone">Drag and drop files here</div>
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

        const setupButton = document.querySelector('[data-bs-target="#autoPlanModal"]');

        setupButton.addEventListener('click', function() {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const formData = new FormData();

            formData.append('amount', document.getElementById('amount').value);
            formData.append('start_date', document.getElementById('startDate').value);
            formData.append('end_date', document.getElementById('deliveryDate').value);
            formData.append('JobAutoplanStrategyId', document.getElementById('autoPlanStrategy').value);
            formData.append('job_type_id', document.getElementById('Job_Type').value);
            formData.append('unit_id', document.getElementById('unit').value);
            formData.append('plan_id', document.getElementById('selectionPlan').value);
            formData.append('phase_type_id', document.getElementById('workflow').value);
            formData.append('account_id', document.getElementById('account').value);
            formData.append('source_language_id', document.getElementById('sourceLanguage').value);
            formData.append('target_language_id', document.getElementById('targetLanguage').value);
            formData.append('subject_matter_id', document.getElementById('subjectMatter').value);
            formData.append('content_type_id', document.getElementById('contentType').value);



            formData.append('contact_id', document.getElementById('contact_id').value);


            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/autoPlan', true);
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    console.log('Data submitted successfully:', xhr.responseText);
                } else {
                    console.error('Error submitting data:', xhr.statusText);
                }
            };
            xhr.send(formData);
        });





        $('#Job_Type').change(function() {
            var jobTypeId = $(this).val();

            if (jobTypeId) {
                $.ajax({
                    url: '/Workflows',
                    type: 'GET',
                    data: {
                        job_type_id: jobTypeId
                    },
                    success: function(response) {
                        $('#workflow').empty();
                        $.each(response, function(key, workflow) {
                            $('#workflow').append('<option value="' + workflow.id +
                                '">' + workflow.name + '</option>');
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

    $(document).ready(function() {

        var mailId = <?= $mail->id ?>;
        console.log(mailId);
        $('#loadingOverlay').show();
        fetchJobData(mailId);
        fetchFiles(mailId);

        function fetchJobData(mailId) {
            $.ajax({
                url: '/extractApi/' + mailId,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    mapJobData(response);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching job data:", error);
                },
                complete: function() {
                    $('#loadingOverlay').hide();
                }
            });
        }
function fetchFiles(mailId) {
        $.ajax({
            url: '/fetch-files/' + mailId,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                displayFiles(response);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching files:", error);
            }
        });
    }

    function displayFiles(files) {
        $('#fileList').empty(); // Clear the existing list
        $.each(files, function(index, file) {
            var fileTypeIcon = getFileIcon(file.file_name);
            $('#fileList').append(
                '<li class="list-inline-item file-draggable" draggable="true" data-file-name="' + file.file_name + '">' +
                '<span class="file-icon">' + fileTypeIcon + '</span>' +
                '<span class="file-name">' + file.file_name + '</span>' +
                '</li>'
            );
        });

        addDragAndDropEvents();
    }

    function getFileIcon(fileName) {
        var extension = fileName.split('.').pop().toLowerCase();
        var icons = {
            'pdf': '<i class="fas fa-file-pdf"></i>',
            'doc': '<i class="fas fa-file-word"></i>',
            'docx': '<i class="fas fa-file-word"></i>',
            'xls': '<i class="fas fa-file-excel"></i>',
            'xlsx': '<i class="fas fa-file-excel"></i>',
            'txt': '<i class="fas fa-file-alt"></i>',
            'jpg': '<i class="fas fa-file-image"></i>',
            'png': '<i class="fas fa-file-image"></i>',
            'zip': '<i class="fas fa-file-archive"></i>',
            'default': '<i class="fas fa-file"></i>'
        };
        return icons[extension] || icons['default'];
    }

    function addDragAndDropEvents() {
        // Drag start event
        $('.file-draggable').on('dragstart', function(event) {
            $(this).addClass('dragging');
            event.originalEvent.dataTransfer.setData('text/plain', $(this).data('file-name'));
        });

        // Drag end event
        $('.file-draggable').on('dragend', function() {
            $(this).removeClass('dragging');
        });

        // Drag over and drop events for each folder drop area
        $('.drop-area').on('dragover', function(event) {
            event.preventDefault();
            $(this).addClass('dragover');
        });

        $('.drop-area').on('dragleave', function() {
            $(this).removeClass('dragover');
        });

        $('.drop-area').on('drop', function(event) {
            event.preventDefault();
            $(this).removeClass('dragover');
            var fileName = event.originalEvent.dataTransfer.getData('text/plain');
            var dropAreaId = $(this).attr('id');

            moveFileToFolder(fileName, dropAreaId);
        });
    }

    function moveFileToFolder(fileName, folder) {
        // You can modify this to perform AJAX calls to update the file location on the server
        console.log('File "' + fileName + '" moved to ' + folder);
        alert('File "' + fileName + '" has been moved to ' + folder);
    }
        function mapJobData(data) {

            //populateSelect('workflow', data.workflows);
            if (data.job_type !== null) document.getElementById('Job_Type').value = data.job_type;
            if (data.start_date !== null) document.getElementById('startDate').value = data.start_date;
            if (data.delivery_time !== null) document.getElementById('deliveryDate').value = data.delivery_time;
            if (data.delivery_timezone !== null) document.getElementById('deliveryDateTimezone').value = data
                .delivery_timezone;
            if (data.amount !== null) document.getElementById('amount').value = data.amount;
            if (data.unit !== null) document.getElementById('unit').value = data.unit;
            if (data.source_language !== null) document.getElementById('sourceLanguage').value = data
                .source_language;
            if (data.target_language !== null) document.getElementById('targetLanguage').value = data
                .target_language;
            if (data.subject_matter !== null) document.getElementById('subjectMatter').value = data
                .subject_matter;
            if (data.content_type !== null) document.getElementById('contentType').value = data.content_type;
            if (data.auto_plan_strategy !== null) document.getElementById('autoPlanStrategy').value = data
                .auto_plan_strategy;
            //if (data.auto_assignment !== null) document.getElementById('sourceLanguage').value = data.auto_assignment;
            if (data.selection_plan !== null) document.getElementById('targetLanguage').value = data
                .selection_plan;
            if (data.shared_instructions !== null) document.getElementById('sharedInstructions').value = data
                .shared_instructions;
            if (data.online_source_files !== null) document.getElementById('online_source_files').value = data
                .online_source_files;

        }

        function fetchFiles(mailId) {
    $.ajax({
        url: '/fetch-files/' + mailId,
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            displayFiles(response);
        },
        error: function(xhr, status, error) {
            console.error("Error fetching files:", error);
        }
    });
}

function displayFiles(files) {
    $('#fileList').empty(); // Clear the existing list

    // Add files to the list and make them draggable
    $.each(files, function(index, file) {
        var listItem = $('<li></li>')
            .addClass('list-inline-item draggable-file')
            .attr('draggable', true) // Make it draggable
            .attr('data-file-name', file.file_name) // Store file name in a data attribute
            .text(file.file_name)
            .css({
                marginRight: '15px',
                padding: '5px 10px',
                border: '1px solid #ccc',
                borderRadius: '5px',
                cursor: 'grab'
            });

        $('#fileList').append(listItem);
    });

    setupDragAndDrop();
}

function setupDragAndDrop() {
    // Allow file items to be draggable
    $('.draggable-file').on('dragstart', function(e) {
        e.originalEvent.dataTransfer.setData('text/plain', $(this).data('file-name')); // Store file name in the drag data
        $(this).css('opacity', '0.5'); // Change appearance during drag
    });

    $('.draggable-file').on('dragend', function() {
        $(this).css('opacity', '1'); // Reset appearance when drag ends
    });

    // Setup droppable area
    var dropZone = $('#drag-drop-area'); // The specific drop area
    dropZone.on('dragover', function(e) {
        e.preventDefault(); // Allow drop
        $(this).addClass('dragover'); // Optional: Add a highlight effect when dragging over
    });

    dropZone.on('dragleave', function() {
        $(this).removeClass('dragover'); // Remove highlight effect when leaving
    });

    // Handle file drop
    dropZone.on('drop', function(e) {
        e.preventDefault();
        var droppedFile = e.originalEvent.dataTransfer.getData('text/plain'); // Get the dragged file name
        console.log('File dropped:', droppedFile); // Handle file drop here

        // Add the dropped file to the drop zone
        var droppedItem = $('<div></div>')
            .text(droppedFile)
            .css({
                padding: '5px 10px',
                border: '1px solid #ccc',
                borderRadius: '5px',
                marginBottom: '10px'
            });
        
        $(this).append(droppedItem); // Add the dropped file inside the drop area
        $(this).removeClass('dragover'); // Remove highlight effect when dropped
    });
}

    });
</script>
