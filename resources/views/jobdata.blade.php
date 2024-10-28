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

        function mapJobData(data) {

            //populateSelect('workflow', data.workflows);
            if (data.job_type !== null) {
                document.getElementById('Job_Type').value = data.job_type;
                getWorkflow(data.job_type)
            }
            if (data.start_date !== null) document.getElementById('startDate').value = data.start_date;
            if (data.delivery_time !== null) document.getElementById('deliveryDate').value = data.delivery_time;
            if (data.delivery_timezone !== null) document.getElementById('deliveryDateTimezone').value = data.delivery_timezone;
            if (data.amount !== null) document.getElementById('amount').value = data.amount;
            if (data.unit !== null) document.getElementById('unit').value = data.unit;
            if (data.source_language !== null) document.getElementById('sourceLanguage').value = data.source_language;
            if (data.target_language !== null) document.getElementById('targetLanguage').value = data.target_language;
            if (data.subject_matter !== null) document.getElementById('subjectMatter').value = data.subject_matter;
            if (data.content_type !== null) document.getElementById('contentType').value = data.content_type;
            if (data.auto_plan_strategy !== null) document.getElementById('autoPlanStrategy').value = data.auto_plan_strategy;
            if (data.selection_plan !== null) document.getElementById('selectionPlan').value = data.selection_plan;
            if (data.shared_instructions !== null) document.getElementById('sharedInstructions').value = data.shared_instructions;
            if (data.online_source_files) {
                const checkbox = document.getElementById('online_source_files');
                checkbox.value = data.online_source_files;
                checkbox.checked = true;
            }

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
                var fileName = $(this).data('file-name');
                if (fileName) {
                    e.originalEvent.dataTransfer.setData('text/plain',
                        fileName); // Store file name in the drag data
                    $(this).css('opacity', '0.5'); // Change appearance during drag
                }
            });

            $('.draggable-file').on('dragend', function() {
                $(this).css('opacity', '1'); // Reset appearance when drag ends
            });

            // Setup droppable area
            var dropZone = $('#drag-drop-area');
            var dropZone1 = $('#drag-drop-area1');
            var dropZone2 = $('#drag-drop-area2');
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
                var droppedFile = e.originalEvent.dataTransfer.getData(
                    'text/plain'); // Get the dragged file name

                if (!droppedFile) {
                    console.log('No file data was dropped');
                    return; // Exit if no valid file was dropped
                }

                console.log('File dropped:', droppedFile);

                // Check if the file already exists in the drop zone
                var existingDroppedFile = $(this).find(`div:contains('${droppedFile}')`);
                if (existingDroppedFile.length > 0) {
                    console.log('File already dropped:', droppedFile);
                    return; // Prevent duplicate file drops
                }

                // Add the dropped file to the drop zone
                var droppedItem = $('<div></div>')
                    .text(droppedFile)
                    .css({
                        padding: '5px 10px',
                        border: '1px solid #ccc',
                        borderRadius: '5px',
                        marginBottom: '10px',
                        position: 'relative'
                    });

                // Create delete button
                var deleteButton = $('<button></button>')
                    .text('Delete')
                    .css({
                        position: 'absolute',
                        right: '5px',
                        top: '5px',
                        background: 'red',
                        color: 'white',
                        border: 'none',
                        borderRadius: '3px',
                        padding: '2px 5px',
                        cursor: 'pointer'
                    })
                    .data('file-name',
                        droppedFile) // Store the file name in the button's data attributes
                    .on('click', function() {
                        var fileNameToRestore = $(this).data(
                            'file-name'); // Retrieve the file name when clicked
                        console.log('Restoring file:', fileNameToRestore);

                        // Restore file to the list
                        var listItem = $('<li></li>')
                            .addClass('list-inline-item draggable-file')
                            .attr('draggable', true)
                            .attr('data-file-name', fileNameToRestore)
                            .text(fileNameToRestore)
                            .css({
                                marginRight: '15px',
                                padding: '5px 10px',
                                border: '1px solid #ccc',
                                borderRadius: '5px',
                                cursor: 'grab'
                            });

                        $('#fileList').append(listItem); // Restore file to the list
                        droppedItem.remove(); // Remove from drop area

                        setupDragAndDrop(); // Reinitialize drag events for restored files
                    });

                droppedItem.append(deleteButton); // Add delete button to the dropped item
                $(this).append(droppedItem); // Add the dropped file inside the drop area
                $(this).removeClass('dragover'); // Remove highlight effect when dropped

                // Remove the dragged file from the file list
                $('li[data-file-name="' + droppedFile + '"]').remove();

                setupDragAndDrop(); // Reinitialize drag events for all files after changes
            });

            dropZone1.on('dragover', function(e) {
                e.preventDefault(); // Allow drop
                $(this).addClass('dragover'); // Optional: Add a highlight effect when dragging over
            });
            dropZone1.on('dragleave', function() {
                $(this).removeClass('dragover'); // Remove highlight effect when leaving
            });
            // Handle file drop
            dropZone1.on('drop', function(e) {
                e.preventDefault();
                var droppedFile = e.originalEvent.dataTransfer.getData(
                    'text/plain'); // Get the dragged file name

                if (!droppedFile) {
                    console.log('No file data was dropped');
                    return; // Exit if no valid file was dropped
                }

                console.log('File dropped:', droppedFile);

                // Check if the file already exists in the drop zone
                var existingDroppedFile = $(this).find(`div:contains('${droppedFile}')`);
                if (existingDroppedFile.length > 0) {
                    console.log('File already dropped:', droppedFile);
                    return; // Prevent duplicate file drops
                }

                // Add the dropped file to the drop zone
                var droppedItem = $('<div></div>')
                    .text(droppedFile)
                    .css({
                        padding: '5px 10px',
                        border: '1px solid #ccc',
                        borderRadius: '5px',
                        marginBottom: '10px',
                        position: 'relative'
                    });

                // Create delete button
                var deleteButton = $('<button></button>')
                    .text('Delete')
                    .css({
                        position: 'absolute',
                        right: '5px',
                        top: '5px',
                        background: 'red',
                        color: 'white',
                        border: 'none',
                        borderRadius: '3px',
                        padding: '2px 5px',
                        cursor: 'pointer'
                    })
                    .data('file-name',
                        droppedFile) // Store the file name in the button's data attributes
                    .on('click', function() {
                        var fileNameToRestore = $(this).data(
                            'file-name'); // Retrieve the file name when clicked
                        console.log('Restoring file:', fileNameToRestore);

                        // Restore file to the list
                        var listItem = $('<li></li>')
                            .addClass('list-inline-item draggable-file')
                            .attr('draggable', true)
                            .attr('data-file-name', fileNameToRestore)
                            .text(fileNameToRestore)
                            .css({
                                marginRight: '15px',
                                padding: '5px 10px',
                                border: '1px solid #ccc',
                                borderRadius: '5px',
                                cursor: 'grab'
                            });

                        $('#fileList').append(listItem); // Restore file to the list
                        droppedItem.remove(); // Remove from drop area

                        setupDragAndDrop(); // Reinitialize drag events for restored files
                    });

                droppedItem.append(deleteButton); // Add delete button to the dropped item
                $(this).append(droppedItem); // Add the dropped file inside the drop area
                $(this).removeClass('dragover'); // Remove highlight effect when dropped

                // Remove the dragged file from the file list
                $('li[data-file-name="' + droppedFile + '"]').remove();

                setupDragAndDrop(); // Reinitialize drag events for all files after changes
            });
            dropZone2.on('dragover', function(e) {
                e.preventDefault(); // Allow drop
                $(this).addClass('dragover'); // Optional: Add a highlight effect when dragging over
            });
            dropZone2.on('dragleave', function() {
                $(this).removeClass('dragover'); // Remove highlight effect when leaving
            });
            // Handle file drop
            dropZone2.on('drop', function(e) {
                e.preventDefault();
                var droppedFile = e.originalEvent.dataTransfer.getData(
                    'text/plain'); // Get the dragged file name

                if (!droppedFile) {
                    console.log('No file data was dropped');
                    return; // Exit if no valid file was dropped
                }

                console.log('File dropped:', droppedFile);

                // Check if the file already exists in the drop zone
                var existingDroppedFile = $(this).find(`div:contains('${droppedFile}')`);
                if (existingDroppedFile.length > 0) {
                    console.log('File already dropped:', droppedFile);
                    return; // Prevent duplicate file drops
                }

                // Add the dropped file to the drop zone
                var droppedItem = $('<div></div>')
                    .text(droppedFile)
                    .css({
                        padding: '5px 10px',
                        border: '1px solid #ccc',
                        borderRadius: '5px',
                        marginBottom: '10px',
                        position: 'relative'
                    });

                // Create delete button
                var deleteButton = $('<button></button>')
                    .text('Delete')
                    .css({
                        position: 'absolute',
                        right: '5px',
                        top: '5px',
                        background: 'red',
                        color: 'white',
                        border: 'none',
                        borderRadius: '3px',
                        padding: '2px 5px',
                        cursor: 'pointer'
                    })
                    .data('file-name',
                        droppedFile) // Store the file name in the button's data attributes
                    .on('click', function() {
                        var fileNameToRestore = $(this).data(
                            'file-name'); // Retrieve the file name when clicked
                        console.log('Restoring file:', fileNameToRestore);

                        // Restore file to the list
                        var listItem = $('<li></li>')
                            .addClass('list-inline-item draggable-file')
                            .attr('draggable', true)
                            .attr('data-file-name', fileNameToRestore)
                            .text(fileNameToRestore)
                            .css({
                                marginRight: '15px',
                                padding: '5px 10px',
                                border: '1px solid #ccc',
                                borderRadius: '5px',
                                cursor: 'grab'
                            });

                        $('#fileList').append(listItem); // Restore file to the list
                        droppedItem.remove(); // Remove from drop area

                        setupDragAndDrop(); // Reinitialize drag events for restored files
                    });

                droppedItem.append(deleteButton); // Add delete button to the dropped item
                $(this).append(droppedItem); // Add the dropped file inside the drop area
                $(this).removeClass('dragover'); // Remove highlight effect when dropped

                // Remove the dragged file from the file list
                $('li[data-file-name="' + droppedFile + '"]').remove();

                setupDragAndDrop(); // Reinitialize drag events for all files after changes
            });
        }


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

        function getWorkflow(jobTypeId) {
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
        }
        ///////////


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
                    fetchFiles(document.getElementById('email_id').value);
                },
                error: function(xhr, status, error) {
                    alert("Upload failed. Please try again.");
                }
            });
        });
    });
</script>
<script src="{{ asset('js/autoplan.js') }}"></script>