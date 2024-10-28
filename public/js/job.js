function fetchJobData(mailId) {
    $.ajax({
        url: "/extractApi/" + mailId,
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            mapJobData(response);
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
    //populateSelect('workflow', data.workflows);
    if (data.job_type !== null) {
        document.getElementById("Job_Type").value = data.job_type;
        getWorkflow(data.job_type);
    }
    if (data.start_date !== null)
        document.getElementById("startDate").value = data.start_date;
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
