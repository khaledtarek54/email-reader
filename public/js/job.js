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
        $("#Job_Type").val(data.job_type).trigger("change");
        getWorkflow(data.job_type);
    }

    document.getElementById("startDate").value = setDateNow();
    if (data.delivery_time != null) {
        var deliveryTime = formatDate(data.delivery_time);
        document.getElementById("deliveryDate").value = isDateAfterNow(
            deliveryTime.toString()
        )
            ? deliveryTime
            : null;
    }

    if (data.delivery_timezone !== null) {
        document.getElementById("deliveryDateTimezone").value =
            data.delivery_timezone;
    }

    if (data.amount !== null)
        document.getElementById("amount").value = data.amount;
    if (data.unit !== null) $("#unit").val(data.unit).trigger("change");
    if (data.source_language !== null)
        document.getElementById("sourceLanguage").value = data.source_language;
    if (data.target_language !== null)
        $("#targetLanguage").val(data.target_language).trigger("change");
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
    $("#loadingOverlay").show();
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
                errorContainer.append(
                    `<h2 class="success-message">Job created successfully with job id: ${response.job_id}</h4>`
                );
                for (const taskName in tasks) {
                    const task = tasks[taskName];

                    if (task.status === 500) {
                        errorContainer.append(
                            `<h4 class="error-header">Errors for ${taskName}:</h4>`
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
            } else if (response.status === 500) {
                errorContainer.append(`<h4 class="error-header">Error:</h4>`);
                const errorList = $("<ul></ul>");

                response.msg.forEach((error) => {
                    if (!Array.isArray(error)) {
                        errorList.append(`<li>${error}</li>`);
                    }
                });

                errorContainer.append(errorList);
            } else {
                errorContainer.append(`<h4 class="error-header">Error:</h4>`);
                const errorList = $("<ul></ul>");
                errorList.append(`<li>Error creating job</li>`);
                errorContainer.append(errorList);
            }

            errorContainer.show();
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
function isDateAfterNow(dateString) {
    // Split the input date and time
    const [datePart, timePart] = dateString.split(" ");
    const [day, month, year] = datePart.split("-").map(Number);
    const [hours, minutes] = timePart.split(":").map(Number);

    // Create a new Date object in the proper format
    const inputDate = new Date(year, month - 1, day, hours, minutes); // Month is 0-indexed in JavaScript

    // Get the current date and time
    const now = new Date();

    // Compare the input date with the current date
    return inputDate > now;
}
function setDateNow() {
    const now = new Date();

    // Format the date to 'YYYY-MM-DDTHH:MM' in local time
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, "0"); // Months are zero-indexed
    const day = String(now.getDate()).padStart(2, "0");
    const hours = String(now.getHours()).padStart(2, "0");
    const minutes = String(now.getMinutes()).padStart(2, "0");

    const formattedDate = `${day}-${month}-${year} ${hours}:${minutes}`;
    return formattedDate;
}
function formatDate(inputDate) {
    const isoDate = inputDate.replace(" ", "T");

    const now = new Date(isoDate);

    // Format the date to 'YYYY-MM-DDTHH:MM' in local time
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, "0"); // Months are zero-indexed
    const day = String(now.getDate()).padStart(2, "0");
    const hours = String(now.getHours()).padStart(2, "0");
    const minutes = String(now.getMinutes()).padStart(2, "0");

    const formattedDate = `${day}-${month}-${year} ${hours}:${minutes}`;
    return formattedDate;
}
function applyTimezoneOffset(dateStr, offset) {
    const [day, month, year, hour, minute] = dateStr
        .split(/[-\s:]+/)
        .map(Number);

    // Create a Date object with the given date
    const date = new Date(year, month - 1, day, hour, minute);

    // Calculate the offset in minutes (e.g. +02:00 => 120 minutes)
    const offsetMinutes =
        parseInt(offset.slice(1, 3)) * 60 + parseInt(offset.slice(4, 6));

    // Adjust the date based on the offset
    const newDate = new Date(
        date.getTime() + (offset[0] === "+" ? 1 : -1) * offsetMinutes * 60000
    );

    // Format the adjusted date in the required format (dd-mm-yyyy hh:mm)
    const formattedDate = `${String(newDate.getDate()).padStart(
        2,
        "0"
    )}-${String(newDate.getMonth() + 1).padStart(
        2,
        "0"
    )}-${newDate.getFullYear()} ${String(newDate.getHours()).padStart(
        2,
        "0"
    )}:${String(newDate.getMinutes()).padStart(2, "0")}`;

    // Return the formatted date
    return formattedDate;
}
function getUserTimezoneOffset() {
    const offsetMinutes = new Date().getTimezoneOffset(); // Get timezone offset in minutes

    // Calculate absolute hours and minutes from the offset
    const hours = Math.abs(Math.floor(offsetMinutes / 60)); // Absolute hours
    const minutes = Math.abs(offsetMinutes % 60); // Remaining minutes

    // Determine sign based on offset (positive or negative)
    const sign = offsetMinutes > 0 ? "-" : "+";

    // Format and return the offset as +hh:mm or -hh:mm
    return `${sign}${String(hours).padStart(2, "0")}:${String(minutes).padStart(
        2,
        "0"
    )}`;
}
function setTimeZone(timezone) {
    const selectElement = document.getElementById("timezoneSelector");

    const option = Array.from(selectElement.options).find(
        (opt) => opt.value == timezone
    );
    if (option) {
        selectElement.value = timezone;
    }
}
function invertOffset(offset) {
    if (
        !offset ||
        typeof offset !== "string" ||
        !/^[+-]\d{2}:\d{2}$/.test(offset)
    ) {
        throw new Error("Invalid offset format. Expected format: ±HH:MM");
    }
    const sign = offset[0] === "+" ? "-" : "+";
    return sign + offset.slice(1);
}
