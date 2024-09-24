@extends('layouts.main')
@section('title','User Management - Saraswati Globals')
@section('content')
    <main id="main" class="main">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif
        <div class="dashboard-header pagetitle">
            <h1>Update User Management</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item"> User Management</li>
                    <li class="breadcrumb-item active">Update</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Update User Management</h4>

                            <!-- Multi Columns Form -->
                            <form class="row g-3" action="{{ route('users.update', $user->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="col-md-6">
                                    <label for="name"><strong>Name <span
                                                class="required-classes">*</span></strong></label>
                                    <input type="text" name="name" class="form-control" id="inputName5"
                                        value="{{ $user->name }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="name"><strong>Email <span
                                                class="required-classes">*</span></strong></label>
                                    <input type="text" name="email" class="form-control" id="inputName5"
                                        value="{{ $user->email }}">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="name"><strong>Password <span
                                                class="required-classes">*</span></strong></label>
                                    <input type="password" name="password" class="form-control" id="inputName5"
                                        value="">
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="name"><strong>Confirm Password <span
                                                class="required-classes">*</span></strong></label>
                                    <input type="password" name="confirm-password" class="form-control" id="inputName5"
                                        value="{{ $user->confirm }}">
                                    @error('confirm-password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="roles[]"><strong>Role <span
                                                        class="required-classes">*</span></strong></label>
                                            <select name="roles[]" class="form-control" multiple required>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}"
                                                        @if (in_array($role->id, $userRoles)) selected @endif>
                                                        {{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('roles[]')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="text-end">
                                    <a type="button" href="{{ route('users.index') }}"
                                        class="btn btn-secondary">Cancel</a>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form><!-- End Multi Columns Form -->

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->
@endsection
