<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Preview - Gmail Style</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/core.css') }}" class="template-customizer-core-css" />
</head>
<body>

<!-- Navbar (Gmail-style) -->
<nav class="navbar">
    <div class="navbar-brand">
        <i class="fas fa-bars" id="menu-toggle"></i> Email
    </div>
</nav>
<!-- Overlay for sidebar -->
<div class="overlay" id="overlay"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <a href="/mails"><i class="fas fa-inbox"></i> Inbox</a>
    <a href="#"><i class="fas fa-trash-alt"></i> Trash</a>
    <a href="#"><i class="fas fa-paperclip"></i> Attachment</a>
</div>

<!-- Email Header (Gmail-style) -->
<div class="email-header container">
    <div class="d-flex align-items-center">
        <div class="avatar" style="background-color: #fbbc05;">G</div>
        <div>
            <div class="sender">Google</div>
            <div class="email-time">3:35 PM (20 minutes ago)</div>
        </div>
    </div>
    <div class="subject">New sign-in from Samsung Galaxy S5</div>
</div>

<!-- Email Body (Gmail-style) -->
<div class="email-body container">
    Hi there,

    We noticed a new sign-in to your Google account from a Samsung Galaxy S5. If this was you, you don’t need to do anything. If not, we recommend securing your account.

    Device: Samsung Galaxy S5
    Time: 3:35 PM
    Location: Chicago, IL

    If you did not initiate this, please change your password and review recent activity on your account.

    Thanks,
    The Google Team
</div>

<!-- Email Action Buttons (Gmail-style) -->
<div class="email-actions">
    <button><i class="fas fa-envelope"></i> Original Mail</button>
    <a href="/jobdata"><button><i class="fas fa-film"></i> Job data</button></a>
    <button><i class="fas fa-archive"></i> Archive</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/sidebar.js') }}"></script>
</body>
</html>
