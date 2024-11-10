function fetchFiles(mailId, jobData) {
    $.ajax({
        url: "/fetch-files/" + mailId,
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            displayFiles(response);
            console.log(response);
            if (!response.length == 0) {
                assignFilesToFolders(jobData, response);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error fetching files:", error);
        },
    });
}
function assignFilesToFolders(jobData) {
    // Parse the folders data (assuming they are in JSON string format)
    const inFolderFiles = jobData.in_folder
        ? JSON.parse(jobData.in_folder)
        : [];
    const instructionsFolderFiles = jobData.instructions_folder
        ? JSON.parse(jobData.instructions_folder)
        : [];
    const referenceFolderFiles = jobData.reference_folder
        ? JSON.parse(jobData.reference_folder)
        : [];

    // Define the drop zones for each folder
    var inFolderZone = $("#drag-drop-area"); // Assuming this is for in_folder
    var instructionsFolderZone = $("#drag-drop-area1"); // Assuming this is for instructions_folder
    var referenceFolderZone = $("#drag-drop-area2"); // Assuming this is for reference_folder

    function removeFileFromFolders(file) {
        [inFolderFiles, instructionsFolderFiles, referenceFolderFiles].forEach(
            (folder) => {
                const index = folder.indexOf(file);
                if (index !== -1) {
                    folder.splice(index, 1); // Remove the file from this specific folder array
                }
            }
        );
    }

    // Helper function to add files to the appropriate drop zone
    function addFilesToDropZone(zone, files) {
        files.forEach(function (file) {
            // Check if the file is already in the drop zone
            if (
                inFolderFiles.includes(file) ||
                instructionsFolderFiles.includes(file) ||
                referenceFolderFiles.includes(file)
            ) {
                removeFileFromFolders(file); // Remove the file from all folder arrays
                var existingFile = zone.find(`div:contains('${file}')`);
                if (existingFile.length > 0) {
                    console.log(
                        `File already exists in the drop zone: ${file}`
                    );
                    return;
                }

                // Add the file to the drop zone
                var droppedItem = $("<div></div>").text(file).css({
                    padding: "5px 10px",
                    border: "1px solid #ccc",
                    borderRadius: "5px",
                    marginBottom: "10px",
                    position: "relative",
                });

                // Create delete button
                var deleteButton = $("<button></button>")
                    .text("Delete")
                    .css({
                        position: "absolute",
                        right: "5px",
                        top: "5px",
                        background: "red",
                        color: "white",
                        border: "none",
                        borderRadius: "3px",
                        padding: "2px 5px",
                        cursor: "pointer",
                    })
                    .data("file-name", file)
                    .on("click", function () {
                        var fileNameToRestore = $(this).data("file-name");
                        console.log("Restoring file:", fileNameToRestore);

                        // Restore file to the list
                        var listItem = $("<li></li>")
                            .addClass("list-inline-item draggable-file")
                            .attr("draggable", true)
                            .attr("data-file-name", fileNameToRestore)
                            .text(fileNameToRestore)
                            .css({
                                marginRight: "15px",
                                padding: "5px 10px",
                                border: "1px solid #ccc",
                                borderRadius: "5px",
                                cursor: "grab",
                            });

                        $("#fileList").append(listItem); // Restore file to the list
                        droppedItem.remove(); // Remove from drop area

                        setupDragAndDrop(); // Reinitialize drag events for restored files
                    });

                droppedItem.append(deleteButton); // Add delete button to the dropped item
                zone.append(droppedItem); // Add the dropped file inside the drop area
                $("#fileList").find(`li[data-file-name="${file}"]`).remove();
            }
        });
    }

    // Add files to their respective zones
    addFilesToDropZone(inFolderZone, inFolderFiles);
    addFilesToDropZone(instructionsFolderZone, instructionsFolderFiles);
    addFilesToDropZone(referenceFolderZone, referenceFolderFiles);
}

function displayFiles(files) {
    $("#fileList").empty(); // Clear the existing list

    // Add files to the list and make them draggable
    $.each(files, function (index, file) {
        var listItem = $("<li></li>")
            .addClass("list-inline-item draggable-file")
            .attr("draggable", true) // Make it draggable
            .attr("data-file-name", file.file_name) // Store file name in a data attribute
            .text(file.file_name)
            .css({
                marginRight: "15px",
                padding: "5px 10px",
                border: "1px solid #ccc",
                borderRadius: "5px",
                cursor: "grab",
            });

        $("#fileList").append(listItem);
    });

    setupDragAndDrop();
}
function mapUploadedFiles() {
    let fileInput = document.getElementById("file");
    const files = fileInput.files;
    // Add files to the list and make them draggable
    $.each(files, function (index, file) {
        var listItem = $("<li></li>")
            .addClass("list-inline-item draggable-file")
            .attr("draggable", true) // Make it draggable
            .attr("data-file-name", file.name) // Store file name in a data attribute
            .text(file.name)
            .css({
                marginRight: "15px",
                padding: "5px 10px",
                border: "1px solid #ccc",
                borderRadius: "5px",
                cursor: "grab",
            });

        $("#fileList").append(listItem);
    });
}
function setupDragAndDrop() {
    // Allow file items to be draggable
    $(".draggable-file").on("dragstart", function (e) {
        var fileName = $(this).data("file-name");
        if (fileName) {
            e.originalEvent.dataTransfer.setData("text/plain", fileName); // Store file name in the drag data
            $(this).css("opacity", "0.5"); // Change appearance during drag
        }
    });

    $(".draggable-file").on("dragend", function () {
        $(this).css("opacity", "1"); // Reset appearance when drag ends
    });

    // Setup droppable area
    var dropZone = $("#drag-drop-area");
    var dropZone1 = $("#drag-drop-area1");
    var dropZone2 = $("#drag-drop-area2");
    dropZone.on("dragover", function (e) {
        e.preventDefault(); // Allow drop
        $(this).addClass("dragover"); // Optional: Add a highlight effect when dragging over
    });

    dropZone.on("dragleave", function () {
        $(this).removeClass("dragover"); // Remove highlight effect when leaving
    });

    // Handle file drop
    dropZone.on("drop", function (e) {
        e.preventDefault();
        var droppedFile = e.originalEvent.dataTransfer.getData("text/plain"); // Get the dragged file name

        if (!droppedFile) {
            console.log("No file data was dropped");
            return; // Exit if no valid file was dropped
        }

        console.log("File dropped:", droppedFile);

        // Check if the file already exists in the drop zone
        var existingDroppedFile = $(this).find(
            `div:contains('${droppedFile}')`
        );
        if (existingDroppedFile.length > 0) {
            console.log("File already dropped:", droppedFile);
            return; // Prevent duplicate file drops
        }

        // Add the dropped file to the drop zone
        var droppedItem = $("<div></div>").text(droppedFile).css({
            padding: "5px 10px",
            border: "1px solid #ccc",
            borderRadius: "5px",
            marginBottom: "10px",
            position: "relative",
        });

        // Create delete button
        var deleteButton = $("<button></button>")
            .text("Delete")
            .css({
                position: "absolute",
                right: "5px",
                top: "5px",
                background: "red",
                color: "white",
                border: "none",
                borderRadius: "3px",
                padding: "2px 5px",
                cursor: "pointer",
            })
            .data("file-name", droppedFile) // Store the file name in the button's data attributes
            .on("click", function () {
                var fileNameToRestore = $(this).data("file-name"); // Retrieve the file name when clicked
                console.log("Restoring file:", fileNameToRestore);

                // Restore file to the list
                var listItem = $("<li></li>")
                    .addClass("list-inline-item draggable-file")
                    .attr("draggable", true)
                    .attr("data-file-name", fileNameToRestore)
                    .text(fileNameToRestore)
                    .css({
                        marginRight: "15px",
                        padding: "5px 10px",
                        border: "1px solid #ccc",
                        borderRadius: "5px",
                        cursor: "grab",
                    });

                $("#fileList").append(listItem); // Restore file to the list
                droppedItem.remove(); // Remove from drop area

                setupDragAndDrop(); // Reinitialize drag events for restored files
            });

        droppedItem.append(deleteButton); // Add delete button to the dropped item
        $(this).append(droppedItem); // Add the dropped file inside the drop area
        $(this).removeClass("dragover"); // Remove highlight effect when dropped

        // Remove the dragged file from the file list
        $('li[data-file-name="' + droppedFile + '"]').remove();

        setupDragAndDrop(); // Reinitialize drag events for all files after changes
    });

    dropZone1.on("dragover", function (e) {
        e.preventDefault(); // Allow drop
        $(this).addClass("dragover"); // Optional: Add a highlight effect when dragging over
    });
    dropZone1.on("dragleave", function () {
        $(this).removeClass("dragover"); // Remove highlight effect when leaving
    });
    // Handle file drop
    dropZone1.on("drop", function (e) {
        e.preventDefault();
        var droppedFile = e.originalEvent.dataTransfer.getData("text/plain"); // Get the dragged file name

        if (!droppedFile) {
            console.log("No file data was dropped");
            return; // Exit if no valid file was dropped
        }

        console.log("File dropped:", droppedFile);

        // Check if the file already exists in the drop zone
        var existingDroppedFile = $(this).find(
            `div:contains('${droppedFile}')`
        );
        if (existingDroppedFile.length > 0) {
            console.log("File already dropped:", droppedFile);
            return; // Prevent duplicate file drops
        }

        // Add the dropped file to the drop zone
        var droppedItem = $("<div></div>").text(droppedFile).css({
            padding: "5px 10px",
            border: "1px solid #ccc",
            borderRadius: "5px",
            marginBottom: "10px",
            position: "relative",
        });

        // Create delete button
        var deleteButton = $("<button></button>")
            .text("Delete")
            .css({
                position: "absolute",
                right: "5px",
                top: "5px",
                background: "red",
                color: "white",
                border: "none",
                borderRadius: "3px",
                padding: "2px 5px",
                cursor: "pointer",
            })
            .data("file-name", droppedFile) // Store the file name in the button's data attributes
            .on("click", function () {
                var fileNameToRestore = $(this).data("file-name"); // Retrieve the file name when clicked
                console.log("Restoring file:", fileNameToRestore);

                // Restore file to the list
                var listItem = $("<li></li>")
                    .addClass("list-inline-item draggable-file")
                    .attr("draggable", true)
                    .attr("data-file-name", fileNameToRestore)
                    .text(fileNameToRestore)
                    .css({
                        marginRight: "15px",
                        padding: "5px 10px",
                        border: "1px solid #ccc",
                        borderRadius: "5px",
                        cursor: "grab",
                    });

                $("#fileList").append(listItem); // Restore file to the list
                droppedItem.remove(); // Remove from drop area

                setupDragAndDrop(); // Reinitialize drag events for restored files
            });

        droppedItem.append(deleteButton); // Add delete button to the dropped item
        $(this).append(droppedItem); // Add the dropped file inside the drop area
        $(this).removeClass("dragover"); // Remove highlight effect when dropped

        // Remove the dragged file from the file list
        $('li[data-file-name="' + droppedFile + '"]').remove();

        setupDragAndDrop(); // Reinitialize drag events for all files after changes
    });
    dropZone2.on("dragover", function (e) {
        e.preventDefault(); // Allow drop
        $(this).addClass("dragover"); // Optional: Add a highlight effect when dragging over
    });
    dropZone2.on("dragleave", function () {
        $(this).removeClass("dragover"); // Remove highlight effect when leaving
    });
    // Handle file drop
    dropZone2.on("drop", function (e) {
        e.preventDefault();
        var droppedFile = e.originalEvent.dataTransfer.getData("text/plain"); // Get the dragged file name

        if (!droppedFile) {
            console.log("No file data was dropped");
            return; // Exit if no valid file was dropped
        }

        console.log("File dropped:", droppedFile);

        // Check if the file already exists in the drop zone
        var existingDroppedFile = $(this).find(
            `div:contains('${droppedFile}')`
        );
        if (existingDroppedFile.length > 0) {
            console.log("File already dropped:", droppedFile);
            return; // Prevent duplicate file drops
        }

        // Add the dropped file to the drop zone
        var droppedItem = $("<div></div>").text(droppedFile).css({
            padding: "5px 10px",
            border: "1px solid #ccc",
            borderRadius: "5px",
            marginBottom: "10px",
            position: "relative",
        });

        // Create delete button
        var deleteButton = $("<button></button>")
            .text("Delete")
            .css({
                position: "absolute",
                right: "5px",
                top: "5px",
                background: "red",
                color: "white",
                border: "none",
                borderRadius: "3px",
                padding: "2px 5px",
                cursor: "pointer",
            })
            .data("file-name", droppedFile) // Store the file name in the button's data attributes
            .on("click", function () {
                var fileNameToRestore = $(this).data("file-name"); // Retrieve the file name when clicked
                console.log("Restoring file:", fileNameToRestore);

                // Restore file to the list
                var listItem = $("<li></li>")
                    .addClass("list-inline-item draggable-file")
                    .attr("draggable", true)
                    .attr("data-file-name", fileNameToRestore)
                    .text(fileNameToRestore)
                    .css({
                        marginRight: "15px",
                        padding: "5px 10px",
                        border: "1px solid #ccc",
                        borderRadius: "5px",
                        cursor: "grab",
                    });

                $("#fileList").append(listItem); // Restore file to the list
                droppedItem.remove(); // Remove from drop area

                setupDragAndDrop(); // Reinitialize drag events for restored files
            });

        droppedItem.append(deleteButton); // Add delete button to the dropped item
        $(this).append(droppedItem); // Add the dropped file inside the drop area
        $(this).removeClass("dragover"); // Remove highlight effect when dropped

        // Remove the dragged file from the file list
        $('li[data-file-name="' + droppedFile + '"]').remove();

        setupDragAndDrop(); // Reinitialize drag events for all files after changes
    });
}
function getDroppedFiles() {
    var inFolderFiles = [];
    var instructionsFolderFiles = [];
    var referenceFolderFiles = [];

    // For the In Folder
    $("#drag-drop-area div").each(function () {
        var fileName = $(this).contents().get(0).nodeValue.trim(); // Get only the file name, excluding the delete button
        if (fileName) {
            inFolderFiles.push(fileName);
        }
    });

    // For the Instructions Folder
    $("#drag-drop-area1 div").each(function () {
        var fileName = $(this).contents().get(0).nodeValue.trim(); // Get only the file name, excluding the delete button
        if (fileName) {
            instructionsFolderFiles.push(fileName);
        }
    });

    // For the Reference Folder
    $("#drag-drop-area2 div").each(function () {
        var fileName = $(this).contents().get(0).nodeValue.trim(); // Get only the file name, excluding the delete button
        if (fileName) {
            referenceFolderFiles.push(fileName);
        }
    });

    return {
        inFolder: inFolderFiles,
        instructionsFolder: instructionsFolderFiles,
        referenceFolder: referenceFolderFiles,
    };
}
function fetchFilesFromTP(mailIdTP, mailId, jobData) {
    $.ajax({
        url: "/fetch-files-tp/" + mailIdTP,
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            console.log(response);
            fetchFiles(mailId, jobData);
        },
        error: function (xhr, status, error) {
            console.error("Error fetching files:", error);
        },
    });
}
