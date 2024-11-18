@extends('layouts.main')
@section('title', 'Dashboard- Saraswati Globals')
@section('content')
    <main id="main" class="main">

        <div class="dashboard-header pagetitle">
            <h1>Dashboard</h1>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <style>
                .card {
                    padding-top: 0px !important;
                }


                .dashboard_dataTables_wrapper {
                    height: 213px;
                    overflow-y: scroll;
                }

                /* .dashboard_dataTables_wrapper_low {
                                        height: 600px;
                                        overflow-y: scroll;
                                    } */

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

                                            {{ $sales_order }}
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
                                            {{ $purchase_order }}
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
                                        <th scope="col">SO Item Number</th>
                                        <th scope="col">Base Item</th>
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
                                        <th scope="col">PO Item Number</th>
                                        <th scope="col">Base Item</th>
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

            {{-- .................................................................................................................................... --}}
            <div class="row">


                <div class="col-lg-6 px-2 py-4 ">
                    <div class="row dashboard-container">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Due Sales Order Date</h5>

                                    <!-- Table with stripped rows -->
                                    <div class="dashboard_dataTables_wrapper_low">
                                        <table class="table datatable">
                                            <thead>
                                                <tr>
                                                    <th>Sales Order</th>
                                                    <th>Total Rest Quantity</th>
                                                    <th>Due SO Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($sales_order_due_date as $data)
                                                    <tr>
                                                        <td>{{ $data['so_number'] }}</td>
                                                        <td>
                                                            <a href="#"
                                                                style="text-decoration: underline; color: blue;"
                                                                data-bs-toggle="modal" data-bs-target="#so_items"
                                                                class="rest-quantity-link"
                                                                onclick="get_so_items_for_report({{ $data['so_id'] }})">
                                                                {{ $data['total_quantity'] }}
                                                            </a>
                                                        </td>

                                                        <td>{{ date('d-M-Y', strtotime($data['due_date'])) }}</td>

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
                                    <h5 class="card-title">Due Purchase Order Date</h5>

                                    <!-- Table with stripped rows -->
                                    <div class="dashboard_dataTables_wrapper_low">
                                        <table class="table datatable">
                                            <thead>
                                                <tr>
                                                    <th>Purchase Order</th>
                                                    <th>Total Rest Quantity</th>
                                                    <th>Due PO Date</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($purchase_order_due_date as $data)
                                                    <tr>
                                                        <td>{{ $data['document_number'] }}</td>
                                                        <td>
                                                            <a href="#"
                                                                style="text-decoration: underline; color: blue;"
                                                                data-bs-toggle="modal" data-bs-target="#po_items"
                                                                class="rest-quantity-link"
                                                                onclick="get_po_items_for_report({{ $data['po_id'] }})">

                                                                {{ $data['total_quantity'] }}
                                                            </a>
                                                        </td>
                                                        <td>{{ date('d-M-Y', strtotime($data['due_date'])) }}</td>
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


            <div class="row">
                <div class="col-lg-6 px-2 py-4 ">
                    <div class="row dashboard-container">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Stock Party Wise​ </h5>

                                    <!-- Table with stripped rows -->
                                    <div class="dashboard_dataTables_wrapper_low">
                                        <table class="table datatable">
                                            <thead>
                                                <tr>
                                                    <th>Base Item</th>
                                                    <th>Total SO Qty</th>
                                                    <th>Total PO Qty</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($mergedTotals as $total)
                                                    <tr>
                                                        <td>{{ $total['category_name'] }}</td>
                                                        <td>
                                                            <a href="#"
                                                                style="text-decoration: underline; color: blue;"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#Modalfor_quantity_details_so"
                                                                class="rest-quantity-link"
                                                                onclick="get_received_so_qty_for_report({{ $total['category_id'] }})">
                                                                {{ $total['so_total_quantity'] }}
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <a href="#"
                                                                style="text-decoration: underline; color: blue;"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#Modalfor_quantity_details"
                                                                class="rest-quantity-link"
                                                                onclick="get_received_qty_for_report({{ $total['category_id'] }})">
                                                                {{ $total['po_total_quantity'] }}
                                                            </a>
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
                                    <h5 class="card-title">Latest Stock Details</h5>

                                    <!-- Table with stripped rows -->
                                    <div class="dashboard_dataTables_wrapper_low">
                                        <table class="table datatable">
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
                                                                    <td style="padding: 8px;">{{ $data['item_name'] ?? 'N/A' }}</td>
                                                                @endif
                                                            @endforeach
        
                                                            {{-- <td style="padding: 8px;">{{ $data['transaction_type'] ?? 'N/A' }}</td> --}}
                                                            {{-- <td style="padding: 8px;">{{ $data['quantity'] ?? 'N/A' }}</td> --}}
        
                                                            <!-- LIFO Transactions -->
                                                            @foreach ($lifo_transaction as $lifo_transactions)
                                                                @if ($data['id'] == $lifo_transactions['transaction_id'] && $data['item_id'] == $lifo_transactions['item_id'])
                                                                    <td style="padding: 8px;">
                                                                        {{ number_format($lifo_transactions['balance_qty'], 2) ?? 'N/A' }}
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
                                                        <td colspan="7" style="padding: 8px; text-align: center;">No data
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

        </section>

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
                                <td>${rowData.qty}</td>
                                 <td>${rowData.po_dispatch_rest_qty}</td>
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
                                <td>${rowData.qty}</td>
                                 <td>${rowData.so_dispatch_rest_qty}</td>
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
                            <td>${item.total_qty}</td>
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
                            <td>${item.total_qty}</td>
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

            function get_so_items_for_report(so_id) {
                let SoId = so_id;
                $.ajax({
                    url: "{{ url('get_so_qty') }}",
                    method: "POST",
                    data: {
                        SoId: SoId,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        // Log the response to the console to check structure
                        console.log(res);

                        // Make sure rows_data exists and is an array
                        let rowsData = res.data; // Ensure this matches the 'data' key in the response
                        let tableBody = document.querySelector('.modal-body-so-item table tbody');
                        tableBody.innerHTML = ''; // Clear existing table rows

                        // Loop through the response data and add rows to the table
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
                    <td>${rowData.so_item_no}</td>
                    <td>${rowData.name}</td>
                    <td>${rowData.so_dispatch_rest_qty}</td>
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

            function get_po_items_for_report(po_id) {
                let PoId = po_id;
                $.ajax({
                    url: "{{ url('get_po_qty') }}",
                    method: "POST",
                    data: {
                        PoId: PoId,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        let rowsData = res.data; // Ensure this matches the 'data' key in the response
                        let tableBody = document.querySelector('.modal-body-po-item table tbody');
                        tableBody.innerHTML = ''; // Clear existing table rows

                        // Loop through the response data and add rows to the table
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
                    <td>${rowData.po_item_no}</td>
                    <td>${rowData.name}</td>
                    <td>${rowData.po_dispatch_rest_qty}</td>
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
        </script>
    </main><!-- End #main -->
@endsection
