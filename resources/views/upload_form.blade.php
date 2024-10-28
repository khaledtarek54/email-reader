<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Form</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>
    <h1>Upload File</h1>
    <form id="uploadForm" enctype="multipart/form-data">
        <label for="email_id">Email ID:</label>
        <input type="text" name="email_id" id="email_id" required>
        <br>

        <label for="file">Select files:</label>
        <input type="file" name="file[]" id="file" multiple required> <!-- Allow multiple files -->
        <br>
    </form>

    <script>
        $(document).ready(function () {
            // Set CSRF token in the headers for AJAX request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Trigger AJAX upload on file selection
            $('#file').on('change', function () {
                let formData = new FormData($('#uploadForm')[0]);
                $.ajax({
                    url: "{{ route('upload.file') }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        alert("Files uploaded successfully!");
                    },
                    error: function (xhr, status, error) {
                        alert("Upload failed. Please try again.");
                    }
                });
            });
        });
    </script>
</body>
</html>
