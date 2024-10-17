<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Listing - Gmail Style</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/core.css') }}" class="template-customizer-core-css" />
</head>
<body>


<!-- Navbar (Gmail-style) -->
<nav class="navbar">
    <div class="navbar-brand">
        <i class="fas fa-bars" id="menu-toggle"></i> Mail Reader
    </div>
    <i class="fas fa-search" style="color: white; float: right; margin-top: -25px;"></i>
</nav>
<!-- Overlay for sidebar -->
<div class="overlay" id="overlay"></div>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <a href="#"><i class="fas fa-inbox"></i> Inbox</a>
    <a href="#"><i class="fas fa-trash-alt"></i> Trash</a>
    <a href="#"><i class="fas fa-paperclip"></i> Attachment</a>
</div>
<!-- content -->
@yield('content')
<!-- endcontent -->

<!-- Floating action button (FAB) -->
<div class="fab">
    <i class="fas fa-pencil-alt"></i>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/sidebar.js') }}"></script>

</body>
</html>
