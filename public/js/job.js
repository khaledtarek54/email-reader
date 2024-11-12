function fetchJobData(mailId) {
    $.ajax({
        url: "/extractApi/" + mailId,
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            mapJobData(response);
            fetchFilesFromTP(response.mail_id_tp, mailId, response);
        },
        error: function (xhr, status, error) {
            console.error("Error fetching job data:", error);
        },
        complete: function () {
            $("#loadingOverlay").hide();
        },
    });
}
function mapJobData(data) {
    if (data.job_type !== null) {
        document.getElementById("Job_Type").value = data.job_type;
        getWorkflow(data.job_type);
    }
    if (data.start_date !== null)
        document.getElementById("startDate").value = data.start_date;
    else setStartDateNow();
    if (data.delivery_time !== null)
        document.getElementById("deliveryDate").value = data.delivery_time;
    if (data.delivery_timezone !== null)
        document.getElementById("deliveryDateTimezone").value =
            data.delivery_timezone;
    if (data.amount !== null)
        document.getElementById("amount").value = data.amount;
    if (data.unit !== null) document.getElementById("unit").value = data.unit;
    if (data.source_language !== null)
        document.getElementById("sourceLanguage").value = data.source_language;
    if (data.target_language !== null)
        document.getElementById("targetLanguage").value = data.target_language;
    if (data.subject_matter !== null)
        document.getElementById("subjectMatter").value = data.subject_matter;
    if (data.content_type !== null)
        document.getElementById("contentType").value = data.content_type;
    if (data.auto_plan_strategy !== null)
        document.getElementById("autoPlanStrategy").value =
            data.auto_plan_strategy;
    if (data.selection_plan !== null)
        document.getElementById("selectionPlan").value = data.selection_plan;
    if (data.shared_instructions !== null)
        document.getElementById("sharedInstructions").value =
            data.shared_instructions;
    if (data.online_source_files) {
        const checkbox = document.getElementById("online_source_files");
        checkbox.value = data.online_source_files;
        checkbox.checked = true;
    }
}
function getWorkflow(jobTypeId) {
    $.ajax({
        url: "/Workflows",
        type: "GET",
        data: {
            job_type_id: jobTypeId,
        },
        success: function (response) {
            $("#workflow").empty();
            $.each(response, function (key, workflow) {
                $("#workflow").append(
                    '<option value="' +
                        workflow.id +
                        '">' +
                        workflow.name +
                        "</option>"
                );
            });
        },
        error: function () {
            alert("Failed to load workflows");
        },
    });
}
function createJob(mailId) {
    var formData = getJobDataFromFields();

    $.ajax({
        url: "/createJob/" + mailId,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            const errorContainer = $("#taskErrors"); // Select the error container
            errorContainer.empty(); // Clear any previous errors

            // Check for the first type of response structure (autoPlaning)
            if (response.status === 200 && response.autoPlaning) {
                const tasks = response.autoPlaning.Tasks;

                for (const taskName in tasks) {
                    const task = tasks[taskName];

                    if (task.status === 500) {
                        errorContainer.append(
                            `<h4>Errors for ${taskName}:</h4>`
                        );
                        const errorList = $("<ul></ul>");

                        task.msg.forEach((error) => {
                            if (!Array.isArray(error)) {
                                errorList.append(`<li>${error}</li>`);
                            }
                        });

                        errorContainer.append(errorList);
                    }
                }
            }
            else if (response.status === 500) {
                errorContainer.append(`<h4>Error:</h4>`);
                const errorList = $("<ul></ul>");

                response.msg.forEach((error) => {
                    if (!Array.isArray(error)) {
                        errorList.append(`<li>${error}</li>`);
                    }
                });

                errorContainer.append(errorList);
            }
            // Check for the second type of response structure (original.data)
            else if (
                response.original &&
                response.original.data &&
                response.original.data.status === 500
            ) {
                errorContainer.append(`<h4>Error:</h4>`);
                const errorList = $("<ul></ul>");

                response.original.data.msg.forEach((error) => {
                    if (!Array.isArray(error)) {
                        errorList.append(`<li>${error}</li>`);
                    }
                });

                errorContainer.append(errorList);
            }
            errorContainer.show(); // Make sure the container is visible before fading out
            setTimeout(() => {
                errorContainer.fadeOut(500); // Fade out the error container
            }, 5000);
        },
        error: function (xhr, status, error) {
            console.error("An error occurred:", error);
        },
        complete: function () {
            $("#loadingOverlay").hide();
        },
    });
}
function getJobDataFromFields() {
    var files = getDroppedFiles();
    const formData = new FormData();

    formData.append("account", document.getElementById("account").value);
    formData.append("contact_id", document.getElementById("contact_id").value);
    formData.append("job_name", document.getElementById("job_name").value);
    formData.append("Job_Type", document.getElementById("Job_Type").value);
    formData.append("workflow", document.getElementById("workflow").value);
    formData.append("startDate", document.getElementById("startDate").value);
    formData.append(
        "deliveryDate",
        document.getElementById("deliveryDate").value
    );
    formData.append(
        "deliveryDateTimezone",
        document.getElementById("deliveryDateTimezone").value
    );
    formData.append("amount", document.getElementById("amount").value);
    formData.append("unit", document.getElementById("unit").value);
    formData.append(
        "sourceLanguage",
        document.getElementById("sourceLanguage").value
    );
    formData.append(
        "targetLanguage",
        document.getElementById("targetLanguage").value
    );
    formData.append(
        "subjectMatter",
        document.getElementById("subjectMatter").value
    );
    formData.append(
        "contentType",
        document.getElementById("contentType").value
    );
    formData.append(
        "autoPlanStrategy",
        document.getElementById("autoPlanStrategy").value
    );
    formData.append(
        "selectionPlan",
        document.getElementById("selectionPlan").value
    );
    formData.append(
        "online_source_files",
        document.getElementById("online_source_files").checked ? 1 : 0
    );
    formData.append(
        "sharedInstructions",
        document.getElementById("sharedInstructions").value
    );
    formData.append("inFolderFiles", files.inFolder);
    formData.append("instructionsFolderFiles", files.instructionsFolder);
    formData.append("referenceFolderFiles", files.referenceFolder);

    return formData;
}
