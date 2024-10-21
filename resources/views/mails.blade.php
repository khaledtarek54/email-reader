@extends('layouts.dashbordlayout')
@section('content')


<!-- Navbar (Gmail-style) -->
<nav class="navbar">
    <div class="navbar-brand">
        <i class="fas fa-bars" id="menu-toggle"></i> Mail Reader
    </div>
    <i class="fas fa-search" style="color: white; float: right; margin-top: -25px;"></i>
</nav>
<!-- Email list (Gmail-style) -->
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
<div class="pagination-container">
    {{ $mails->links('pagination::bootstrap-4') }}
</div>


@endsection