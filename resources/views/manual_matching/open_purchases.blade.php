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

                                {{-- <div class="col-md-6 col-sm-12 d-flex justify-content-end">
                                    <div class="btn-group">
                                        @can('Inward-create')
                                            <a href="#" class="btn btn-primary mb-4 mr-3" data-bs-toggle="modal"
                                                data-bs-target="#PurchaseinwardModal">Add Inventory</a>
                                        @endcan
                                    </div>
                                    <div class="btn-group">
                                        @can('Inward-create')
                                            <a href="{{ route('show.purchases')}}" class="btn btn-primary mb-4 mr-3" >Purchases </a>
                                        @endcan
                                    </div> --}}
                                    {{-- <div class="btn-group ps-3">
                                        @can('Inward-create')
                                            <a href="{{ route('inventory.lifo') }}" class="btn btn-primary mb-4 mr-3">LIFO</a>
                                        @endcan
                                    </div>

                                    <div class="btn-group ps-3">
                                        @can('Inward-create')
                                            <a href="{{ route('inventory_valuation.valuation') }}" class="btn btn-primary mb-4 mr-3">Valuation</a>
                                        @endcan
                                    </div>

                                    <div class="btn-group ps-3">
                                        @can('Inward-create')
                                            <a href="{{ route('position.report') }}" class="btn btn-primary mb-4 mr-3">Position Report</a>
                                        @endcan
                                    </div> --}}
                                {{-- </div>
                            </div> --}}

                            <div class="dashboard-header pagetitle">
                                <div class="breadcrum">
                                    <section class="section">
                                        <div >
                                         
                    
                                           
                                            <h2>Open Purchases</h2>
                                            <div style="overflow-x: auto">
                                                <table class="table table-bordered xl">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Transaction Date</th>
                                                            <th>Transaction Type</th>
                                                            <th>PO Number</th>
                                                            <th>Supplier Name</th>
                                                            <th>Item Name</th>
                                                            <th>Quantity Name</th>
                                                            <th>Price</th>

                                                            <th>Position</th>

                                                          
                                                        </tr>
                                                      
                                                    </thead>
                                                    <tbody>
                                                  
                                                            @foreach ($purchases as $data)
                                                                <tr  >
                                                                    <td>{{ $loop->iteration }}</td>
                                                                     <td>{{ date('d-m-Y', strtotime($data->created_at)) }}</td> 
                                                                    <td>Purchase</td>

                                                                    <td>
                                                                        <a href="#" class="document-cell" data-document-number="{{ $data->company_name }}"  data-bs-toggle="modal"  data-bs-target="#documentModal">
                                                                            {{ $data->document_number }}
                                                                        </a>
                                                                    </td>
                                                                    
                                                                     <td>{{$data->supplier_id }}</td>
                                                                     <td>{{$data->category }}{{$data->sub_category_id }}</td>
                                                                     <td>{{$data->quantity }}</td>
                                                                     <td>{{$data->price }}</td>
                                                                   
                                                                    <td>Open</td>
                                                                </tr>
                                                            @endforeach
                                                   
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                              
                            <!-- End Table with stripped rows -->
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </main><!-- End #main -->

<!-- Modal -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Larger modal for better visibility -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="documentModalLabel">Purchase Order Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="modalDocumentNumber" class="form-label"><strong>Supplier Name:</strong></label>
                    <p id="modalDocumentNumber"></p>
                </div>

                <table class="table" id="buyerTable">
                    <thead>
                        <tr>
                            <th>Buyer</th>
                            <th>Quantity</th>
                            <th>Rate</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
              
                                <select name="buyer[]" class="form-select" required>
                                    <option value="">Select a Buyer</option>
                                    @foreach($buyers as $buyer)
                                        <option value="{{ $buyer->id }}">{{ $buyer->id }}</option>
                                    @endforeach
                                </select>
                                @error('buyer.*') <span class="text-danger">{{ $message }}</span> @enderror
                                
                            </td>
                            <td>
                                <input type="number" class="form-control" name="quantity[]" placeholder="Enter Quantity" min="0" step="1">
                            </td>
                            <td>
                                <input type="number" class="form-control" name="rate[]" placeholder="Enter Rate" min="0" step="0.01">
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger remove-row">Remove</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                
                <button type="button" class="btn btn-primary" id="addRowButton">Add Another Buyer</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveDetailsButton">Save Details</button> <!-- Save action button -->
            </div>
        </div>
    </div>
</div>

<script>

$(document).ready(function() {
    // When a document cell is clicked, populate the modal and show it
    $('.document-cell').on('click', function() {
        const documentNumber = $(this).data('document-number');
        $('#modalDocumentNumber').text(documentNumber);
        $('#buyerTable tbody').empty(); // Clear previous entries
        addRow(); // Add the first row
        $('#documentModal').modal('show');
    });

    // Function to add a new row
    function addRow() {
        const newRow = `
            <tr>
                <td>
                 <select name="buyer[]" class="form-select" required>
                                    <option value="">Select a Buyer</option>
                                    @foreach($buyers as $buyer)
                                        <option value="{{ $buyer->id }}">{{ $buyer->company_name }}</option>
                                    @endforeach
                                </select>
                </td>
                <td>
                    <input type="number" class="form-control" name="quantity[]" placeholder="Enter Quantity" min="0" step="1">
                </td>  
                <td>
                    <input type="number" class="form-control" name="rate[]" placeholder="Enter Rate" min="0" step="0.01">
                </td>
                <td>
                    <button type="button" class="btn btn-danger remove-row">Remove</button>
                </td>
            </tr>
        `;
        $('#buyerTable tbody').append(newRow);
    }

    // Add new row on button click
    $('#addRowButton').on('click', function() {
        addRow();
    });

    // Remove a row on button click
    $(document).on('click', '.remove-row', function() {
        $(this).closest('tr').remove();
    });

    // Handle the save action
    $('#saveDetailsButton').on('click', function() {
        const buyers = [];
        const quantities = [];
        const rates = [];

        $('#buyerTable tbody tr').each(function() {
            const buyer = $(this).find('.buyer-select').val();
            const quantity = $(this).find('input[name="quantity[]"]').val();
            const rate = $(this).find('input[name="rate[]"]').val();
            if (buyer && quantity && rate) {
                buyers.push(buyer);
                quantities.push(quantity);
                rates.push(rate);
            }
        });

        if (buyers.length > 0) {
            // Process the data (you can make an AJAX call or form submission here)
            console.log('Buyers:', buyers, 'Quantities:', quantities, 'Rates:', rates);

            // Optionally, close the modal
            $('#documentModal').modal('hide');
        } else {
            // Handle validation (e.g., alerting the user)
            alert('Please fill in all fields for at least one buyer.');
        }
    });
});

    </script>

  
@endsection
