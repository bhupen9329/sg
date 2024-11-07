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
                        <li class="breadcrumb-item"> Purchase Order Details</li>
                    </ol>
                </nav>
            </div><!-- End Page Title -->
            <!-- Purchase Order Details Section -->
            <div class="card">
                <div class="card-body">
                    <h4 class="text-blue h4">Purchase Order Details</h4> <br>

                    <div class="row mb-3">
                        <label for="poNumber" class="col-sm-2 col-form-label"><strong>PO Number</strong></label>
                        <div class="col-sm-4">
                            {{ $purchaseOrder->document_number }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="poNumber" class="col-sm-2 col-form-label"><strong>Company Name</strong></label>
                        <div class="col-sm-4">
                            {{ $purchaseOrder->company_name }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="poDate" class="col-sm-2 col-form-label"><strong>PO Date</strong></label>
                        <div class="col-sm-4">
                            {{-- {{ $purchaseOrder->date }} --}}
                            {{ date('d-M-Y', strtotime($purchaseOrder->date)) }}
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="poDueDate" class="col-sm-2 col-form-label"><strong>PO Due Date</strong></label>
                        <div class="col-sm-4">
                            {{-- {{ $purchaseOrder->due_date }} --}}
                            {{ date('d-M-Y', strtotime($purchaseOrder->due_date)) }}
                        </div>
                    </div>

                    {{-- <div class="row mb-3">
                        <label for="poQuantity" class="col-sm-2 col-form-label"><strong>PO Qty</strong></label>
                        <div class="col-sm-4">
                            {{ $purchaseOrder->quantity }} MT
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="poRemainingQty" class="col-sm-2 col-form-label"><strong>PO Pending Qty</strong></label>
                        <div class="col-sm-4">
                            {{ $purchaseOrder->rest_quantity }} MT
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="price" class="col-sm-2 col-form-label"><strong>Price</strong></label>
                        <div class="col-sm-4">
                            {{ $purchaseOrder->price }}
                        </div>
                    </div> --}}

                    <div class="row mb-3">
                        <label for="position" class="col-sm-2 col-form-label"><strong>Position</strong></label>
                        <div class="col-sm-4">
                            {{ $purchaseOrder->match_position }}
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
                                        <th>PO No.</th>
                                        <th>PO Item Number</th>
                                        <th>Seller Name(Party Name)</th>
                                        <th>Item Category</th>
                                        <th>Quantity(Q)</th>    
                                        <th>Rest Quantity(Q)</th>                                       
                                        <th>PO Unit Price</th>
                                        <th>PO Price</th>
                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($po_data)
                                        <tr>
                                            <td>{{ date('d-M-Y', strtotime($po_data->po_date ?? 'N/A')) }}</td>
                                            <td>{{ $po_data->document_number }}</td>
                                            <td>{{ $po_data->po_item_no }}</td>
                                            <td>{{ $po_data->supplier_name }}</td>
                                            <td>{{ $po_data->category_name }}</td>
                                            <td>{{ $po_data->qty }}</td>
                                            <td>{{ $po_data->po_rest_qty }}</td>
                                            <td>{{ $po_data->unit_price }}</td>
                                            <td>{{ $po_data->price }}</td>
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
                                        <th>Purchase Order</th>    
                                        <th>PO Item No</th>
                                        <th>Company Name</th>                                       
                                        <th>PO Item Name</th>
                                        <th>Sales Order</th>
                                        <th>SO Item No</th>  
                                        <th>Company Name</th>                                       
                                        <th>SO Item Name</th> 
                                        <th>Matched Quantity</th>
                                        <th>PO Item Rem Qty</th>
                                        <th>PO unit price</th>
                                        <th>SO Item Rem Qty</th>
                                        <th>SO unit price</th> 
                                        <th>PO Item Position (Status)</th>
                                        <th>SO Item Position (Status)</th>  
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- @dd($manual_match); --}}
                                    @foreach ($manual_match as $data)
                                        @php
                                            $po_status = $data->po_rest_quantity > 0 ? 'Open' : 'Closed';
                                            $so_status = $data->so_rest_quantity > 0 ? 'Open' : 'Closed';
                                        @endphp
                                        <tr>
                                            {{-- @dd($data); --}}
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ date('d-M-Y', strtotime($data->created_at)) }}</td>
                                            <td>{{ $data->po_number }}</td>
                                            <td>{{ $data->po_item_no }}</td>
                                            <td>{{ $data->po_company_name }}</td>
                                            <td>{{ $data->po_category_name }} {{ $data->po_sub_category_name }}</td>
                                            <td>{{ $data->so_number }}</td>
                                            <td>{{ $data->so_item_no }}</td>
                                            <td>{{ $data->so_company_name }}</td>
                                            <td>{{ $data->so_category_name }} {{ $data->so_sub_category_name }}</td>
                                            <td>{{ $data->matched_quantity }}</td>
                                            <td>{{ $data->po_item_rest_quantity }}</td>
                                            <td>{{ $data->po_items_unit_price }}</td>
                                            <td>{{ $data->so_item_rest_quantity }}</td>
                                            <td>{{ $data->so_items_unit_price }}</td>
                                            <td>{{ $data->po_item_status }} </td>
                                            <td>{{ $data->so_item_status }} </td>
                                            {{-- <td>{{ $data->po_match_position }} </td>
                                            <td>{{ $data->so_match_position }} </td> --}}
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

@if($po_data->po_item_status == 'Open')
            <div class="card">
                <div class="card-body">
                    <form id="billForm" action="{{ route('purchasesellmatch.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="pd-20">
                                            <h4 class="text-blue h4">Sales Orders (Open Positions)</h4><br>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Action</th>
                                                <th>Sales Order</th>
                                                <th>SO Item NO</th>
                                                <th>Buyer Name(Party Name)</th>
                                                <th>Item Category</th>
                                                <th>Quantity(Q)</th>    
                                                <th>Rest Quantity(Q)</th>                                       
                                                <th>SO Unit Price</th>
                                                <th>SO Price</th>
                                                <th>Matched Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($salesOrders as $order)
                                            @if($order->so_rest_qty == 0)
                                            @continue
                                            @endif
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="sales-order-checkbox" name="selected_so_items[]" value="{{ $order->so_item_id }}">
                                                    </td>
                                                    <td>{{ $order->so_number }}</td>
                                                    <td>{{ $order->so_item_no }}</td>
                                                    <td>{{ $order->company_name }}</td>
                                                    <td>{{ $order->category_name }}</td>
                                                    <td>{{ $order->qty }}</td>
                                                    <td>{{ $order->so_rest_qty }}</td>
                                                    <td>{{ $order->unit_price }}</td>
                                                    <td>{{ $order->price }}</td>

                                                    <td>
                                                        @if($order->so_rest_qty > $po_data->po_rest_qty)
                                                        <input type="number" step="any" name="matched_quantity[{{ $order->so_item_id }}]" class="matched-quantity-input form-control" disabled min="0" max="{{ $po_data->po_rest_qty }}" placeholder="Enter quantity">
                                                        @else
                                                        <input type="number" step="any" name="matched_quantity[{{ $order->so_item_id }}]" class="matched-quantity-input form-control" disabled min="0" max="{{ $order->so_rest_qty }}" placeholder="Enter quantity">
                                                        @endif
                                                    </td>
                                                   <input type="hidden" name="sales_order_id" id="salesOrderId" value="{{ $order->so_id }}">

                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
            
                                <input type="hidden" name="purchase_order_id" id="purchaseOrderId" value="{{ $purchaseOrder->id }}">
                                <input type="hidden" name="po_item_id" id="poItemId" value="{{ $po_data->po_item_id }}">
            
                                <button type="submit" class="btn btn-primary float-end" id="submit_btn">Submit</button>
                                <div id="on_submit" class="text-danger mt-2" style="display: none;">At least one row must be selected.</div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            @endif
            
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
