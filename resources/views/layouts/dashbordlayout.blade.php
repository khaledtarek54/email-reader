<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loodz el gen</title>
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Include Bootstrap CSS (Ensure this is loaded first) -->

    <link rel="stylesheet" href="{{ asset('css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('css/select2.css') }}" class="template-customizer-select2-css"/>
    <!-- Include Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/DateTimePicker.css') }}" />

    
</head>

<body>

    <!-- Overlay for sidebar -->
    <div class="overlay" id="overlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a href="/mails"><i class="fas fa-inbox"></i> Inbox</a>
        <a href="/trash"><i class="fas fa-trash-alt"></i> Trash</a>
        <!-- <a href="#"><i class="fas fa-paperclip"></i> Attachment</a> -->
        <a href="#" class="logout-link"
            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-lock"></i> Logout
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>


    </div>
    <!-- content -->
    @yield('content')
    <!-- endcontent -->

    <!-- Floating action button (FAB) -->
    <!-- <div class="fab">
        <i class="fas fa-pencil-alt"></i>
    </div> -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script src="{{ asset('js/autoplan.js') }}"></script>
    <script src="{{ asset('js/job.js') }}"></script>
    <script src="{{ asset('js/files.js') }}"></script>
    <script src="{{ asset('js/mail.js') }}"></script>
    <script  src="{{ asset('js/DateTimePicker.js') }}"></script>

    <!-- Include Select2 JS -->
    
    

</body>

</html>
