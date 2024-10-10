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
            <h1> Match Purchase</h1>
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
                    <label for="poDate" class="col-sm-2 col-form-label"><strong>PO Date</strong></label>
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
                </div>
            </div>
        </div><br><br>

        <div class="card">
            <div class="card-body">

        <form id="billForm" action="{{ route('purchasesellmatch.store') }}" method="post" enctype="multipart/form-data">
            @csrf
                <div class="row">
                    <div class="col-lg-12">
                   
                                <div class="row">
                                    <div class="col-md-6 col-sm-12">
                                        <div class="pd-20">
                                            <h4 class="text-blue h4">Sales Orders (Open Positions)</h4> <br>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-4">

                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Select</th>
                                                <th>Sales Order</th>
                                                <th>Open Quantity</th>
                                                <th>Rem Quantity</th>
                                                <th>Rate</th>
                                                <th>Matched Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <input type="hidden" name="purchase_order_id" id="purchaseOrderId">
                                            @foreach ($salesOrders as $order)
                                            
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" class="sales-order-checkbox"
                                                            name="selected_orders[]" value="{{ $order->id }}">
                                                    </td>
                                                    <td>{{ $order->so_number }}</td>
                                                    <td>{{ $order->total_quantity }}</td>
                                                    <td>{{ $order->rest_quantity }}</td>
                                                    <td>{{ $order->total_amount }}</td>
                                                    <td>
                                                        <!-- Input for matched quantity, disabled by default -->
                                                        <input type="number" step="any"
                                                            name="matched_quantity[{{ $order->id }}]"
                                                            class="matched-quantity-input form-control" disabled
                                                            min="0" max="{{ $order->total_quantity }}"
                                                            placeholder="Enter quantity">
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <button type="submit" class="btn btn-primary float-end" id="submit_btn">Submit</button>
                                <div id="on_submit" class="text-danger mt-2" style="display: none;">At least one row must be
                                    selected.</div>
                            </div>
                </div>
            </form>
        </div>
    </div>
 

    </main><!-- End #main -->
</section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const documentCells = document.querySelectorAll('.document-cell');
            const purchaseOrderIdInput = document.getElementById('purchaseOrderId');

            // When any document-cell link is clicked, set the hidden purchase order ID
            documentCells.forEach(function(cell) {
                cell.addEventListener('click', function() {
                    const purchaseOrderId = this.getAttribute('data-id');
                    purchaseOrderIdInput.value =
                    purchaseOrderId; // Set the purchase order ID in the hidden field
                });
            });

            const checkboxes = document.querySelectorAll('.sales-order-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const matchedQuantityInput = this.closest('tr').querySelector(
                        '.matched-quantity-input');
                    if (this.checked) {
                        matchedQuantityInput.disabled =
                        false; // Enable input when checkbox is checked
                    } else {
                        matchedQuantityInput.disabled =
                        true; // Disable input when checkbox is unchecked
                    }
                });
            });
        });
    </script>


@endsection
