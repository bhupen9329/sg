@extends('layouts.main')
@section('title', 'Index - New Dispatch')
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
        @if ($message = Session::get('error'))
            <div class="tt active">
                <div class="tt-content">
                    <i class="fas fa-solid fa-times-circle error-icon"></i>
                    <div class="message">
                        <span class="text text-1">Error</span>
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
            <h1>New Dispatch Summary</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Open New Dispatch Positions</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="pd-20">
                                        <h4 class="text-blue h4">New Dispatch</h4>
                                    </div>
                                </div>


                                <div class="col-md-6 col-sm-12 d-flex justify-content-end">
                                    <div class="btn-group">
                                        @can('Dispatch-create')
                                            <button type="button" class="btn btn-primary dropdown-toggle mb-4 mr-3" data-bs-toggle="dropdown" aria-expanded="false">
                                                Add Dispatch
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item" href="{{ route('new.dispatch.create') }}">Dispatch from PO</a></li>
                                                <li><a class="dropdown-item" href="{{ route('new.dispatch.create_so') }}">Dispatch from SO</a></li>
                                            </ul>
                                        @endcan
                                    </div>
                                </div>
                                

                            
                            </div>
                            <div style="overflow-x: auto">
                                <table class="table " id="Category_table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th style="width: 72.8125px;">Dispatch Date</th>
                                            <th style="width: 72.8125px;">Dispatch Number</th>

                                            <th style="width: 72.8125px;">Vehicle Number</th>
                                            <th style="width: 84.8125px;">From (Party Name)</th>
                                            <th style="width: 84.8125px;">PO Item No</th>
                                            <th style="width: 84.8125px;">PO Item Unit Price</th>
                                            <th>PO Gross Price</th>
                                            <th>Category</th>
                                            <th>Conv Item Name</th>
                                            <th>Dispatch Qty</th>
                                            <th style="width: 84.8125px;">To (Party Name)</th>
                                            <th>SO Item No</th>
                                            <th style="width: 84.8125px;">SO Item Unit Price</th>
                                            <th>SO Gross Price</th>
                                            <th>Dispatch Type</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($disaptch_data as $data)
                                            @php
                                                $gross_so =
                                                    floatval($data->dispatch_so_unit_price ?? 0) +
                                                    floatval($data->dispatch_so_freight ?? 0) +
                                                    floatval($data->conv_rate ?? 0) +
                                                    floatval($data->dispatch_other ?? 0);
                                    
                                                $gross_po =
                                                    floatval($data->dispatch_unit_price ?? 0) +
                                                    floatval($data->dispatch_freight ?? 0) +
                                                    floatval($data->conv_rate ?? 0) +
                                                    floatval($data->dispatch_other ?? 0);
                                            @endphp
                                    
                                            <tr data-dispatch-number="{{ $data->dispatch_number }}">
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ date('d-M-Y', strtotime($data->date)) }}</td>
                                                <td>{{ $data->dispatch_number ?? 'N/A' }}</td>
                                                <td>{{ $data->vehicle_number ?? 'N/A' }}</td>
                                                <td>{{ $data->po_company }}</td>
                                                <td><a
                                                        href="{{ route('purchase.edit', ['id' => $data->po_id]) }}">{{ $data->po_item_no }}</a>
                                                </td>
                                                <td>{{ $data->dispatch_unit_price }}</td>
                                                <td>
                                                    <a href="javascript:void(0);" data-bs-toggle="modal"
                                                        data-bs-target="#Modalfor_quantity_details_so"
                                                        onclick="get_received_so_qty_for_report('{{ $data->dispatch_id }}')">
                                                        {{ number_format($gross_po, 2) }}
                                                    </a>
                                                </td>
                                    
                                                <td>{{ $data->category_name }}</td>
                                                <td>{{ $data->sub_category_name }}</td>
                                    
                                                <td>{{ number_format($data->dispatched_quantity, 3) }}</td>
                                    
                                                <td>{{ $data->so_company }}</td>
                                    
                                                <td><a
                                                        href="{{ route('sales.edit', ['id' => $data->so_id]) }}">{{ $data->so_item_no }}</a>
                                                </td>
                                                <td>{{ $data->dispatch_so_unit_price }}</td>
                                                <td>
                                                    <a href="javascript:void(0);" data-bs-toggle="modal"
                                                        data-bs-target="#Modalfor_quantity_details_po"
                                                        onclick="get_received_po_qty_for_report('{{ $data->dispatch_id }}')">
                                                        {{ number_format($gross_so, 2) }}
                                                    </a>
                                                </td>

                                                <td>{{ $data->dispatch_type ?? 'DispatchPO'}}</td>
                                    
                                                <td>
                                                    <div class="filter">
                                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                class="bi bi-three-dots"></i></a>
                                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                    
                                                            @can('Dispatch-edit')
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('dispatch.edit', $data->dispatch_number) }}"><i
                                                                            class="fa-solid fa-pencil"></i>Edit</a>
                                                                </li>
                                                            @endcan
                                    
                                                            @can('Dispatch-delete')
                                                                <li>
                                                                    <form method="GET"
                                                                        action="{{ route('dispatch.destroy', $data->dispatch_id) }}">
                                                                        @method('DELETE')
                                                                        <button type="button"
                                                                            class="dropdown-item delete-button">
                                                                            <i class="fa-solid fa-trash"></i> Delete
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            @endcan
                                    
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    
                                </table>
                            </div>




                        </div>
                    </div>
                </div>
            </div>
        </section>
        <br><br><br>

        <div class="modal fade" id="Modalfor_quantity_details_so" tabindex="-1" aria-labelledby="modal3Label"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal3Label">Payable Total Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"style="width:50px"></button>
                    </div>
                    <div class="modal-body-so">
                        <h6 class="text-end mt-2" style="margin-right: 20px;"><strong>Dispatched Qty</strong> : <span
                                id="so_add_qty"></span></h6>
                        <h6 class="text-end mt-1" style="margin-right: 20px;"><strong>Payable Total</strong> : <span
                                id="so_add_total_qty"></span></h6>
                        <table class="table SO table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Rate</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="Modalfor_quantity_details_po" tabindex="-1" aria-labelledby="modal3Label"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">

                        <h5 class="modal-title" id="modal3Label">Receivable Total Details</h5>

                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body-po">
                        <h6 class="text-end mt-2" style="margin-right: 20px;"><strong>Dispatched Qty</strong> : <span
                                id="add_qty"></span></h6>
                        <h6 class="text-end mt-1" style="margin-right: 20px;"><strong>Receivable Total</strong> : <span
                                id="add_total_qty"></span></h6>
                        <table class="table SO table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Rate</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>



    </main><!-- End #main -->





<!--    <script>-->
<!--$(document).ready(function() {-->
<!--    var table = $('#Category_table').DataTable({-->
<!--        dom: 'Bfrtip',-->
<!--        pageLength: 50,-->
<!--        lengthMenu: [-->
<!--            [10, 20, 50, 100, 150, -1],-->
<!--            ['10 rows', '20 rows', '50 rows', '100 rows', '150 rows', 'Show all']-->
<!--        ],-->
<!--        buttons: ['pageLength', 'csv', 'print'],-->
<!--        drawCallback: function(settings) {-->
<!--            var api = this.api();-->
<!--            var lastDispatchNumber = null;-->

<!--            api.rows({ page: 'current' }).every(function(rowIdx, tableLoop, rowLoop) {-->
<!--                var data = this.node();-->
<!--                var dispatchNumber = $(data).data('dispatch-number');-->

<!--                if (dispatchNumber !== lastDispatchNumber) {-->
<!--                    lastDispatchNumber = dispatchNumber;-->

<!--                    $(data).before(-->
<!--                        '<tr class="group"><td colspan="16" style="text-align: center; border-top: 2px solid #8B0000;"></td></tr>'-->
<!--                    );-->
<!--                }-->
<!--            });-->
<!--        }-->
<!--    });-->
<!--});-->

<!--    </script>-->

    <script>
        $(document).ready(function() {
            var table = $('#Category_table').DataTable({
                dom: 'Bfrtip',
                pageLength: 50,
                lengthMenu: [
                    [10, 20, 50, 100, 150, -1],
                    ['10 rows', '20 rows', '50 rows', '100 rows', '150 rows', 'Show all']
                ],
                buttons: ['pageLength',
                    {
                        extend: 'csv',
                        text: 'CSV',
                        title: 'Saraswati Globals (Index-Dispatch)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15],
                        }
                    },
                    {
                        extend: 'print',
                        text: 'PRINT',
                        title: 'Saraswati Globals (Index-Dispatch)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15],
                        },
                        customize: function(win) {
                            $(win.document.body).find('table')
                                .addClass('table')
                                .css({
                                    'margin': '10px',
                                    'padding': '10px'
                                });

                            $(win.document.body).find('h1')
                                .css({
                                    'text-align': 'center',
                                    'font-size': '20px',
                                    'margin-top': '20px'
                                });
                        }
                    }],
                drawCallback: function(settings) {
                    var api = this.api();
                    var lastDispatchNumber = null;
                    var totalQuantity = 0; // Initialize total quantity for each dispatch_number

                    api.rows({
                        page: 'current'
                    }).every(function(rowIdx, tableLoop, rowLoop) {
                        var data = this.node();
                        var dispatchNumber = $(data).data('dispatch-number');
                        var quantity = parseFloat($(data).find('td:eq(10)').text()
                            .trim()); // Get the dispatched quantity from the 11th column

                        if (dispatchNumber !== lastDispatchNumber) {
                            // If a new dispatch number starts, display the sum for the previous one
                            if (lastDispatchNumber !== null) {
                                $(data).before(
                                    '<tr class="group"><td colspan="10" style="text-align: end;font-weight: bold;">Total Qty:</td><td colspan="6" style="text-align: left;font-weight: bold;">' +
                                    totalQuantity.toFixed(3) + '</td></tr>' +
                                    '<tr class="group"><td colspan="17" style="text-align: center; border-top: 2px solid #8B0000;"></td></tr>'
                                );
                            }
                            // Reset the totalQuantity for the new dispatch number
                            totalQuantity = quantity;
                            lastDispatchNumber = dispatchNumber;
                        } else {
                            totalQuantity += quantity; // Add to the total quantity
                        }
                    });

                    // After looping through all rows, display the last total quantity if necessary
                    if (lastDispatchNumber !== null) {
                        $(api.row(':last').node()).after(
                                 '<tr class="group"><td colspan="10" style="text-align: end;font-weight: bold;">Total Qty:</td><td colspan="6" style="text-align: left;font-weight: bold;">' +
                                    totalQuantity.toFixed(3) + '</td></tr>'
                        );
                    }
                }
            });
        });
    </script>

    <script>
        function get_received_so_qty_for_report(dispatch_id) {
            $.ajax({
                url: "{{ url('get_dispatch_payable_total') }}",
                method: "POST",
                data: {
                    dispatch_id: dispatch_id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(res) {
                    let tableBody = document.querySelector('.modal-body-so table tbody');
                    tableBody.innerHTML = ''; // Clear existing table rows

                    // Check if the required properties exist in the response
                    if (res) {
                        // Create rows according to the table structure
                        $('#so_add_qty').html(res.dispatched_quantity);
                        $('#so_add_total_qty').html(res.dispatch_total);


                        let rows = [

                            `<tr>
                        <th scope="row">1</th>
                        <td>PO Unit Rate</td>
                        <td>${res.dispatch_unit_price ?? 0}</td>
                    </tr>`,
                            `<tr>
                        <th scope="row">2</th>
                        <td>Conv Rate</td>
                        <td>${res.conv_rate ?? 0}</td>
                    </tr>`,

                            `<tr>
                        <th scope="row">4</th>
                        <td>Loading + Insurance Rate</td>
                        <td>${res.dispatch_other ?? 0}</td>
                    </tr>`,

                            `<tr>
                        <th scope="row"></th>
                        <td><strong>Total</strong></td>
       <td><strong>${(parseFloat(res.dispatch_unit_price ?? 0) + parseFloat(res.conv_rate ?? 0) + parseFloat(res.dispatch_freight ?? 0) + parseFloat(res.dispatch_other ?? 0)).toFixed(2)}</strong></td>
                    </tr>`,

                        ];

                        // Insert all rows into the table body
                        rows.forEach(row => tableBody.insertAdjacentHTML('beforeend', row));
                    } else {
                        console.error("Dispatch data not found in response");
                    }
                },
                error: function(err) {
                    console.error("An error occurred:", err);
                }
            });
        }
    </script>
    <script>
        function get_received_po_qty_for_report(dispatch_id) {
            $.ajax({
                url: "{{ url('get_dispatch_so_unit_price') }}",
                method: "POST",
                data: {
                    dispatch_id: dispatch_id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(res) {
                    // Clear existing table rows
                    let tableBody = document.querySelector('.modal-body-po table tbody');
                    tableBody.innerHTML = '';

                    // Set total quantity in the span element
                    $('#add_total_qty').html(res.total_qty);
                    $('#add_qty').html(res.dispatched_quantity);


                    // Check if the required properties exist in the response
                    if (res) {
                        // Create rows according to the table structure
                        let rows = [
                            `<tr>
                        <th scope="row">1</th>
                        <td>SO Unit Rate</td>
                        <td>${res.dispatch_so_unit_price ?? 0}</td>
                    </tr>`,
                            `<tr>
                        <th scope="row">2</th>
                        <td>Conv Rate</td>
                        <td>${res.conv_rate ?? 0}</td>
                    </tr>`,

                            `<tr>
                        <th scope="row">4</th>
                        <td>Loading + Insurance Rate</td>
                        <td>${res.dispatch_other ?? 0}</td>
                    </tr>`,
                            `<tr>
                        <th scope="row"></th>
                        <td><strong>Total</strong></td>
       <td><strong>${(parseFloat(res.dispatch_so_unit_price ?? 0) + parseFloat(res.conv_rate ?? 0) + parseFloat(res.dispatch_freight ?? 0) + parseFloat(res.dispatch_other ?? 0)).toFixed(2)}</strong></td>
                    </tr>`,
                        ];

                        // Insert all rows into the table body
                        rows.forEach(row => tableBody.insertAdjacentHTML('beforeend', row));
                    } else {
                        console.error("Dispatch data not found in response");
                    }
                },
                error: function(err) {
                    console.error("An error occurred:", err);
                }
            });
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.table.dataTable').removeClass('no-footer');
        });
    </script>


@endsection
