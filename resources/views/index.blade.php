@extends('layouts.main')
@section('title', 'Dashboard- Saraswati Globals')
@section('content')
    <main id="main" class="main">

        <div class="dashboard-header pagetitle">
            <h1>Dashboard</h1><br>
            <p style="color: red">Overview of sales, purchase, stock and valuation insights for open orders.</p>
        </div><!-- End Page Title -->

        @can('Dashboard')
            
        <section class="section dashboard">
            <style>
                .card {
                    padding-top: 0px !important;
                }


                .dashboard_dataTables_wrapper {
                    height: 213px;
                    overflow-y: scroll;
                }

                .note-toolbar .btn-primary:hover,
                .note-toolbar .btn-primary:active,
                .note-toolbar .btn-primary:focus {
                    background-color: #007bff;
                }

                .row {
                    --bs-gutter-x: -0.5rem !important;
                }
            </style>

            <!-- Left side columns -->
            <div class="row ">

                <!--User ​ Card -->
                <div class="col-lg-6 px-2 py-4 ">
                    <div class="card info-card sales-card">
                        <div class="card-body">
                            <h5 class="card-title">Total Sales Order Quantity</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-chart-line"></i>
                                </div>
                                <div class="ps-3">
                                    <a href="#" style="text-decoration: underline; color: rgb(16, 16, 17);"
                                        data-bs-toggle="modal" data-bs-target="#Modalfor_quantity_details_item_wise"
                                        class="rest-quantity-link" onclick="get_received_so_qty_for_report_item_wise()">
                                        <h6>
                                            {{ number_format($sales_order, 3) }}
                                        </h6>
                                    </a>





                                </div>
                            </div>
                        </div>

                    </div>
                </div><!-- End User Card -->

                <!-- Revenue Card -->
                <div class="col-lg-6 px-2 py-4 ">
                    <div class="card info-card revenue-card">

                        <div class="card-body">
                            <h5 class="card-title">Total Purchase Order Quantity</h5>

                            <div class="d-flex align-items-center">
                                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <div class="ps-3">

                                    <a href="#" style="text-decoration: underline; color: rgb(16, 16, 17);"
                                        data-bs-toggle="modal" data-bs-target="#Modalfor_quantity_details_item_wise_po"
                                        class="rest-quantity-link" onclick="get_received_po_qty_for_report_item_wise()">
                                        <h6>
                                            {{-- {{ $purchase_order }} --}}
                                            {{ number_format($purchase_order, 3) }}
                                        </h6>
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div><!-- End Customers Card -->


            <!-- Modal  -->
            <div class="modal fade" id="Modalfor_quantity_details" tabindex="-1" aria-labelledby="modal3Label"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal3Label">Purchase Quantity - History</h5>
                            <a href="javascript:void(0);"
                                style="text-decoration: underline; color: blue; margin-left: 222px;"
                                class="rest-quantity-link" id="showMoreDetailsLink">
                                Show More Details
                            </a>

                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"style="width:50px"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">PO Date</th>
                                        <th scope="col">Party Name</th>
                                        <th scope="col">PO Number</th>
                                        <th scope="col">Total Qty</th>
                                        <th scope="col">Rest Qty</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="Modalfor_quantity_details_item_wise" tabindex="-1" aria-labelledby="modal3Label"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal3Label">Sales Order Quantity - Item Wise</h5>

                            <a href="{{ route('so_report') }}"
                                style="text-decoration: underline; color: blue; margin-left: 222px;"
                                class="rest-quantity-link">
                                Show More Details</a>

                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"style="width:50px"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Base Item</th>
                                        <th scope="col">Total Qty</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="Modalfor_quantity_details_item_wise_po" tabindex="-1" aria-labelledby="modal3Label"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal3Label">Purchase Order Quantity - Item Wise</h5>
                            <a href="{{ route('po_report') }}"
                                style="text-decoration: underline; color: blue; margin-left: 222px;"
                                class="rest-quantity-link">
                                Show More Details</a>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"style="width:50px"></button>
                        </div>
                        <div class="modal-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Base Item</th>
                                        <th scope="col">Total Qty</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="Modalfor_quantity_details_so" tabindex="-1" aria-labelledby="modal3Label"
                aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal3Label">Sales Order Quantity - History</h5>
                            <a href="javascript:void(0);"
                                style="text-decoration: underline; color: blue; margin-left: 222px;"
                                class="rest-quantity-link" id="showMoreDetailsLinkSo">
                                Show More Details
                            </a>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"style="width:50px"></button>
                        </div>
                        <div class="modal-body-so">
                            <table class="table SO table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">SO Date</th>
                                        <th scope="col">Party Name</th>
                                        <th scope="col">SO Number</th>
                                        <th scope="col">Total Qty</th>
                                        <th scope="col">Rest Qty</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="so_items" tabindex="-1" aria-labelledby="modal3Label" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal3Label">Sales Order Item </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"style="width:50px"></button>
                        </div>
                        <div class="modal-body-so-item">
                            <table class="table SO table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">SO Due Date</th>
                                        <th scope="col">SO Number</th>
                                        <th scope="col">Party Name</th>
                                        <th scope="col">Sales Person</th>
                                        <th scope="col">Rest Quantity</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="po_items" tabindex="-1" aria-labelledby="modal3Label" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal3Label">Purchase Order Item </h5>

                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"style="width:50px"></button>
                        </div>
                        <div class="modal-body-po-item">
                            <table class="table SO table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">PO Due Date</th>
                                        <th scope="col">PO Number</th>
                                        <th scope="col">Party Name</th>
                                        <th scope="col">Purchase Person</th>
                                        <th scope="col">Rest Quantity</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="Modalfor_quantity_details_so_party_wise" tabindex="-1"
                aria-labelledby="modal3Label" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal3Label">Sales Order Item </h5>
                            <a href="javascript:void(0);"
                                style="text-decoration: underline; color: blue; margin-left: 222px;"
                                class="rest-quantity-link" id="showMoreDetailsLinkSoPartyWise">
                                Show More Details
                            </a>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"style="width:50px"></button>
                        </div>
                        <div class="modal-body-so-item-party-wise">
                            <table class="table SO table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Base Item</th>
                                        <th scope="col">Quantity</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade" id="Modalfor_quantity_details_po_party_wise" tabindex="-1"
                aria-labelledby="modal3Label" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal3Label">Purchase Order Item </h5>
                            <a href="javascript:void(0);"
                                style="text-decoration: underline; color: blue; margin-left: 222px;"
                                class="rest-quantity-link" id="showMoreDetailsLinkPoPartyWise">
                                Show More Details
                            </a>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"style="width:50px"></button>
                        </div>
                        <div class="modal-body-po-item-party-wise">
                            <table class="table PO table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Base Item</th>
                                        <th scope="col">Quantity</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            {{-- .................................................................................................................................... --}}

            <div class="row">
                <div class="col-lg- px-2 py-4 ">
                    <div class="row dashboard-container">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Latest Stock Details</h5>

                                    <!-- Table with stripped rows -->
                                    <div class="dashboard_dataTables_wrapper_low">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th style="padding: 8px;">Date</th>
                                                    <th style="padding: 8px;">Item Name</th>
                                                    <th style="padding: 8px;">Position (MT)</th>
                                                    <th style="padding: 8px;">LIFO Valuation (र)</th>
                                                    <th style="padding: 8px;">FIFO Valuation (र)</th>
                                                    <th style="padding: 8px;">Average Valuation (र)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if (!empty($lifoData))
                                                    <!-- Table row with data -->
                                                    {{-- @dump($lifo_transaction); @dump($inventory_transaction); --}}
                                                    @foreach ($inventory_transaction as $data)
                                                        <tr>
                                                            {{-- <td style="padding: 8px;">{{ ($data['transaction_date']) }}</td> --}}
                                                            @foreach ($lifo_transaction as $lifo_transactions)
                                                                @if ($data['id'] == $lifo_transactions['transaction_id'] && $data['item_id'] == $lifo_transactions['item_id'])
                                                                    <td style="padding: 8px;">
                                                                        {{ date('d-M-Y', strtotime($data['transaction_date'])) }}
                                                                    </td>
                                                                    <td style="padding: 8px;">
                                                                        {{ $data['item_name'] ?? 'N/A' }}</td>
                                                                @endif
                                                            @endforeach

                                                            {{-- <td style="padding: 8px;">{{ $data['transaction_type'] ?? 'N/A' }}</td> --}}
                                                            {{-- <td style="padding: 8px;">{{ $data['quantity'] ?? 'N/A' }}</td> --}}

                                                            <!-- LIFO Transactions -->
                                                            @foreach ($lifo_transaction as $lifo_transactions)
                                                                @if ($data['id'] == $lifo_transactions['transaction_id'] && $data['item_id'] == $lifo_transactions['item_id'])
                                                                    <td style="padding: 8px;">
                                                                        {{ number_format($lifo_transactions['balance_qty'], 3) ?? 'N/A' }}
                                                                    </td>
                                                                    <td style="padding: 8px;">
                                                                        <a
                                                                            href="{{ route('show.lifo', ['id' => $data['id'], 'item_id' => $data['item_id']]) }}">
                                                                            {{ number_format($lifo_transactions['balance_unit_price'], 2) ?? 'N/A' }}
                                                                        </a>
                                                                    </td>
                                                                @endif
                                                            @endforeach

                                                            <!-- FIFO Transactions -->
                                                            @foreach ($fifo_transaction as $fifo_transactions)
                                                                @if ($data['id'] == $fifo_transactions['transaction_id'] && $data['item_id'] == $fifo_transactions['item_id'])
                                                                    <td style="padding: 8px;">
                                                                        <a
                                                                            href="{{ route('show.fifo', ['id' => $data['id'], 'item_id' => $data['item_id']]) }}">
                                                                            {{ number_format($fifo_transactions['balance_unit_price'], 2) ?? 'N/A' }}
                                                                        </a>
                                                                    </td>
                                                                @endif
                                                            @endforeach


                                                            @foreach ($avg_transaction as $avg_transactions)
                                                                @if ($data['id'] == $avg_transactions['transaction_id'] && $data['item_id'] == $avg_transactions['item_id'])
                                                                    <td style="padding: 8px;">
                                                                        <a
                                                                            href="{{ route('show.average', ['id' => $data['id'], 'item_id' => $data['item_id']]) }}">
                                                                            {{ number_format($avg_transactions['balance_unit_price'], 2) ?? 'N/A' }}
                                                                        </a>
                                                                    </td>
                                                                @endif
                                                            @endforeach



                                                            <!-- LIFO Manual Match and Netwise -->
                                                            {{-- <td style="padding: 8px;">{{ $lifoData['manual_match'] ?? 'N/A' }}</td> --}}
                                                            {{-- <td style="padding: 8px;">
                                                                
                                                                    <a href="{{ route('show.average', ['id' => $data['id'], 'item_id' => $data['item_id']]) }}">
                                                                    N/A
                                                                </a>
                                                            </td> --}}
                                                            {{-- <td style="padding: 8px;">{{ $lifoData['netwise'] ?? 'N/A' }}</td> --}}
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <!-- No data row -->
                                                    <tr>
                                                        <td colspan="7" style="padding: 8px; text-align: center;">No
                                                            data
                                                            available</td>
                                                    </tr>
                                                @endif
                                            </tbody>

                                        </table>
                                    </div>
                                    <!-- End Table with stripped rows -->

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>


            <div class="row">

                <div class="col-lg-6 px-2 py-4 ">
                    <div class="row dashboard-container">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Due Sales Order Date</h5>

                                    <!-- Table with stripped rows -->
                                    <div class="dashboard_dataTables_wrapper_low">
                                        <table class="table" id="table_1">
                                            <thead>
                                                <tr>
                                                    <th>Due SO Date</th>
                                                    <th>Total SO Due Quantity</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $current_date = date('d-M-Y');
                                                ?>

                                                <tr>

                                                    <td>{{ date('d-M-Y', strtotime($current_date)) }}</td>
                                                    <td>
                                                        {{-- <a href="" style="text-decoration: underline; color: blue;"
                                                            data-bs-toggle="modal" data-bs-target="#so_items"
                                                            class="rest-quantity-link"
                                                            onclick="get_so_items_for_report({{ $total_sales_order_quantity }})">
                                                            {{ $total_sales_order_quantity }}
                                                        </a> --}}

                                                        <a href="{{ route('due_so_report') }}"
                                                            style="text-decoration: underline; color: blue;"
                                                            class="rest-quantity-link">
                                                            {{ number_format($total_sales_order_quantity, 3) }}
                                                        </a>
                                                    </td>


                                                </tr>

                                            </tbody>

                                        </table>
                                    </div>
                                    <!-- End Table with stripped rows -->

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-lg-6 px-2 py-4 ">
                    <div class="row dashboard-container">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Due Purchase Order Date</h5>

                                    <!-- Table with stripped rows -->
                                    <div class="dashboard_dataTables_wrapper_low">
                                        <table class="table"  id="table_2">
                                            <thead>
                                                <tr>
                                                    <th>Due PO Date</th>
                                                    <th>Total PO Due Quantity</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $current_date = date('d-M-Y');
                                                ?>

                                                <tr>

                                                    <td>{{ date('d-M-Y', strtotime($current_date)) }}</td>
                                                    <td>
                                                        {{-- <a href="#" style="text-decoration: underline; color: blue;"
                                                            data-bs-toggle="modal" data-bs-target="#po_items"
                                                            class="rest-quantity-link"
                                                            onclick="get_po_items_for_report({{ $total_purchase_order_quantity }})">
                                                            {{ $total_purchase_order_quantity }}
                                                        </a> --}}

                                                        <a href="{{ route('due_po_report') }}"
                                                            style="text-decoration: underline; color: blue;"
                                                            class="rest-quantity-link">
                                                            {{ number_format($total_purchase_order_quantity, 3) }}
                                                    </td>


                                                </tr>

                                            </tbody>

                                        </table>
                                    </div>
                                    <!-- End Table with stripped rows -->

                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            </div>


            <div class="row">
                <div class="col-lg-6 px-2 py-4 ">
                    <div class="row dashboard-container">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Stock Item Wise​ </h5>

                                    <!-- Table with stripped rows -->
                                    <div class="dashboard_dataTables_wrapper_low">
                                        <table class="table" id="table_3">
                                            <thead>
                                                <tr>
                                                    <th>Base Item</th>
                                                    <th>Total SO Qty</th>
                                                    <th>Total PO Qty</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($mergedTotals as $total)
                                                    @if ($total['so_total_quantity'] == null && $total['po_total_quantity'] == null)
                                                    @continue
                                                @endif
                                                    <tr>
                                                        <td>{{ $total['category_name'] }}</td>
                                                        <td>
                                                          @if ($total['so_total_quantity'] == null)
                                                              N/A
                                                          @else
                                                                <a href="#"
                                                                style="text-decoration: underline; color: blue;"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#Modalfor_quantity_details_so"
                                                                class="rest-quantity-link"
                                                                onclick="openModalWithCategorySO({{ $total['category_id'] }})">
                                                                {{ number_format((float) $total['so_total_quantity'], 3) }}
                                                            </a>
                                                          @endif
                                                        </td>
                                                        <td>
                                                            @if ( $total['po_total_quantity'] == null)
                                                              N/A  
                                                            @else
                                                                 <a href="#"
                                                                style="text-decoration: underline; color: blue;"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#Modalfor_quantity_details"
                                                                class="rest-quantity-link"
                                                                onclick="openModalWithCategory({{ $total['category_id'] }})">
                                                                {{ number_format((float) $total['po_total_quantity'], 3) }}
                                                            </a>
                                                            @endif
                                                           
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                    <!-- End Table with stripped rows -->

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-lg-6 px-2 py-4 ">
                    <div class="row dashboard-container">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Stock Party Wise​ </h5>

                                    <!-- Table with stripped rows -->
                                    <div class="dashboard_dataTables_wrapper_low">
                                        <table class="table" id="table_4">
                                            <thead>
                                                <tr>
                                                    <th>Party Name</th>
                                                    <th>Total SO Qty</th>
                                                    <th>Total PO Qty</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- @dd($mergedTotalsPartyWise) --}}
                                                @foreach ($mergedTotalsPartyWise as $total)
                                                  @if ($total['so_total_quantity'] == null && $total['po_total_quantity'] == null)
                                                    @continue
                                                @endif
                                                    <tr>
                                                        <td>{{ $total['company_name'] }}</td>
                                                        <td>
                                                            
                                                            @if ($total['so_total_quantity'] == null)
                                                            N/A
                                                               
                                                    
                                                            @else

                                                                 <a href="#"
                                                                style="text-decoration: underline; color: blue;"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#Modalfor_quantity_details_so_party_wise"
                                                                class="rest-quantity-link"
                                                                onclick="openModalWithCategorySOPartyWise({{ $total['party_id'] }})">
                                                                {{-- {{ $total['so_total_quantity'] }} --}}
                                                                {{ number_format((float) $total['so_total_quantity'], 3) }}
                                                                        </a>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ( $total['po_total_quantity'] == null)
                                                                 N/A
                                                            @else
                                                                 <a href="#"
                                                                style="text-decoration: underline; color: blue;"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#Modalfor_quantity_details_po_party_wise"
                                                                class="rest-quantity-link"
                                                                onclick="openModalWithCategoryPOPartyWise({{ $total['party_id'] }})">
                                                                {{-- {{ $total['po_total_quantity'] }} --}}
                                                                {{ number_format((float) $total['po_total_quantity'], 3) }}
                                                            </a>
                                                            @endif
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        </table>
                                    </div>
                                    <!-- End Table with stripped rows -->

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </section>
        @endcan

<script>
    $(document).ready(function() {
        $('#table_1').DataTable();
        $('#table_2').DataTable();
        $('#table_3').DataTable();
        $('#table_4').DataTable();
    });
</script>

        <script>
            function get_category_id(id) {
                var category_id = id;

                $.ajax({
                    url: "{{ route('get_category_data') }}",
                    method: "POST",
                    data: {
                        category_id: category_id,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        var data = res.data;
                        $('#Category').val(data.name);
                        $('#Price').val(data.price);
                        $('#Margin').val(data.margin);
                        $('#Id').val(data.id);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        </script>

        <script>
            function openModalWithCategory(category_id) {
                // Update the 'Show More Details' link with the category_id
                const link = document.getElementById('showMoreDetailsLink');
                const baseUrl = "{{ route('po_report', ':id') }}";
                const updatedUrl = baseUrl.replace(':id', category_id);
                link.setAttribute('href', updatedUrl);

                // Set the category_id in the modal and fetch data
                get_received_qty_for_report(category_id);
            }

            function openModalWithCategorySO(category_id) {

                // Update the 'Show More Details' link with the category_id
                const link = document.getElementById('showMoreDetailsLinkSo');
                const baseUrl = "{{ route('so_report', ':id') }}";
                const updatedUrl = baseUrl.replace(':id', category_id);
                link.setAttribute('href', updatedUrl);

                // Set the category_id in the modal and fetch data
                get_received_so_qty_for_report(category_id);
            }




            function get_received_qty_for_report(category_id) {
                let get_category_id = category_id;
                $.ajax({
                    url: "{{ url('get_received_qty') }}",
                    method: "POST",
                    data: {
                        get_category_id: get_category_id,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        // console.log(res); // Log the response to the console
                        let rowsData = res.rows_data;
                        let tableBody = document.querySelector('.modal-body table  tbody');
                        tableBody.innerHTML = ''; // Clear existing table rows

                        rowsData.forEach((rowData, index) => {
                            // Parse the date string and format it
                            let date = new Date(rowData.date);
                            let formattedDate = date.toLocaleDateString('en-GB', {
                                day: '2-digit',
                                month: '2-digit', // Use '2-digit' for numeric month or 'short' for abbreviated text month
                                year: 'numeric'
                            });
                            let row = `<tr>
                                <th scope="row">${index + 1}</th>
                                <td>${formattedDate}</td>
                                 <td>${rowData.company_name}</td>
                                <td>${rowData.document_number}</td>
                                                <td>${parseFloat(rowData.qty).toFixed(3)}</td>
                                  <td>${parseFloat(rowData.po_dispatch_rest_qty).toFixed(3)}</td>
            
                            </tr>`;
                            tableBody.insertAdjacentHTML('beforeend', row);
                        });
                    }


                });
            }

            function get_received_so_qty_for_report(category_id) {
                let get_category_id = category_id;
                $.ajax({
                    url: "{{ url('get_received_qty_so') }}",
                    method: "POST",
                    data: {
                        get_category_id: get_category_id,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        // console.log(res); // Log the response to the console
                        let rowsData = res.rows_data;
                        let tableBody = document.querySelector('.modal-body-so table tbody');
                        tableBody.innerHTML = ''; // Clear existing table rows

                        rowsData.forEach((rowData, index) => {
                            // Parse the date string and format it
                            let date = new Date(rowData.date);
                            let formattedDate = date.toLocaleDateString('en-GB', {
                                day: '2-digit',
                                month: '2-digit', // Use '2-digit' for numeric month or 'short' for abbreviated text month
                                year: 'numeric'
                            });
                            let row = `<tr>
                                <th scope="row">${index + 1}</th>
                                <td>${formattedDate}</td>
                                 <td>${rowData.company_name}</td>
                                <td>${rowData.so_number}</td>
                                  <td>${parseFloat(rowData.qty).toFixed(3)}</td>
                                  <td>${parseFloat(rowData.so_dispatch_rest_qty).toFixed(3)}</td>
                            </tr>`;
                            tableBody.insertAdjacentHTML('beforeend', row);
                        });
                    }


                });
            }

            function get_received_so_qty_for_report_item_wise() {
                $.ajax({
                    url: "{{ url('get_received_qty_so_item_wise') }}",
                    method: "GET",
                    success: function(response) {
                        let tbody = '';
                        if (response && response.data) {
                            response.data.forEach((item, index) => {
                                tbody += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.name}</td>
                           <td>${parseFloat(item.total_qty).toFixed(3)}</td>
                        </tr>`;
                            });
                        } else {
                            tbody = `
                    <tr>
                        <td colspan="3" class="text-center">No data available</td>
                    </tr>`;
                        }
                        $('#Modalfor_quantity_details_item_wise tbody').html(tbody);
                    },
                    error: function(xhr, status, error) {
                        alert('Error fetching data: ' + error);
                    }
                });
            }

            function get_received_po_qty_for_report_item_wise() {
                $.ajax({
                    url: "{{ url('get_received_qty_po_item_wise') }}",
                    method: "GET",
                    success: function(response) {
                        let tbody = '';
                        if (response && response.data) {
                            response.data.forEach((item, index) => {
                                tbody += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${item.name}</td>
                            <td>${parseFloat(item.total_qty).toFixed(3)}</td>
                        </tr>`;
                            });
                        } else {
                            tbody = `
                    <tr>
                        <td colspan="3" class="text-center">No data available</td>
                    </tr>`;
                        }
                        $('#Modalfor_quantity_details_item_wise_po tbody').html(tbody);
                    },
                    error: function(xhr, status, error) {
                        alert('Error fetching data: ' + error);
                    }
                });
            }

            function get_so_items_for_report(current_date) {
                let CurrentDate = current_date;
                $.ajax({
                    url: "{{ url('get_so_qty') }}",
                    method: "POST",
                    data: {
                        CurrentDate: CurrentDate,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        // Log the response to the console to check structure

                        // Make sure rows_data exists and is an array
                        let rowsData = res.data; // Ensure this matches the 'data' key in the response
                        let tableBody = document.querySelector('.modal-body-so-item table tbody');
                        tableBody.innerHTML = ''; // Clear existing table rows

                        // Loop through the response data and add rows to the table
                        rowsData.forEach((rowData, index) => {
                            // Parse the date string and format it
                            let date = new Date(rowData.due_date);
                            let formattedDate = date.toLocaleDateString('en-GB', {
                                day: '2-digit',
                                month: 'short', // Abbreviated month name
                                year: 'numeric'
                            }).replace(' ', '');
                            let row = `<tr>
                    <th scope="row">${index + 1}</th>
                     <td>${formattedDate}</td>
                    <td>${rowData.so_number}</td>
                     <td>${rowData.company_name}</td>
                    <td>${rowData.name}</td>
                        <td>${parseFloat(rowData.total_quantity).toFixed(3)}</td>
                </tr>`;
                            tableBody.insertAdjacentHTML('beforeend', row);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log('Error:', error);
                        // Optionally show an error message to the user
                    }
                });
            }

            function get_po_items_for_report(current_date) {
                let CurrentDate = current_date;
                $.ajax({
                    url: "{{ url('get_po_qty') }}",
                    method: "POST",
                    data: {
                        CurrentDate: CurrentDate,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        let rowsData = res.data; // Ensure this matches the 'data' key in the response
                        let tableBody = document.querySelector('.modal-body-po-item table tbody');
                        tableBody.innerHTML = ''; // Clear existing table rows

                        // Loop through the response data and add rows to the table
                        rowsData.forEach((rowData, index) => {
                            // Parse the date string and format it
                            let date = new Date(rowData.due_date);
                            let formattedDate = date.toLocaleDateString('en-GB', {
                                day: '2-digit',
                                month: 'short', // Abbreviated month name
                                year: 'numeric'
                            }).replace(' ', '');
                            let row = `<tr>
                    <th scope="row">${index + 1}</th>
                     <td>${formattedDate}</td>
                    <td>${rowData.po_number}</td>
                     <td>${rowData.company_name}</td>
                    <td>${rowData.name}</td>
                        <td>${parseFloat(rowData.total_quantity).toFixed(3)}</td>
                </tr>`;
                            tableBody.insertAdjacentHTML('beforeend', row);
                        });
                    },
                    error: function(xhr, status, error) {
                        console.log('Error:', error);
                        // Optionally show an error message to the user
                    }
                });
            }


            function openModalWithCategory(category_id) {
                // Update the 'Show More Details' link with the category_id
                const link = document.getElementById('showMoreDetailsLink');
                const baseUrl = "{{ route('po_report', ':id') }}";
                const updatedUrl = baseUrl.replace(':id', category_id);
                link.setAttribute('href', updatedUrl);

                // Set the category_id in the modal and fetch data
                get_received_qty_for_report(category_id);
            }

            function openModalWithCategorySOPartyWise(party_id) {
                console.log(party_id);
                // Update the 'Show More Details' link with the category_id
                const link = document.getElementById('showMoreDetailsLinkSoPartyWise');
                const baseUrl = "{{ route('so_report_party_wise', ':id') }}";
                const updatedUrl = baseUrl.replace(':id', party_id);
                link.setAttribute('href', updatedUrl);

                // Set the category_id in the modal and fetch data
                get_received_so_qty_for_party_wise_report(party_id);
            }

            function openModalWithCategoryPOPartyWise(party_id) {
                console.log(party_id);

                // Update the 'Show More Details' link with the party_id
                const link = document.getElementById('showMoreDetailsLinkPoPartyWise');
                const baseUrl = "{{ route('po_report_party_wise', ':id') }}";
                const updatedUrl = baseUrl.replace(':id', party_id);
                link.setAttribute('href', updatedUrl);

                // Fetch data related to the party_id
                get_received_qty_for_party_wise_report(party_id);
            }


            function get_received_so_qty_for_party_wise_report(company_id) {

                let CompanyId = company_id;
                $.ajax({
                    url: "{{ url('get_received_qty_party_wise') }}",
                    method: "POST",
                    data: {
                        company_id: CompanyId,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        // console.log(res); // Log the response to the console
                        let rowsData = res.rows_data;
                        let tableBody = document.querySelector('.modal-body-so-item-party-wise table  tbody');
                        tableBody.innerHTML = ''; // Clear existing table rows

                        rowsData.forEach((rowData, index) => {
                            // Parse the date string and format it
                            let date = new Date(rowData.date);
                            let formattedDate = date.toLocaleDateString('en-GB', {
                                day: '2-digit',
                                month: '2-digit', // Use '2-digit' for numeric month or 'short' for abbreviated text month
                                year: 'numeric'
                            });
                            let row = `<tr>
                                <th scope="row">${index + 1}</th>
                                 <td>${rowData.category_name}</td>
                                   <td>${parseFloat(rowData.total_quantity).toFixed(3)}</td>
                            </tr>`;
                            tableBody.insertAdjacentHTML('beforeend', row);
                        });
                    }


                });
            }

            function get_received_qty_for_party_wise_report(company_id) {
                let CompanyId = company_id;
                $.ajax({
                    url: "{{ url('get_received_qty_po_party_wise') }}",
                    method: "POST",
                    data: {
                        company_id: CompanyId,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        // console.log(res); // Log the response to the console
                        let rowsData = res.rows_data;
                        let tableBody = document.querySelector('.modal-body-po-item-party-wise table  tbody');
                        tableBody.innerHTML = ''; // Clear existing table rows

                        rowsData.forEach((rowData, index) => {
                            // Parse the date string and format it
                            let date = new Date(rowData.date);
                            let formattedDate = date.toLocaleDateString('en-GB', {
                                day: '2-digit',
                                month: '2-digit', // Use '2-digit' for numeric month or 'short' for abbreviated text month
                                year: 'numeric'
                            });
                            let row = `<tr>
                                <th scope="row">${index + 1}</th>
                                 <td>${rowData.category_name}</td>
                                    <td>${parseFloat(rowData.total_quantity).toFixed(3)}</td>
                            </tr>`;
                            tableBody.insertAdjacentHTML('beforeend', row);
                        });
                    }


                });
            }
        </script>
    </main><!-- End #main -->
@endsection
