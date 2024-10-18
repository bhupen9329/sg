@extends('layouts.main')
@section('title', 'Match-Purchase')

@section('content')
    <style>
        .input-wrapper {
            position: relative;
        }

        .input-wrapper input {
            padding-left: 30px;
        }

        .input-wrapper i {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #999;
            border: none;
        }
    </style>

    <main id="main" class="main">
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

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        
        @if (session('msg'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: "Oops!",
                        text: "{{ session('msg') }}",
                        icon: "error"
                    });
                });
            </script>
        @endif

        <section class="section">


            <div class="dashboard-header pagetitle">
                <h1> Manual Matching</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                        <li class="breadcrumb-item"> Sales Order Details</li>
                    </ol>
                </nav>
            </div><!-- End Page Title -->
            <!-- Purchase Order Details Section -->
            <div class="card">
                <div class="card-body">
                    <h4 class="text-blue h4">Sales Order Details</h4> <br>

                    <div class="row mb-3">
                        <label for="poNumber" class="col-sm-2 col-form-label"><strong>SO Number</strong></label>
                        <div class="col-sm-4">
                            {{ $salesOrders->so_number }}
                        </div>
                    </div>


                    <div class="row mb-3">
                        <label for="poNumber" class="col-sm-2 col-form-label"><strong>Party Name</strong></label>
                        <div class="col-sm-4">
                            {{ $salesOrders->company_name }}
                        </div>
                    </div>


                    <div class="row mb-3">
                        <label for="poDate" class="col-sm-2 col-form-label"><strong>SO Date</strong></label>
                        <div class="col-sm-4">
                            {{ $salesOrders->date }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="poDueDate" class="col-sm-2 col-form-label"><strong>SO Due Date</strong></label>
                        <div class="col-sm-4">
                            {{ $salesOrders->due_date }}
                        </div>
                    </div>

                    {{-- <div class="row mb-3">
                        <label for="poQuantity" class="col-sm-2 col-form-label"><strong>SO Quantity</strong></label>
                        <div class="col-sm-4">
                            {{ $salesOrders->total_quantity }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="poRemainingQty" class="col-sm-2 col-form-label"><strong>SO Remaining
                                Qty</strong></label>
                        <div class="col-sm-4">
                            {{ $salesOrders->rest_quantity }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label for="poRemainingQty" class="col-sm-2 col-form-label"><strong>SO Unit Price</strong></label>
                        <div class="col-sm-4">
                            {{ $salesOrders->total_amount }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="price" class="col-sm-2 col-form-label"><strong>Price</strong></label>
                        <div class="col-sm-4">
                            {{ $salesOrders->total_price }}
                        </div>
                    </div> --}}

                    <div class="row mb-3">
                        <label for="position" class="col-sm-2 col-form-label"><strong>Position</strong></label>
                        <div class="col-sm-4">
                            {{ $salesOrders->match_position }}
                        </div>
                    </div>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                           
                          

                            <!-- Existing Table Section -->
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="pd-20">
                                        <h4 class="text-blue h4">Purchase Transaction Details</h4>
                                    </div>
                                </div>

                           
                            </div>

                            <!-- Table with stripped rows -->
                            <table class="table table-bordered table-hover table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Date(DD/MM/YY)​</th>
                                        <th>SO No.</th>
                                        <th>SO Item Number</th>
                                        <th>Buyer Name(Party Name)</th>
                                        <th>Item Category</th>
                                        <th>Item Sub-Category</th>
                                        <th>Quantity(Q)</th>    
                                        <th>Rest Quantity(Q)</th>                                       
                                        <th>SO Unit Price</th>
                                        <th>SO Price</th>
                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($so_data)
                                        <tr>
                                            <td>{{ date('d-m-Y', strtotime($so_data->so_date ?? 'N/A')) }}</td>
                                            <td>{{ $so_data->so_number }}</td>
                                            <td>{{ $so_data->po_item_no }}</td>
                                            <td>{{ $so_data->supplier_name }}</td>
                                            <td>{{ $so_data->category_name }}</td>
                                            <td>{{ $so_data->sub_category_name }}</td>
                                            <td>{{ $so_data->qty }}</td>
                                            <td>{{ $so_data->so_rest_qty }}</td>
                                            <td>{{ $so_data->unit_price }}</td>
                                            <td>{{ $so_data->price }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                            
                            
                            <!-- End Table with stripped rows -->
                        </div>
                    </div>
                </div>
            </div><br><br>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                           
                          

                            <!-- Existing Table Section -->
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="pd-20">
                                        <h4 class="text-blue h4">Matched Transactions details</h4>
                                    </div>
                                </div>

                           
                            </div>

                            <!-- Table with stripped rows -->
                            <table class="table table-bordered table-hover table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Transaction Date</th>
                                        <th>Sales Order</th>
                                        <th>SO Item No</th>  
                                        <th>Company Name</th>                                       
                                        <th>SO Item Name</th>  
                                        <th>Purchase Order</th>    
                                        <th>PO Item No</th>  
                                        <th>Company Name</th>                                       
                                        <th>PO Item Name</th>  
                                        <th>Matched Quantity</th>
                                        <th>PO Item Rem Qty</th>
                                        <th>SO Item Rem Qty</th>    
                                        <th>Purchase Position (Status)</th>
                                        <th>Sales Position (Status)</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($manual_match as $data)
                                        @php
                                            $po_status = $data->po_rest_quantity > 0 ? 'Open' : 'Closed';
                                            $so_status = $data->so_rest_quantity > 0 ? 'Open' : 'Closed';
                                        @endphp
                                        <tr>
                                            {{-- @dd($data); --}}
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ date('d-m-Y', strtotime($data->created_at)) }}</td>
                                            <td>{{ $data->so_number }}</td>
                                            <td>{{ $data->so_item_no }}</td>
                                            <td>{{ $data->so_company_name }}</td>
                                            <td>{{ $data->so_category_name }} {{ $data->so_sub_category_name }}</td>
                                            <td>{{ $data->po_number }}</td>
                                            <td>{{ $data->po_item_no }}</td>
                                            <td>{{ $data->po_company_name }}</td>
                                            <td>{{ $data->po_category_name }} {{ $data->po_sub_category_name }}</td>
                                            <td>{{ $data->matched_quantity }}</td>
                                            <td>{{ $data->po_item_rest_quantity }}</td>
                                            <td>{{ $data->so_item_rest_quantity }}</td>
                                            <td>{{ $data->po_match_position }} </td>
                                            <td>{{ $data->so_match_position }} </td>
                                            <td> 
                                                <button type="button" class="btn btn-primary float-end" id="submit_btn" data-bs-toggle="modal" onClick="reply_click('{{ $data->transaction_id }}')" data-bs-target="#revertModal">Revert</button>
                                            </td>
                                            
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            
                            
                            <!-- End Table with stripped rows -->
                        </div>
                    </div>
                </div>
            </div><br><br>

            <div class="card">
                <div class="card-body">

                    <form id="billForm" action="{{ route('purchasesellmatch.store.buyer') }}" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="sales_order_id" id="salesOrderId">
                        <div class="mt-4">
                            <h5>Purchase Orders (Open Positions)</h5>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Action</th>
                                        <th>Purchase Order</th>
                                        <th>PO Item NO</th>
                                        <th>Seller Name(Party Name)</th>
                                        <th>Item Category</th>
                                        <th>Item Sub-Category</th>
                                        <th>Quantity(Q)</th>    
                                        <th>Rest Quantity(Q)</th>                                       
                                        <th>PO Unit Price</th>
                                        <th>PO Price</th>
                                        <th>Matched Quantity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($purchaseOrders as $order)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="sales-order-checkbox" name="selected_po_items[]" value="{{ $order->po_item_id }}">
                                            </td>
                                            <td>{{ $order->document_number }}</td>
                                            <td>{{ $order->po_item_no }}</td>
                                            <td>{{ $order->company_name }}</td>
                                            <td>{{ $order->category_name }}</td>
                                            <td>{{ $order->sub_category_name }}</td>
                                            <td>{{ $order->qty }}</td>
                                            <td>{{ $order->po_rest_qty }}</td>
                                            <td>{{ $order->unit_price }}</td>
                                            <td>{{ $order->price }}</td>
                                            <td>
                                                @if($order->po_rest_qty > $so_data->so_rest_qty)
                                                <input type="number" step="any" name="matched_quantity[{{ $order->po_item_id }}]" class="matched-quantity-input form-control" disabled  min="0" max="{{ $so_data->so_rest_qty }}" placeholder="Enter quantity">
                                                @else
                                                <input type="number" step="any" name="matched_quantity[{{ $order->po_item_id }}]" class="matched-quantity-input form-control" disabled  min="0" max="{{ $order->po_rest_qty }}" placeholder="Enter quantity">

                                                @endif
                                            </td>
                                           <input type="hidden" name="purchase_order_id" id="salesOrderId" value="{{ $order->po_id }}">

                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <input type="hidden" name="sales_order_id"  value="{{ $salesOrders->so_id }}">
                        <input type="hidden" name="so_item_id"  value="{{ $so_data->so_item_id }}">

                        <button type="submit" class="btn btn-primary float-end" id="submit_btn">Submit</button>
                        <div id="on_submit" class="text-danger mt-2" style="display: none;">At least one row must be
                            selected.</div>
                    </form>
                </div>
            </div>
 {{-- ...............................................modal...............................................  --}}

 <!-- Modal -->
 <div class="modal fade" id="revertModal" tabindex="-1" aria-labelledby="revertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="revertModalLabel">Confirm Revert</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          Are you sure you want to revert the changes?
        </div>
        <div class="modal-footer">
            <form action="{{ route('transaction_revert') }}" method="post">
                @csrf
           <input type="hidden" name="transaction_id" id="transaction_id">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" route="{{('')}}" class="btn btn-primary">Yes, Revert</button>
        </form>

        </div>
      </div>
    </div>
</div>

    </main><!-- End #main -->
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const documentCells = document.querySelectorAll('.document-cell');
            const purchaseOrderIdInput = document.getElementById('purchaseOrderId');
            const checkboxes = document.querySelectorAll('.sales-order-checkbox');
    
            // Function to set the hidden purchase order ID when a document-cell is clicked
            const setPurchaseOrderId = (id) => {
                if (id) {
                    purchaseOrderIdInput.value = id; // Set the purchase order ID in the hidden field
                } else {
                    console.error('Purchase Order ID not found on clicked element.');
                }
            };
    
            // Add click event listeners to document cells
            documentCells.forEach(cell => {
                cell.addEventListener('click', function() {
                    const purchaseOrderId = this.getAttribute('data-id'); // Ensure this attribute is set in HTML
                    setPurchaseOrderId(purchaseOrderId);
                });
            });
    
            // Function to handle enabling/disabling matched quantity input
            const toggleMatchedQuantityInput = (checkbox, matchedQuantityInput) => {
                matchedQuantityInput.disabled = !checkbox.checked; // Enable/disable input based on checkbox state
                if (!checkbox.checked) {
                    matchedQuantityInput.value = ''; // Clear the value if unchecked
                }
            };
    
            // Add change event listeners to sales order checkboxes
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const matchedQuantityInput = this.closest('tr').querySelector('.matched-quantity-input');
                    toggleMatchedQuantityInput(this, matchedQuantityInput);
                });
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.sales-order-checkbox');
    
    // Function to enable/disable matched quantity input based on checkbox selection
    const toggleMatchedQuantityInput = (checkbox, matchedQuantityInput) => {
        matchedQuantityInput.disabled = !checkbox.checked; // Enable/disable input based on checkbox state
        if (!checkbox.checked) {
            matchedQuantityInput.value = ''; // Clear value if unchecked
        }
    };

    // Add change event listeners to sales order checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const matchedQuantityInput = this.closest('tr').querySelector('.matched-quantity-input');
            toggleMatchedQuantityInput(this, matchedQuantityInput);
        });
    });
});

    </script>

    
<script>
        
        function reply_click(id)
        {
        $('#transaction_id').val(id);
        }
            </script>

@endsection
