@extends('layouts.master')

@section('content')
    <div class="container-fluid">
        <div class="row justify-content-center align-items-center" style="height: 100vh">
            <div class="col-md-4">
                <h4>Welcome, {{ auth()->user()->name }}</h4>

                <form method="post" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary w-100">Log Out</button>
                </form>
            </div>
        </div>
    </div>
@endsection
