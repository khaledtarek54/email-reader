@extends('layouts.dashbordlayout')
@section('content')
    <nav class="navbar">
        <div class="navbar-brand">
            <i class="fas fa-bars" id="menu-toggle"></i> Email
        </div>

    </nav>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if ($errors->has('error'))
        <div class="alert alert-danger">
            {{ $errors->first('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif
    <div id="loadingOverlay" style="display:none;">
        <div id="loadingSpinner"></div>
    </div>
    <!-- Email Header (Gmail-style) -->
    <div class="email-header container">
        <div class="d-flex align-items-center">
            <div class="avatar" style="background-color: #fbbc05;">{{ strtoupper(substr($mail->from, 0, 1)) }}</div>
            <div>
                <div class="sender">{{ $mail->from }}</div>
                <div class="email-time">{{ \Carbon\Carbon::parse($mail->mail_datetime)->format('Y-m-d H:i:s') }}</div>
            </div>
        </div>
        <div class="subject">{{ $mail->subject }}</div>
        <div id="fileList" style="display: flex; gap: 15px;"></div>

    </div>

    <!-- Email Body (Gmail-style) -->
    <div class="email-body container">
        {!! $mail->html_body !!}
    </div>

    <!-- Email Action Buttons (Gmail-style) -->
    <div class="email-actions">
        <form action="{{ route('jobdata', ['id' => $mail->id]) }}" method="POST" style="display: inline;"
            onsubmit="return checkMailStatus();">
            @csrf
            <button type="submit"><i class="fas fa-cog"></i> Job data</button>
        </form>
        @if ($mail->trash)
            <form action="{{ route('mail.recover', ['id' => $mail->id]) }}" method="POST">
                @csrf
                <button type="submit">
                    <i class="fas fa-undo"></i> Recover
                </button>
            </form>
        @else
            <form action="{{ route('mail.trash', ['id' => $mail->id]) }}" method="POST">
                @csrf
                <button type="submit">
                    <i class="fas fa-trash-alt"></i> Trash
                </button>
            </form>
        @endif
    </div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var mailId = <?= json_encode($mail->id) ?>; // Assuming mail->id is numeric
        var mailIdTP = <?= json_encode($mail->mail_id) ?>; // Wrap in json_encode to handle quotes
        fetchFilesForMail(mailIdTP);


    });

    function checkMailStatus() {
        // If the mail is marked as trash, prevent form submission
        var mailTrashed = <?= json_encode($mail->trash) ?>;
        if (mailTrashed) {
            alert('This mail is in the trash, please recover to access job data.');
            return false; // Prevent form submission
        }
        return true; // Allow form submission
    }
</script>
