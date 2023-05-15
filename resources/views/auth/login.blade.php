@extends('layout')
@section('content')
<main class="login-form">
    <div class="cotainer mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                @if(session('login_error'))
                    <div class="d-flex justify-content-center">
                        <span class="text-danger mb-3">{{ session('login_error') }}</span>
                    </div>
                @endif
                <div class="card g-form">
                    <h3 class="card-header text-center">Login</h3>
                    <div class="card-body">
                        <form method="POST" class="text-dark" action="{{ route('login.custom') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <input type="text" placeholder="Email" id="email" class="form-control" name="email" required
                                    autofocus>
                                @if ($errors->has('email'))
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <input type="password" placeholder="Password" id="password" class="form-control" name="password" required>
                                @if ($errors->has('password'))
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                @endif
                            </div>
                            <div class="form-group mb-3">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember_me"> Remember Me
                                    </label>
                                </div>
                            </div>
                            <div class="d-grid mx-auto">
                                <button type="submit" class="btn btn-dark btn-block">Login</button>
                            </div>
                        </form>
                        <div class="mt-3">
                            <span>Don't have an account?</span>
                            <a href="{{ route('register-user') }}">Register</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection