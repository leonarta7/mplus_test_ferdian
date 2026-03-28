@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center" style="height: 100vh">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Login</h5>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('login.submit') }}">
                            @csrf

                            @if(count($errors) > 0)
                                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                                    <ul class="m-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                                    {{ session()->get('success') }}
                                </div>
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Your Email" value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Your Password" required>
                            </div>

                            <div class="row align-items-center mb-3">
                                <div class="col">
                                    <button type="submit" class="btn btn-dark w-100">Login</button>
                                </div>
                                <div class="col-auto">
                                    <span>Or</span>
                                </div>
                                <div class="col">
                                    <a href="{{ route('register') }}" class="btn btn-secondary w-100">Register</a>
                                </div>
                            </div>
                        </form>

                        <form method="post" action="{{ route('login.third_party') }}">
                            @csrf
                            <input type="hidden" name="type" value="google">
                            <button type="submit" class="btn btn-danger w-100 mb-3"><i class="bi bi-google"></i> Login with Google</button>
                        </form>
                        <form method="post" action="{{ route('login.third_party') }}">
                            @csrf
                            <input type="hidden" name="type" value="facebook">
                            <button class="btn btn-primary w-100 mb-3"><i class="bi bi-facebook"></i> Login with Facebook</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
