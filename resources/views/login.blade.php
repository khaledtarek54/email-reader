@extends('layouts.dashbordlayout')
@section('content')
<!-- Login Form -->
<div class="login-container">
    <h2>Login</h2>
    <form method="POST" action="{{ route('login') }}" >
        @csrf
        <div class="mb-3">
            <input type="text" class="form-control" id="username" placeholder="User Name" required>
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" id="password" placeholder="Password" required>
        </div>
        <button type="submit" class="btn btn-login">Login</button>
    </form>
</div>
@endsection