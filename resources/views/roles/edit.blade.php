@extends('layouts.main')
@section('title','Access Management - Saraswati Globals')
@section('content')
    <main id="main" class="main">
        <div class="dashboard-header pagetitle">
            <h1>Update Role Management</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item"> Role Management</li>
                    <li class="breadcrumb-item active">Edit</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Update New Role</h4>

                            <!-- Multi Columns Form -->
                            <form class="row g-3" action="{{ route('roles.update', $role->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="col-md-4">
                                    <label for="name"><strong>Name <span
                                                class="required-classes">*</span></strong></label>
                                    <input type="text" name="name" class="form-control" id="inputName5"
                                        value="{{ $role->name }}" >
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="permission"><strong>Permission<span
                                                class="required-classes">*</span></strong>:</strong></label>
                                        <br />
                                        @foreach ($permission as $value)
                                            <label>
                                                <input type="checkbox" name="permission[]" value="{{ $value->name }}"
                                                    class="name"{{ in_array($value->id, $rolePermissions) ? 'checked' : '' }}>
                                                {{ $value->name }}
                                            </label>
                                            <br />
                                        @endforeach
                                    </div>
                                </div>

                                <div class="text-end">
                                    <a type="button" href="{{route('roles.index')}}" class="btn btn-secondary">Cancel</a>
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
