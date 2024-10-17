@extends('layouts.dashbordlayout')
@section('content')
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
@endsection