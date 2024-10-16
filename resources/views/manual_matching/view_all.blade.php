@extends('layouts.main')
@section('title', 'Inward - Saraswati Globals')
@section('content')
    <style>

    </style>
    <main id="main" class="main">
        @if ($message = Session::get('Credit_note_status'))
            <div class="tt active">
                <div class="tt-content">
                    <i class="fas fa-solid fa-check check"></i>
                    <div class="message">
                        <span class="text text-1">Success</span>
                        <span class="text text-2"> {{ $message }}</span>
                    </div>
                </div>
                <i class="fa-solid fa-xmark close"></i>
                <div class="pg active"></div>
            </div>
        @endif
        @if ($message = Session::get('success'))
            <div class="tt active">
                <div class="tt-content">
                    <i class="fas fa-solid fa-check check"></i>
                    <div class="message">
                        <span class="text text-1">Success</span>
                        <span class="text text-2"> {{ $message }}</span>
                    </div>
                </div>
                <i class="fa-solid fa-xmark close"></i>
                <div class="pg active"></div>
            </div>
        @endif
        @if ($message = Session::get('update'))
            <div class="tt active">
                <div class="tt-content">
                    <i class="fas fa-solid fa-check check"></i>
                    <div class="message">
                        <span class="text text-1">Update</span>
                        <span class="text text-2"> {{ $message }}</span>
                    </div>
                </div>
                <i class="fa-solid fa-xmark close"></i>
                <div class="pg active"></div>
            </div>
        @endif
        @if ($message = Session::get('approve'))
            <div class="tt active">
                <div class="tt-content">
                    <i class="fas fa-solid fa-check check"></i>
                    <div class="message">
                        <span class="text text-1">Approve</span>
                        <span class="text text-2"> {{ $message }}</span>
                    </div>
                </div>
                <i class="fa-solid fa-xmark close"></i>
                <div class="pg active"></div>
            </div>
        @endif

        @if ($message = Session::get('delete'))
            <div class="tt active">
                <div class="tt-content">
                    <i class="fas fa-solid fa-exclamation exclamation update"></i>
                    <div class="message">
                        <span class="text text-1">Delete</span>
                        <span class="text text-2"> {{ $message }}</span>
                    </div>
                </div>
                <i class="fa-solid fa-xmark close"></i>
                <div class="pg active"></div>
            </div>
        @endif
        <div class="dashboard-header pagetitle">
            <h1>Purchase -Sell Details</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Inventory</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3 d-flex justify-content-end">
                                <a href="{{ route('manual.matching') }}" class="btn btn-primary">Back</a>
                            </div>
                            <!-- Add Filter Section -->
                            {{-- <div class="row mb-4">

                                <div class="col-md-4 col-sm-6">
                                    <label for="date_filter" class="form-label">Select Filter</label>
                                    <select class="form-select" id="filterType" name="filterType">
                                        <option value="">Select Filter Type</option>
                                        <option value="weekly">Weekly</option>
                                        <option value="monthly">Monthly</option>
                                    </select>
                                </div>
                                <div class="col-md-2 col-sm-6" style="margin-top: 7px">
                                    <label for="filterTodate"><strong>From Date</strong></label>
                                    <?php
                                    $firstDayOfMonth = (new DateTime('first day of this month'))->format('Y-m-d');
                                    ?>
                                    <input type="date" class="form-control" value="<?php echo $firstDayOfMonth; ?>" name="to_date"
                                        id="filterTodate" required>
                                </div>
                                <div class="col-md-2 col-sm-6" style="margin-top: 7px">
                                    <label for="filterFromdate"><strong>To Date</strong></label>
                                    <?php
                                    $lastDayOfMonth = (new DateTime('last day of this month'))->format('Y-m-d');
                                    ?>
                                    <input type="date" class="form-control" value="<?php echo $lastDayOfMonth; ?>" name="from_date"
                                        id="filterFromdate" required>
                                </div>
                                <div class="col-md-4 col-sm-12 d-flex align-items-end">

                                    <button class=" m-1 btn btn-primary" type="button"
                                    onclick="filterButton(
                                        $('#filterType').val(),  
                                        $('#filterTodate').val(),
                                        $('#filterFromdate').val(),
                                    )">
                                    Apply
                                </button>
                                
                                </div>

                            </div> --}}

                            <!-- Existing Table Section -->
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="pd-20">
                                        <h4 class="text-blue h4">Purchase -Sell  Details</h4>
                                    </div>
                                </div>

                           
                            </div>

                            <!-- Table with stripped rows -->
                            <table class="table table-bordered table-hover table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Transaction Date</th>
                                        <th>Purchase Order</th>    
                                        <th>Company Name</th>                                       

                                        <th>Sales Order</th>
                                        <th>Company Name</th>                                       

                                        <th>Matched Quantity</th>
                                        <th>PO Remaining Quantity</th>
                                        <th>SO Remaining Quantity</th>    
                                        <th>Purchase Position (Status)</th>
                                        <th>Sales Position (Status)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($manual_match as $data)
                                        @php
                                            // Check if the purchase order and sales order are open or closed
                                            $po_status = $data->po_rest_quantity > 0 ? 'Open' : 'Closed';
                                            $so_status = $data->so_rest_quantity > 0 ? 'Open' : 'Closed';
                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ date('d-m-Y', strtotime($data->created_at)) }}</td>
                                            <td>{{ $data->po_number }}</td>
                                            <td>{{ $data->po_company_name }}</td>

                                            <td>{{ $data->so_number }}</td>
                                            <td>{{ $data->so_company_name }}</td>

                                            <td>{{ $data->matched_quantity }}</td>
                                            <td>{{ $data->po_item_rest_quantity }}</td>
                                            <td>{{ $data->so_item_rest_quantity }}</td>
                                            <td>{{ $data->po_match_position }} </td>
                                            <td>{{ $data->so_match_position }} </td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            
                            <!-- End Table with stripped rows -->
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </main><!-- End #main -->

 
@endsection
