@extends('layouts.main')
@section('title','Buyser Supliers - Saraswati Globals')
@section('content')
    <main id="main" class="main">
        <div class="dashboard-header pagetitle">
            <h1>Company Details</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Company</a></li>
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
                                    <h4 class="card-title">Show Company Details</h4>
                                </div>
                                <div class="col-lg-6 text-end">
                                    <a class="btn btn-primary mb-4 mr-3" href="{{ route('buyers.index') }}">Back</a>
                                </div>
                            </div>
                            <!-- Bootstrap Table -->
                            <div class="table">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <th scope="row">Company Name</th>
                                            <td>{{ $companies->company_name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Address</th>
                                            <td>{{ $companies->address }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Mobile</th>
                                            <td>{{ $companies->mobile  ?? 'N/A'}}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Email</th>
                                            <td>{{ $companies->email  ?? 'N/A'}}</td>
                                        </tr>
                                      
                                       
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


          
        </section>
        

    </main><!-- End #main -->
@endsection
