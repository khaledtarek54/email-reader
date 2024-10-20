@extends('layouts.dashbordlayout')
@section('content')
<!-- Login Form -->
<div class="login-container">
    <h2>Login</h2>
    <form>
        <div class="mb-3">
            <input type="email" class="form-control" id="user_name" placeholder="User">
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" id="password" placeholder="Password">
        </div>
        <button type="submit" class="btn btn-login">Login</button>
    </form>
    <p class="form-text">Don't have an account? <a href="#">Sign up</a></p>
</div>
@endsection