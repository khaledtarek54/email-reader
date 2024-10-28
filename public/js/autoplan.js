function changeAutoPlan(weekdays, end_date) {
    $("#loadingOverlay").show();

    const planStart = document.getElementById("planstart").value;
    const planAmount = document.getElementById("planamount").value;
    const autoplanid = document.getElementById("autoPlanStrategy").value;
    const autoassignid = document.getElementById("selectionPlan").value;
    const workflowid = document.getElementById("workflow").value;
    const weekendDays = [];

    weekdays.forEach((day) => {
        const checkbox = document.getElementById(
            "weekDay_" + day.WeekDay.number
        );
        if (checkbox && !checkbox.checked) {
            weekendDays.push(day.WeekDay.number);
        }
    });

    var formData = new FormData();
    formData.append("plan_start", planStart);
    formData.append("plan_end", end_date);
    formData.append("plan_amount", planAmount);
    formData.append("weekend_days", weekendDays);
    formData.append("autoplan_id", autoplanid);
    formData.append("rs_plan_id", autoassignid);
    formData.append("workflow_id", workflowid);
    formData.append("unit_id", document.getElementById("unit").value);
    formData.append("job_type_id", document.getElementById("Job_Type").value);
    formData.append("account_id", document.getElementById("account").value);
    $.ajax({
        data: formData,
        type: "post",
        url: "/autoPlanEdit",
        dataType: "json",
        contentType: false,
        processData: false,
        success: function (data, textStatus) {
            console.log("Response:", data);
            populateFormFields(data);
            $("#loadingOverlay").hide();
        },
    });
}

function populateFormFields(response) {
    for (const taskId in response) {
        if (response.hasOwnProperty(taskId)) {
            const task = response[taskId];

            const startDateInput = document.getElementById(
                `start_date_${taskId}`
            );
            if (startDateInput) {
                startDateInput.value = task.start;
            }

            const endDateInput = document.getElementById(`end_date_${taskId}`);
            if (endDateInput) {
                endDateInput.value = task.end;
            }

            const amountInput = document.getElementById(`TaskAmount_${taskId}`);
            if (amountInput) {
                amountInput.value = task.amount;
            }
        }
    }
}

function saveAutoPlanSpecs() {
    $("#loadingOverlay").show();
    var formData = $("#job_specs_form").serialize();

    $.ajax({
        data: formData,
        type: "post",
        url: "/saveAutoPlanSpecs",
        dataType: "json",
        success: function (data, textStatus) {
            $("#loadingOverlay").hide();
            //$("#autoPlanModal").modal('hide');
        },
    });
}

function getAutoPlanSpecs() {
    const formData = new FormData();
    formData.append("amount", document.getElementById("amount").value);
    formData.append("start_date", document.getElementById("startDate").value);
    formData.append("end_date", document.getElementById("deliveryDate").value);
    formData.append(
        "JobAutoplanStrategyId",
        document.getElementById("autoPlanStrategy").value
    );
    formData.append("job_type_id", document.getElementById("Job_Type").value);
    formData.append("unit_id", document.getElementById("unit").value);
    formData.append("plan_id", document.getElementById("selectionPlan").value);
    formData.append("phase_type_id", document.getElementById("workflow").value);
    formData.append("account_id", document.getElementById("account").value);
    formData.append(
        "source_language_id",
        document.getElementById("sourceLanguage").value
    );
    formData.append(
        "target_language_id",
        document.getElementById("targetLanguage").value
    );
    formData.append(
        "subject_matter_id",
        document.getElementById("subjectMatter").value
    );
    formData.append(
        "content_type_id",
        document.getElementById("contentType").value
    );
    formData.append("contact_id", document.getElementById("contact_id").value);

    $.ajax({
        url: "/autoPlan",
        method: "POST",
        data: formData,
        processData: false, // Important for FormData
        contentType: false, // Important for FormData
        success: function (response) {
            $("#autoPlanModal .modal-body").html(response.html);
            //$("#autoPlanModal").modal("show");
        },
        error: function (xhr, status, error) {
            console.error("Error loading auto plan:", xhr.responseText);
        },
    });
}
