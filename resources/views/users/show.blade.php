@extends('layouts.main')
@section('title','User Management - Saraswati Globals')
@section('content')
    <main id="main" class="main">
        <div class="dashboard-header pagetitle">
            <h1>User Details</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User Management</a></li>
                    <li class="breadcrumb-item active">Details</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <h4 class="card-title">Show Users</h4>
                                </div>
                                <div class="col-lg-6 d-flex justify-content-end ">
                                    <a class="btn btn-primary mb-4 mr-3 "href="{{ route('users.index') }}">Back</a>
                                </div>
                            </div>
                            <!-- Multi Columns Form -->
                            <div class="col-md-4">
                                <label for="name"><strong>Name : </strong> {{ $user->name }}</label>
                            </div>
                            <div class="col-md-4">
                                <label for="name"><strong>Email : </strong> {{ $user->email }}</label>
                            </div>
                            <div class="col-md-4">
                                <label for="name"><strong>Role : </strong>
                                    @if (!empty($user->getRoleNames()))
                                        @foreach ($user->getRoleNames() as $v)
                                            <label class="badge bg-success">{{ $v }}</label>
                                        @endforeach
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->
@endsection
