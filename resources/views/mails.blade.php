@extends('layouts.dashbordlayout')
@section('content')



<nav class="navbar">
    <div class="navbar-brand">
        <i class="fas fa-bars" id="menu-toggle"></i> Inbox
    </div>
    <a href="{{ route('refresh-mails') }}" style="text-decoration: none; color: inherit;">
        <i class="fas fa-sync-alt fa-2x" style="color: white; float: right; margin-top: -5px;" aria-hidden="true"></i>
    </a>

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

<ul class="container email-list">
    @foreach ($mails as $mail)
    <a href="{{ route('mailview', ['id' => $mail->id]) }}" style="text-decoration: none; color: inherit;">
        <li class="email-item">
            <div class="email-left">
                <div class="avatar" style="background-color: #fbbc05;">{{ strtoupper(substr($mail->from, 0, 1)) }}</div> <!-- Display first letter of sender -->
                <div class="email-info">
                    <div class="sender">{{ $mail->from }}</div>
                    <div class="subject">{{ $mail->subject." "."....."}}</div>
                    <div class="snippet">{{strip_tags($mail->html_body)." "."....." }}</div>
                </div>
            </div>
            <div class="email-right">
                <div class="email-time">
                    {{ \Carbon\Carbon::parse($mail->mail_datetime)->isToday() ? 
                            \Carbon\Carbon::parse($mail->mail_datetime)->format('g:i A') : 
                            \Carbon\Carbon::parse($mail->mail_datetime)->format('M d, Y') 
                        }}
                </div>
            </div>
        </li>
    </a>
    @endforeach
</ul>

<!-- Render pagination links -->
<div class="d-flex justify-content-end">
    {{ $mails->links('pagination::bootstrap-4') }}
</div>


@endsection