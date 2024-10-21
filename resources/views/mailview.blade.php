@extends('layouts.dashbordlayout')
@section('content')

<!-- Navbar (Gmail-style) -->
<nav class="navbar">
    <div class="navbar-brand">
        <i class="fas fa-bars" id="menu-toggle"></i> Email
    </div>
    <i class="fas fa-search" style="color: white; float: right; margin-top: -25px;"></i>
</nav>
<!-- Email Header (Gmail-style) -->
<div class="email-header container">
    <div class="d-flex align-items-center">
        <div class="avatar" style="background-color: #fbbc05;">{{ strtoupper(substr($mail->from, 0, 1)) }}</div>
        <div>
            <div class="sender">{{$mail->from}}</div>
            <div class="email-time">{{  \Carbon\Carbon::parse($mail->mail_datetime)->format('Y-m-d H:i:s') }}</div>
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
    <a href="/jobdata"><button><i class="fas fa-film"></i> Job data</button></a>
    <button><i class="fas fa-archive"></i> Archive</button>
</div>
@endsection