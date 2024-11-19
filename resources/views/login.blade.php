@extends('layouts.dashbordlayout')
@section('content')
<!-- Login Form -->
<div class="login-container">
    <h2>Login</h2>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('login') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
        </div>
        <div class="mb-3">
            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
        </div>
        <input type="hidden"  id="userTimeZone" name="userTimeZone">
        <button type="submit" class="btn btn-login">Login</button>
    </form>
</div>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#userTimeZone').val(getUserTimezoneOffset());
    });
</script>