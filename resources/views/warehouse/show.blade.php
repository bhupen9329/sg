@extends('layouts.main')
@section('title','Warehouse - Saraswati Globals')
@section('content')
    <main id="main" class="main">

        <div class="dashboard-header pagetitle">
            <h1>WareHouse Details</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/warehouse') }}">WareHouse Details</a></li>
                    <li class="breadcrumb-item">Details</li>

                </ol>
            </nav>
        </div><!-- End Page Title -->


        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">WareHouse Info</h5>
                            <div class="text-end ">
                                <a class="btn btn-secondary" href="{{ route('warehouse.index') }}">Back</a>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive table-bordered ">
                                        <table class="table">
                                            <tbody>
                                                @if ($warehouse)
                                                    <tr>
                                                        <th class="font-weight-bold">Warehouse Title</th>
                                                        <td>{{ $warehouse->warehouse_title }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="font-weight-bold">Address</th>
                                                        <td>{{ $warehouse->address }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="font-weight-bold">City</th>
                                                        <td>{{ $warehouse->city }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th class="font-weight-bold">State</th>
                                                        <td>{{ $warehouse->state }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="font-weight-bold">Country</th>
                                                        <td>{{ $warehouse->country }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="font-weight-bold">Pincode</th>
                                                        <td>{{ $warehouse->pincode }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="font-weight-bold">GSTN</th>
                                                        <td>{{ $warehouse->gstn }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th class="font-weight-bold">Store Manager</th>
                                                        <td>{{ $stock_manager->name }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th class="font-weight-bold">Date</th>
                                                        <td>{{ \Carbon\Carbon::parse($warehouse->created_at)->format('d-m-Y') }}
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td colspan="2">No WareHouse information available.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>





    </main><!-- End #main -->
@endsection
