<!-- resources/views/upload_form.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Form</title>
</head>
<body>
    <h1>Upload File</h1>
    <form action="{{ route('upload.file') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="email_id">Email ID:</label>
        <input type="text" name="email_id" id="email_id" required>
        <br>

        <label for="file">Select file:</label>
        <input type="file" name="file" id="file" required>
        <br>

        <button type="submit">Upload</button>
    </form>
</body>
</html>
