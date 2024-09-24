@extends('layouts.main')
@section('title', 'Inward  - Saraswati Globals')
@section('content')
    <main id="main" class="main">

        <div class="dashboard-header pagetitle">
            <h1>Inward Details</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('/sales') }}">Inward  Details</a></li>
                    <li class="breadcrumb-item">Details</li>

                </ol>
            </nav>
        </div><!-- End Page Title -->


        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Inward  Info</h5>
                            <div class="text-end ">
                                <a class="btn btn-secondary" href="{{ route('sales.index') }}">Back</a>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive table-bordered ">
                                        <table class="table">
                                            <tbody>
                                                @if (!empty($sales_data))
                                                    
                                                    <tr>
                                                        <th class="font-weight-bold">Inward Number​</th>
                                                        <td>{{ $sales_data->so_number }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="font-weight-bold">Address​</th>
                                                        <td>{{ $sales_data->address }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="font-weight-bold">Date</th>
                                                        <td>{{ date('d-M-Y', strtotime($sales_data->date)) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="font-weight-bold">Terms &
                                                            Conditions</th>
                                                        <td>{{ $sales_data->terms_condition }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="font-weight-bold">Amount</th>
                                                        <td>{{ $sales_data->total_amount  }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="font-weight-bold">Total Quantity(Q)</th>
                                                        <td>{{ $sales_data->total_quantity }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="font-weight-bold">Status</th>
                                                        <td>{{ $sales_data->status }}</td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td colspan="2">No Purchase Order information available.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive table-bordered ">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Item Category </th>
                                                    <th>Item SubCategory </th>
                                                    <th>Quantity(Q) </th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($so_item as $item_data)
                                                    <tr>
                                                        <td>{{ $item_data->name }}</td>
                                                        <td>{{ $item_data->sub_category }}</td>
                                                        <td>{{ $item_data->qty }}</td>
                                                        <td>{{ $item_data->amount }}</td>
                                                    </tr>
                                                @endforeach


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
