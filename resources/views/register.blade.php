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
                        <form method="post" action="{{ route('register.submit') }}">
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

                            <div class="mb-3">
                                <label class="form-label">Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Your Name" value="{{ old('name') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Gender</label>
                                <select class="form-select" name="gender" required>
                                    <option value="M" {{ old('gender', '') == 'M' ? 'selected' : '' }}>Male</option>
                                    <option value="F" {{ old('gender', '') == 'F' ? 'selected' : '' }}>Female</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Birth Date</label>
                                <input type="text" class="form-control" id="birth_date" name="birth_date" placeholder="Your Birth Date" value="{{ old('birth_date') }}" required readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" placeholder="Your Email" value="{{ old('email') }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control" name="password" placeholder="Your Password" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Your Password" required>
                            </div>

                            <button type="submit" class="btn btn-dark w-100 mb-3">Register</button>
                            <a href="{{ route('login') }}" class="btn btn-secondary w-100">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("#birth_date", {
            dateFormat: "Y-m-d",
        });
    </script>
@endsection
