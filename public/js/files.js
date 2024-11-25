const folders = [
    {
        name: "In folder",
        div: "drag-drop-area",
    },
    {
        name: "Instructions folder",
        div: "drag-drop-area1",
    },
    {
        name: "Reference folder",
        div: "drag-drop-area2",
    },
];

function fetchFiles(mailId, jobData) {
    $.ajax({
        url: "/fetch-files/" + mailId,
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            displayFiles(response);
            if (!response.length == 0) {
                assignFilesToFolders(jobData, response);
                $("#online_source_files").prop("checked", false);
            } else {
                $("#online_source_files").prop("checked", true);
            }
        },
        error: function (xhr, status, error) {
            console.error("Error fetching files:", error);
        },
    });
}
function assignFilesToFolders(jobData, files) {
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

    function isFileInArray(fileName, filesArray) {
        return filesArray.some((file) => file.file_name === fileName);
    }

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
    function addFilesToDropZone(zone, files, allfiles) {
        files.forEach(function (file) {
            // Check if the file is already in the drop zone
            if (
                inFolderFiles.includes(file) ||
                instructionsFolderFiles.includes(file) ||
                referenceFolderFiles.includes(file)
            ) {
                fileFound = isFileInArray(file, allfiles);
                if (fileFound) {
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
                        cursor: "pointer",
                    });

                    // Create delete button
                    var deleteButton = $("<button></button>")
                        .text("X")
                        .addClass("delete-button")
                        .data("file-name", file)
                        .on("click", function () {
                            var listItem = $("<li></li>")
                                .addClass("list-inline-item")
                                .attr("data-file-name", file) // Store file name in a data attribute
                                .css({
                                    marginRight: "15px",
                                    padding: "5px 10px",
                                    border: "1px solid #ccc",
                                    borderRadius: "5px",
                                    position: "relative",
                                    cursor: "pointer",
                                });

                            // Display the file name
                            var fileNameText = $("<span></span>").text(file);

                            var menuButton = $("<button>&#8942;</button>")
                                .css({
                                    background: "none",
                                    border: "none",
                                    cursor: "pointer",
                                    fontSize: "16px",
                                    padding: "0 5px",
                                    color: "#555",
                                    marginLeft: "10px",
                                })
                                .on("click", function (event) {
                                    event.stopPropagation();
                                    toggleMenu(listItem);
                                });

                            var folderMenu =
                                $("<ul></ul>").addClass("folder-menu");

                            $.each(folders, function (index, folder) {
                                var folderOption = $("<li></li>")
                                    .text(folder.name)
                                    .css({
                                        padding: "5px 10px",
                                        cursor: "pointer",
                                    })
                                    .on("click", function () {
                                        transferFileToFolder(file, folder.name);
                                        folderMenu.hide();
                                    });
                                folderMenu.append(folderOption);
                            });
                            listItem.on("click", function (event) {
                                event.stopPropagation();
                                folderMenu.toggle();
                            });

                            listItem
                                .append(fileNameText)
                                .append(menuButton)
                                .append(folderMenu);
                            $("#fileList").append(listItem); // Add back to file list
                            droppedItem.remove(); // Remove from folder area
                        });

                    droppedItem.append(deleteButton);
                    $("#fileList")
                        .find(`li[data-file-name="${file}"]`)
                        .remove();
                    zone.append(droppedItem);
                }
            }
        });
    }

    // Add files to their respective zones
    addFilesToDropZone(inFolderZone, inFolderFiles, files);
    addFilesToDropZone(instructionsFolderZone, instructionsFolderFiles, files);
    addFilesToDropZone(referenceFolderZone, referenceFolderFiles, files);
}

function displayFiles(files) {
    $("#fileList").empty(); // Clear the existing list

    $.each(files, function (index, file) {
        var listItem = $("<li></li>")
            .addClass("list-inline-item")
            .attr("data-file-name", file.file_name) // Store file name in a data attribute
            .css({
                marginRight: "15px",
                padding: "5px 10px",
                border: "1px solid #ccc",
                borderRadius: "5px",
                position: "relative",
                cursor: "pointer",
            });

        // Display the file name
        var fileNameText = $("<span></span>").text(file.file_name);

        // Create the three dots menu button
        var menuButton = $("<button>&#8942;</button>")
            .css({
                background: "none",
                border: "none",
                cursor: "pointer",
                fontSize: "16px",
                padding: "0 5px",
                color: "#555",
                marginLeft: "10px",
            })
            .on("click", function (event) {
                event.stopPropagation();
                toggleMenu(listItem);
            });

        // Create a folder options menu
        var folderMenu = $("<ul></ul>").addClass("folder-menu");

        // Populate the folder options menu
        $.each(folders, function (index, folder) {
            var folderOption = $("<li></li>")
                .text(folder.name)
                .css({
                    padding: "5px 10px",
                    cursor: "pointer",
                })
                .on("click", function () {
                    transferFileToFolder(file.file_name, folder.name);
                    folderMenu.hide(); // Hide menu after selection
                });
            folderMenu.append(folderOption);
        });
        listItem.on("click", function (event) {
            event.stopPropagation();
            folderMenu.toggle();
        });

        listItem.append(fileNameText).append(menuButton).append(folderMenu);
        $("#fileList").append(listItem);
    });

    $(document).on("click", function () {
        $(".folder-menu").hide();
    });
}

function transferFileToFolder(fileName, folderName) {
    const folder = folders.find((f) => f.name === folderName);
    if (!folder) {
        console.error(`Folder "${folderName}" not found.`);
        return;
    }

    // Create the dropped item with delete button
    var droppedItem = $("<div></div>").text(fileName).css({
        padding: "5px 10px",
        border: "1px solid #ccc",
        borderRadius: "5px",
        marginBottom: "10px",
        position: "relative",
        cursor: "pointer",
    });

    // Create delete button
    var deleteButton = $("<button></button>")
        .text("X")
        .addClass("delete-button")
        .data("file-name", fileName)
        .on("click", function () {
            var listItem = $("<li></li>")
                .addClass("list-inline-item")
                .attr("data-file-name", fileName) // Store file name in a data attribute
                .css({
                    marginRight: "15px",
                    padding: "5px 10px",
                    border: "1px solid #ccc",
                    borderRadius: "5px",
                    position: "relative",
                    cursor: "pointer",
                });

            // Display the file name
            var fileNameText = $("<span></span>").text(fileName);

            var menuButton = $("<button>&#8942;</button>")
                .css({
                    background: "none",
                    border: "none",
                    cursor: "pointer",
                    fontSize: "16px",
                    padding: "0 5px",
                    color: "#555",
                    marginLeft: "10px",
                })
                .on("click", function (event) {
                    event.stopPropagation();
                    toggleMenu(listItem);
                });

            var folderMenu = $("<ul></ul>").addClass("folder-menu");

            $.each(folders, function (index, folder) {
                var folderOption = $("<li></li>")
                    .text(folder.name)
                    .css({
                        padding: "5px 10px",
                        cursor: "pointer",
                    })
                    .on("click", function () {
                        transferFileToFolder(fileName, folder.name);
                        folderMenu.hide();
                    });
                folderMenu.append(folderOption);
            });
            listItem.on("click", function (event) {
                event.stopPropagation();
                folderMenu.toggle();
            });

            listItem.append(fileNameText).append(menuButton).append(folderMenu);
            $("#fileList").append(listItem); // Add back to file list
            droppedItem.remove(); // Remove from folder area
        });

    droppedItem.append(deleteButton);

    $("#fileList").find(`li[data-file-name="${fileName}"]`).remove();
    $(`#${folder.div}`).append(droppedItem);
}

function toggleMenu(listItem) {
    $(".folder-menu").hide(); // Hide other open menus
    listItem.find(".folder-menu").toggle(); // Toggle the current menu
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

function mapUploadedFiles() {
    let fileInput = document.getElementById("file");
    const files = fileInput.files;
    // Add files to the list and make them draggable
    $.each(files, function (index, file) {
        let fileName = file.name;
        var listItem = $("<li></li>")
            .addClass("list-inline-item")
            .attr("data-file-name", fileName) // Store file name in a data attribute
            .css({
                marginRight: "15px",
                padding: "5px 10px",
                border: "1px solid #ccc",
                borderRadius: "5px",
                position: "relative",
                cursor: "pointer",
            });

        // Display the file name
        var fileNameText = $("<span></span>").text(fileName);

        // Create the three dots menu button
        var menuButton = $("<button>&#8942;</button>")
            .css({
                background: "none",
                border: "none",
                cursor: "pointer",
                fontSize: "16px",
                padding: "0 5px",
                color: "#555",
                marginLeft: "10px",
            })
            .on("click", function (event) {
                event.stopPropagation();
                toggleMenu(listItem);
            });

        // Create a folder options menu
        var folderMenu = $("<ul></ul>").addClass("folder-menu");

        // Populate the folder options menu
        $.each(folders, function (index, folder) {
            var folderOption = $("<li></li>")
                .text(folder.name)
                .css({
                    padding: "5px 10px",
                    cursor: "pointer",
                })
                .on("click", function () {
                    transferFileToFolder(fileName, folder.name);
                    folderMenu.hide(); // Hide menu after selection
                });
            folderMenu.append(folderOption);
        });
        listItem.on("click", function (event) {
            event.stopPropagation();
            folderMenu.toggle();
        });

        listItem.append(fileNameText).append(menuButton).append(folderMenu);
        $("#fileList").append(listItem);
    });

    $(document).on("click", function () {
        $(".folder-menu").hide();
    });
}
function fetchFilesFromTP(mailIdTP, mailId, jobData) {
    $.ajax({
        url: "/fetch-files-tp/" + mailIdTP,
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            fetchFiles(mailId, jobData);
        },
        error: function (xhr, status, error) {
            console.error("Error fetching files:", error);
        },
    });
}


