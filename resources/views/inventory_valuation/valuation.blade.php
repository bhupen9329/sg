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
            <h1>Inventory Details</h1>
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
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h4 class="text-blue h4">Inventory Valuation Summary</h4>
                                </div>
                            </div>
        
                            <!-- Filter Section (Optional) -->
                            <div class="row mb-4">
                                <div class="col-md-4">
                                    <label for="valuationType" class="form-label">Valuation Type</label>
                                    <select class="form-select" id="valuationType" name="valuationType" onchange="loadValuationData()">
                                        <option value="lifo">LIFO</option>
                                        <option value="fifo">FIFO</option>
                                        <option value="average">Average Cost</option>
                                    </select>
                                </div>
                            </div>
        
                            <!-- Table with stripped rows -->
                            <table class="table" id="inventorySummaryTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item Name</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total Value</th>
                                        <th>Valuation Method</th>
                                    </tr>
                                </thead>
                                <tbody>
                                   
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section> 



    </main><!-- End #main -->


   





    <script>
        // Load valuation data via AJAX
        function loadValuationData() {
            let valuationType = $('#valuationType').val();

            $.ajax({
                type: 'POST',
                url: '{{ route("inventory.getValuationData") }}',
                data: {
                    valuationType: valuationType,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    let table = $('#inventorySummaryTable tbody');
                    table.empty(); // Clear existing rows

                    // Loop through the response and append each item to the table
                    response.forEach((data, index) => {
                        let totalValue = data.total_value.toFixed(2);  // Format total value
                        let unitPrice = data.unit_price.toFixed(2);    // Format unit price

                        table.append(`
                            <tr>
                                <td>${index + 1}</td>
                                <td>${data.item_name}</td>
                                <td>${data.quantity}</td>
                                <td>${unitPrice}</td>
                                <td>${totalValue}</td>
                                <td>${valuationType.toUpperCase()}</td>
                            </tr>
                        `);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Failed to load valuation data:", status, error);
                }
            });
        }

        // Load default valuation data when the page loads (LIFO by default)
        $(document).ready(function() {
            loadValuationData();
        });
    </script>
@endsection
