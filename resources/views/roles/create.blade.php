@extends('layouts.main')
@section('title','Access Management - Saraswati Globals')
@section('content')
    <main id="main" class="main">
        <div class="dashboard-header pagetitle">
            <h1>Add New Role</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item"> Role Management</li>
                    <li class="breadcrumb-item active">Add</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Create New Role</h4>

                            <!-- Multi Columns Form -->
                            <form class="row g-3" action="{{ route('roles.store') }}" method="POST">
                                @csrf
                                <div class="col-md-4">
                                    <label for="name"><strong>Name <span class="required-classes">*</span></strong></label>
                                    <input type="text" name="name" class="form-control" id="name-input" >
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="permission"><strong>Permission<span
                                                class="required-classes">*</span></strong>:</strong></label>
                                        <br />
                                        @foreach ($permission as $value)
                                            <label>
                                                <input type="checkbox" name="permission[]" value="{{ $value->name }}" 
                                                    class="name">
                                                {{ $value->name }}
                                            </label>
                                            <br />
                                        @endforeach
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form><!-- End Multi Columns Form -->

                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <script>
        $(document).ready(function() {
            $('#name-input').focus(); // Example code
        });
    </script>
@endsection
