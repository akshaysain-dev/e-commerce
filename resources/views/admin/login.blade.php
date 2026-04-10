@extends('layouts.backend')

@section('title', 'Login Admin')

@section('styles')
<style>
    body{
        padding-left: 0px !important;
    }
</style>
@endsection

@section('content')

<div class="container">
    <div class="row justify-content-center mt-5 mb-3">

        <div class="col-md-4">

            <div class="card shadow">

                <div class="card-header text-center">
                    <h4>Admin Login</h4>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card-body">

                    <form action="{{ url('/admin/login') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Login
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>
</div>

@endsection