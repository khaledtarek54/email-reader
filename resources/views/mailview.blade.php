@extends('layouts.dashbordlayout')
@section('content')

<!-- Navbar (Gmail-style) -->
<nav class="navbar">
    <div class="navbar-brand">
        <i class="fas fa-bars" id="menu-toggle"></i> Email
    </div>

</nav>

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
<!-- Email Header (Gmail-style) -->
<div class="email-header container">
    <div class="d-flex align-items-center">
        <div class="avatar" style="background-color: #fbbc05;">{{ strtoupper(substr($mail->from, 0, 1)) }}</div>
        <div>
            <div class="sender">{{$mail->from}}</div>
            <div class="email-time">{{ \Carbon\Carbon::parse($mail->mail_datetime)->format('Y-m-d H:i:s') }}</div>
        </div>
    </div>
    <div class="subject">{{$mail->subject}}</div>
</div>

<!-- Email Body (Gmail-style) -->
<div class="email-body container">
    {!! $mail->html_body !!}
</div>

<!-- Email Action Buttons (Gmail-style) -->
<div class="email-actions">
    <button><i class="fas fa-envelope"></i> Original Mail</button>
    <form action="{{ route('jobdata', ['id' => $mail->id]) }}" method="POST" style="display: inline;">
        @csrf <!-- This is required for POST requests to protect against CSRF attacks -->
        <button type="submit"><i class="fas fa-film"></i> Job data</button>
    </form>
    @if (!$mail)
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
            <i class="fas fa-archive"></i> Trash
        </button>
    </form>
    @endif
</div>
@endsection