@extends('layouts.dashbordlayout')
@section('content')
<!-- Email list (Gmail-style) -->
<ul class="container email-list">
    <a href="/mailview" style="text-decoration: none; color: inherit;">
    <li class="email-item">
        <div class="email-left">
            <div class="avatar" style="background-color: #fbbc05;">G</div>
            <div class="email-info">
                <div class="sender">Google</div>
                <div class="subject">New sign-in from Samsung Galaxy S5</div>
                <div class="snippet">New sign-in from Samsung Galaxy S5...</div>
            </div>
        </div>
        <div class="email-right">
            <div class="email-time">3:35 PM</div>
            <i class="fas fa-star email-star"></i>
        </div>
    </li>
    </a>

    <li class="email-item">
        <div class="email-left">
            <div class="avatar" style="background-color: #34a853;"><i class="fas fa-users"></i></div>
            <div class="email-info">
                <div class="sender">Social <span class="new-badge">1 new</span></div>
                <div class="subject">Deborah Montague</div>
                <div class="snippet">Hey, I just wanted to reach out...</div>
            </div>
        </div>
        <div class="email-right">
            <div class="email-time">Yesterday</div>
            <i class="fas fa-star email-star"></i>
        </div>
    </li>

    <li class="email-item">
        <div class="email-left">
            <div class="avatar" style="background-color: #4285f4;">O</div>
            <div class="email-info">
                <div class="sender">Olenna Mason</div>
                <div class="subject">Hey girl!</div>
                <div class="snippet">Hope you're doing well...</div>
            </div>
        </div>
        <div class="email-right">
            <div class="email-time">Jun 24</div>
            <i class="fas fa-star email-star"></i>
        </div>
    </li>
    
    <li class="email-item">
        <div class="email-left">
            <div class="avatar" style="background-color: #ea4335;">G</div>
            <div class="email-info">
                <div class="sender">Grace Ellington</div>
                <div class="subject">Volunteer Opportunity</div>
                <div class="snippet">I would like to inform you about a...</div>
            </div>
        </div>
        <div class="email-right">
            <div class="email-time">Jun 21</div>
            <i class="fas fa-star email-star"></i>
        </div>
    </li>
</ul>
@endsection
