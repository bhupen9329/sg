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

        <section class="section">


        <div class="dashboard-header pagetitle">
            <h1> Match Sales</h1>
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

                {{-- <div class="row mb-3">
                    <label for="poNumber" class="col-sm-2 col-form-label"><strong>PO Number</strong></label>
                    <div class="col-sm-4">
                        {{ $purchaseOrder->document_number }}
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="poDate" class="col-sm-2 col-form-label"><strong>SO Date</strong></label>
                    <div class="col-sm-4">
                        {{ $purchaseOrder->date }}
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="poDueDate" class="col-sm-2 col-form-label"><strong>PO Due Date</strong></label>
                    <div class="col-sm-4">
                        {{ $purchaseOrder->due_date }}
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="poQuantity" class="col-sm-2 col-form-label"><strong>PO Quantity</strong></label>
                    <div class="col-sm-4">
                        {{ $purchaseOrder->quantity }}
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="poRemainingQty" class="col-sm-2 col-form-label"><strong>PO Remaining Qty</strong></label>
                    <div class="col-sm-4">
                        {{ $purchaseOrder->rest_quantity }}
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="price" class="col-sm-2 col-form-label"><strong>Price</strong></label>
                    <div class="col-sm-4">
                        {{ $purchaseOrder->price }}
                    </div>
                </div>

                <div class="row mb-3">
                    <label for="position" class="col-sm-2 col-form-label"><strong>Position</strong></label>
                    <div class="col-sm-4">
                        {{ $purchaseOrder->match_position }}
                    </div>
                </div> --}}
            </div>
        </div><br><br>

        <div class="card">
            <div class="card-body">

        <form id="billForm" action="{{ route('purchasesellmatch.store.buyer')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="sales_order_id" id="salesOrderId">
            <div class="mt-4">
                <h5>Purchase Orders (Open Positions)</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Select</th>
                            <th>Purchase Order</th>
                            <th>Total Quantity</th>
                            <th>Remaining Quantity</th>
                            <th>Rate</th>
                            <th>Matched Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($purchaseOrders->isEmpty())
                            <tr>
                                <td colspan="6" class="text-center">No open purchase orders available.</td>
                            </tr>
                        @else
                            @foreach($purchaseOrders as $order)
                                <tr>
                                    <td>
                                        <input type="checkbox" class="purchase-order-checkbox" name="selected_orders[]" value="{{ $order->id }}">
                                    </td>
                                    <td>{{ $order->document_number }}</td>
                                    <td>{{ $order->quantity }}</td>
                                    <td>{{ $order->rest_quantity }}</td>
                                    <td>{{ $order->price }}</td>
                                    <td>
                                        <input type="number" 
                                               step="any" 
                                               name="matched_quantity[{{ $order->id }}]" 
                                               class="matched-quantity-input form-control" 
                                               min="0" 
                                               max="{{ $order->total_quantity }}" 
                                               placeholder="Enter quantity" 
                                               value="{{ old('matched_quantity.'.$order->id, '') }}">
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>

            <button type="submit" class="btn btn-primary float-end" id="submit_btn">Submit</button>
            <div id="on_submit" class="text-danger mt-2" style="display: none;">At least one row must be
                selected.</div>
            </form>
        </div>
    </div>
 

    </main><!-- End #main -->
</section>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const purchaseOrderIdInput = document.getElementById('salesOrderId');

        // Function to set the sales order ID in the hidden field
        window.setSalesOrderId = function(orderId) {
            purchaseOrderIdInput.value = orderId; // Set the sales order ID in the hidden field
        };

        const checkboxes = document.querySelectorAll('.purchase-order-checkbox');

        checkboxes.forEach(checkbox => {
            const matchedQuantityInput = checkbox.closest('tr').querySelector('.matched-quantity-input');

            // Disable matched quantity input initially
            matchedQuantityInput.disabled = true;

            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    matchedQuantityInput.disabled = false; // Enable input when checkbox is checked
                    setSalesOrderId(this.value); // Set the sales order ID when checked
                } else {
                    matchedQuantityInput.disabled = true; // Disable input when checkbox is unchecked
                    // Check if any other checkboxes are checked
                    const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
                    if (!anyChecked) {
                        purchaseOrderIdInput.value = ''; // Clear the hidden field if no checkboxes are checked
                    }
                }
            });
        });
    });
</script>

@endsection
