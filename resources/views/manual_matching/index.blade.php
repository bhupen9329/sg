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

                                <div class="col-md-6 col-sm-12 d-flex justify-content-end">
                                    <div class="btn-group">
                                        @can('Inward-create')
                                            <a href="#" class="btn btn-primary mb-4 mr-3" data-bs-toggle="modal"
                                                data-bs-target="#PurchaseinwardModal">Add Inventory</a>
                                        @endcan
                                    </div>
                                    <div class="btn-group ps-3">
                                        @can('Inward-create')
                                            <a href="{{ route('show.purchases')}}"  class="btn btn-primary mb-4 mr-3"   data-bs-toggle="modal"  data-bs-target="#selectcompanymodal">Purchases </a>
                                        @endcan
                                    </div>

                                    <div class="btn-group ps-3">
                                        @can('Inward-create')
                                            <a href="#"  class="btn btn-primary mb-4 mr-3"   data-bs-toggle="modal"  data-bs-target="#selectbuyerorsupplier">Match Inventory </a>
                                        @endcan
                                    </div>
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
                                </div>
                            </div>

                            <!-- Table with stripped rows -->
                            <table class="table" id="Category_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Transaction Date​</th>
                                        <th>Item Name​</th>
                                       
                                        <th>Transaction Type</th>

                                        <th>Purchase ​</th>
                                        <th>Sell Qty</th>
                                        <th>Rem Pur</th>
                                        <th>Rem Sell</th>
                                        <th>Position<th>
                                        </tr>
                                   
                                </thead>
                                <tbody>
                                    {{-- @foreach ($transactions as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ date('d-m-Y', strtotime($data->transaction_date)) }}</td>
                                            <td>{{ $data->item_name }}</td>
                                            <td>{{ $data->transaction_type }}</td>
                                            
                                            
                                            <td>{{ $data->quantity }}</td>
                                            <td>{{ $data->unit_price }}</td>
                                            <td>Open</td>
                                        </tr>
                                    @endforeach --}}
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->
                        </div>
                    </div>
                </div>
            </div>
        </section>


    </main><!-- End #main -->

    
      <!-- Modal -->
      <div class="modal fade" id="selectbuyerorsupplier" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{ route('match.inventory') }}" method="post">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Select Company</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="width:50px"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label class="mb-2">Select Buyer or Supplier <span class="required-classes">*</span></label>
                            <div class="col-lg-12">
                                <input type="radio" name="type" value="buyer" id="buyerOption" checked> Buyer
                                <input type="radio" name="type" value="supplier" id="supplierOption"> Supplier
                            </div>
                        </div>
    
                        <div class="row">
                            <label for="company_id" class="mb-2">Select Company <span class="required-classes">*</span></label>
                            <div class="col-lg-12">
                                <select name="company_id" id="companySelect" class="form-select" required>
                                    <option value="">Select a Company</option>
                                    @foreach($buyers as $buyer)
                                        <option value="{{ $buyer->id }}" class="buyer-option">{{ $buyer->company_name }}</option>
                                    @endforeach
                                    @foreach($suppliers as $supplier)
                                        <option value="{{ $supplier->id }}" class="supplier-option" style="display: none;">{{ $supplier->company_name }}</option>
                                    @endforeach
                                </select>
                                @error('company_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buyerOption = document.getElementById('buyerOption');
            const supplierOption = document.getElementById('supplierOption');
            const companySelect = document.getElementById('companySelect');
            
            // Function to toggle company list based on selected type
            function toggleCompanyList() {
                const isBuyerSelected = buyerOption.checked;
    
                // Show buyer companies
                document.querySelectorAll('.buyer-option').forEach(option => {
                    option.style.display = isBuyerSelected ? '' : 'none';
                });
    
                // Show supplier companies
                document.querySelectorAll('.supplier-option').forEach(option => {
                    option.style.display = isBuyerSelected ? 'none' : '';
                });
    
                // Reset the selected value to default
                companySelect.value = '';
            }
    
            // Event listeners for radio buttons
            buyerOption.addEventListener('change', toggleCompanyList);
            supplierOption.addEventListener('change', toggleCompanyList);
    
            // Initial load (in case Buyer is the default)
            toggleCompanyList();
        });
    </script>
    
      <!-- Modal -->
      <div class="modal fade" id="selectcompanymodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{ route('show.purchases') }}" method="post">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Select Company</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"style="width:50px"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <label for="" class="mb-2">Select Company <span
                                    class="required-classes">*</span></label>

                            <div class="col-lg-12">
                                <select name="company_id" class="form-select" required>
                                    <option value="">Select a Supplier</option>
                                    @foreach($suppliers as $company)
                                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                    @endforeach
                                </select>
                                @error('company_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="PurchaseinwardModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal3Label">Add Inventory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"style="width:50px"></button>
                </div>
                <form action="{{ route('store_inventory') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <!-- Dropdown for selecting Purchase or Sell -->
                        <div class="form-group">
                            <label for="type">Select Type:</label>
                            <select name="type" id="selected_type_purchase" class="form-control" required>
                                <option value="">Select Type</option>
                                <option value="purchase">Purchase</option>
                                <option value="sell">Sell</option>
                            </select>
                        </div>

                        <!-- Form fields for data entry -->
                        <div class="form-group">
                            <label for="item_name">Party Name:</label>
                            <select name="company_name" id="company_name" class="form-control" required>
                                <option value="">Select Party Name</option>
                                @foreach($companies as $company)
                                    <option value="{{ $company->company_name }}">{{ $company->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="item_name">Item Name:</label>
                            <input type="text" name="item_name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="quantity">Quantity:</label>
                            <input type="number" step="any" name="quantity" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="price">Unit Price:</label>
                            <input type="number" step="0.01" name="price" class="form-control" required>
                        </div>

                        <!-- Additional fields can be added here -->
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>

            </div>
        </div>
    </div>


  


    <script>
     function filterButton(filterType, filterTodate, filterFromdate) {
    $.ajax({
        type: 'POST',
        url: 'inventory_valuation/get_inventory_list',
        data: {
            filterTodate: filterTodate,
            filterFromdate: filterFromdate,
            filterType: filterType,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            if (response && Array.isArray(response)) {
                var table = $('#Category_table').DataTable();
                table.clear().draw();
                response.forEach(function(data, index) {
                    var indentQty = data.indent_qty ?? 0;
                    var poQty = data.po_qty ?? 0; // Default to 0 if null or undefined
                    var inwardQty = data.inward_qty ?? 0; // Default to 0 if null or undefined
                    var allocateQty = data.allocation_qty ?? 0;
                    
                    // Calculate remainingQty and pendingIndent
                    var remainingQty = poQty != 0 ? poQty - inwardQty : 0;
                    var pendingIndent = indentQty - (poQty + allocateQty);

                    table.row.add([
                        index + 1,
                        data.transaction_date,
                        data.transaction_type ?? 'N/A',
                        data.unit_price ?? 'N/A',
                        data.quantity ?? 'N/A',
                        data.item_name ?? 'N/A',
                       
                    ]).draw(false);
                });
            } else {
                console.error("Invalid or empty response received.");
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX request failed:", status, error);
        }
    });
}

    </script>
@endsection
