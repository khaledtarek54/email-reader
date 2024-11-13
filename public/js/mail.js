function fetchFilesForMail(mailIdTP) {
    $("#loadingOverlay").show();
    $.ajax({
        url: "/fetch-mail-files/" + mailIdTP,
        type: "POST",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            const fileListContainer = $("#fileList");
            fileListContainer.empty();
            if (!Array.isArray(response.message)) {
                $("#loadingOverlay").hide();
            } else if (response.message && response.message.length != 0) {
                response.message.forEach(function (filename) {
                    const fileItem = $("<div></div>").css({
                        display: "flex",
                        alignItems: "center",
                        padding: "5px",
                        border: "1px solid #ccc",
                        borderRadius: "5px",
                        minWidth: "150px",
                        textAlign: "center",
                        gap: "10px",
                    });
                    const fileIcon = $("<span>&#128196;</span>").css({
                        fontSize: "20px",
                        color: "#555",
                    });
                    const fileNameText = $("<span></span>").text(filename).css({
                        overflow: "hidden",
                        whiteSpace: "nowrap",
                        textOverflow: "ellipsis",
                    });
                    fileItem.append(fileIcon, fileNameText);
                    fileListContainer.append(fileItem);
                });
            }
            $("#loadingOverlay").hide();
        },
        error: function () {
            $("#fileList").append("<p>Failed to load files.</p>");
            $("#loadingOverlay").hide();
        },
    });
}
