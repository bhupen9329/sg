@extends('layouts.main')
@section('title','Quotation - Saraswati Globals')
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
                                <a class="btn btn-secondary" href="{{ route('purchase.index') }}">Back</a>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="table-responsive table-bordered ">
                                        <table class="table">
                                            <tbody>
                                                @if (!empty($po_data))
                                                    <tr>
                                                        <th class="font-weight-bold">Company</th>
                                                        <td>{{ $po_data->company_name }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="font-weight-bold">PO No.​</th>
                                                        <td>{{ $po_data->document_number }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="font-weight-bold">Ordered Quantity (Ton)​</th>
                                                        <td>{{ $po_data->quantity }}</td>
                                                    </tr>

                                                    <tr>
                                                        <th class="font-weight-bold">Balanced Quantity (Ton)​</th>
                                                        <td>{{ $po_data->rest_quantity }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="font-weight-bold">Date</th>
                                                        <td>{{ date('d-M-Y', strtotime($po_data->created_at)) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="font-weight-bold">Status</th>
                                                        <td>{{ $po_data->status }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="font-weight-bold">Order Age</th>
                                                        <td>{{ $po_data->order_age }}</td>
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
                        </div>
                    </div>
                </div>
            </div>
        </section>





    </main><!-- End #main -->
@endsection
