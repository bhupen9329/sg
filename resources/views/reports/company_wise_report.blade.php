@extends('layouts.main')
@section('title', 'Company Wise Reports - Saraswati Globals')
@section('content')
    <style>

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
            <h1>Company Wise Report</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Company Wise Report</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <div class="dashboard-header pagetitle">
            <h1>Company Wise Report </h1>
            <div class="row" style="align-items: flex-end;">
                <div class="col-md-12 col-sm-12 d-flex justify-content-end">


                    <button class=" m-1 btn btn-primary" type="button"
                        onclick="filterButton(
                $('#filterTodate').val(),
                $('#filterFromdate').val(),
                $('#filterCompany').val(),
            )">
                        Apply
                    </button>
                    <button class=" m-1 btn btn-primary" type="button" id="resetButton">Reset</button>
                </div>
            </div>

            <div class="page-header">
                <div class="row">
                    <div class="col-md-2 col-sm-12" style="margin-top: 7px">
                        <label for="filterTodate"><strong>From Date</strong></label>
                        <?php
                        $firstDayOfMonth = (new DateTime('first day of this month'))->format('Y-m-d');
                        ?>
                        <input type="date" class="form-control" value="<?php echo $firstDayOfMonth; ?>" name="to_date"
                            id="filterTodate" required>
                    </div>
                    <div class="col-md-2 col-sm-12" style="margin-top: 7px">
                        <label for="filterFromdate"><strong>To Date</strong></label>
                        <?php
                        $lastDayOfMonth = (new DateTime('last day of this month'))->format('Y-m-d');
                        ?>
                        <input type="date" class="form-control" value="<?php echo $lastDayOfMonth; ?>" name="from_date"
                            id="filterFromdate" required>
                    </div>
                    <div class="col-md-2 col-sm-12">
                        <label for="filterItem_name" class="mb-2"><strong>Company </strong></label>
                        <select class="custom-select form-control company-select" name="company" id="filterCompany"
                            required>
                            <option value="all" selected>All</option>
                            @foreach ($company as $data)
                                <option value="{{ $data->id }}">{{ $data->company_name }}</option>
                            @endforeach
                        </select>
                    </div>



                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row ">
                                <div class="col-md-6 col-sm-12">
                                    <div class="pd-20">
                                        <h4 class="text-blue h4">Company Wise Report</h4>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 d-flex justify-content-end ">
                                </div>
                            </div>
                            <!-- Table with stripped rows -->
                            <div style="overflow-x: scroll">
                                <table class="table " id="Category_table">
                                    <thead>
                                        <tr>
                                            {{-- <th>So No.</th>
                                            <th>Form</th>
                                            <th> To</th>
                                            <th>Dispatch date</th>
                                            <th>Item Name</th>
                                            <th>Con Item Name</th>
                                            <th>Quantity</th>
                                            <th>Vehicle Number</th>
                                            <th>PO Item No.</th>
                                            <th>Payable total</th>
                                            <th>SO Item No.</th>
                                            <th>Receivable Total</th> --}}

                                            <th>#</th>
                                            <th class="text_descriptions">Company Name</th>
                                            <th class="text_descriptions">Year</th>
                                            <th class="text_descriptions">Month</th>
                                            <th class="text_descriptions">Total SO Quantity</th>
                                            <th class="text_descriptions">Total PO Quantity</th>

                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td style="text-align:center;"><strong>Grand Total:</strong></td>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align:left; font-weight: bold;" id="totalDispatchSoTotal">0</td>
                                            <td style="text-align:left; font-weight: bold;" id="totalDispatchPoTotal">0</td>

                                        </tr>
                                    </tfoot>

                                </table>
                            </div>

                            <!-- End Table with stripped rows -->
                            {{-- <div class="modal fade" id="Modalfor_quantity_details_so" tabindex="-1"
                                aria-labelledby="modal3Label" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modal3Label">Payable Total Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"style="width:50px"></button>
                                        </div>
                                        <div class="modal-body-so">
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
                            </div> --}}

                            <div class="modal fade" id="Modalfor_quantity_details_so" tabindex="-1"
                                aria-labelledby="modal3Label" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modal3Label">Payable Total Details</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"style="width:50px"></button>
                                        </div>
                                        <div class="modal-body-so">
                                            <h6 class="text-end mt-2" style="margin-right: 20px;"><strong>Dispatched
                                                    Qty</strong> : <span id="so_add_qty"></span></h6>
                                            <h6 class="text-end mt-1" style="margin-right: 20px;"><strong>Payable
                                                    Total</strong> : <span id="so_add_total_qty"></span></h6>
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


                            <div class="modal fade" id="Modalfor_quantity_details_po" tabindex="-1"
                                aria-labelledby="modal3Label" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">

                                            <h5 class="modal-title" id="modal3Label">Receivable Total Details</h5>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body-po">
                                            <h6 class="text-end mt-2" style="margin-right: 20px;"><strong>Dispatched
                                                    Qty</strong> : <span id="add_qty"></span></h6>
                                            <h6 class="text-end mt-1" style="margin-right: 20px;"><strong>Receivable
                                                    Total</strong> : <span id="add_total_qty"></span></h6>
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

                            {{-- <div class="modal fade" id="Modalfor_quantity_details_po" tabindex="-1"
                                aria-labelledby="modal3Label" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">

                                            <h5 class="modal-title" id="modal3Label">Payable Total Details</h5>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body-po">
                                            <h6 class="text-end py-3"><strong>PO Quantity</strong> : <span
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
                            </div> --}}

                            <div class="modal fade" id="Modalfor_quantity_details_po" tabindex="-1"
                                aria-labelledby="modal3Label" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">

                                            <h5 class="modal-title" id="modal3Label">Receivable Total Details</h5>

                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body-po">
                                            <h6 class="text-end mt-2" style="margin-right: 20px;"><strong>Dispatched
                                                    Qty</strong> : <span id="add_qty"></span></h6>
                                            <h6 class="text-end mt-1" style="margin-right: 20px;"><strong>Receivable
                                                    Total</strong> : <span id="add_total_qty"></span></h6>
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
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main><!-- End #main -->



    {{-- csv  print   --}}
    <script>
        $(document).ready(function() {
            $('.custom-select').select2();
            // Focus the search box when the subcategory dropdown is opened
            $('.custom-select').on('select2:open', function() {
                document.querySelector('.select2-search__field').focus();
            });

        });

        $(document).ready(function() {
            var table = $('#Category_table').DataTable({
                dom: 'Bfrtip',
                lengthMenu: [
                    [10, 20, 50, 100, 150, -1],
                    ['10 rows', '20 rows', '50 rows', '100 rows', '150 rows', 'Show all']
                ],
                buttons: [
                    'pageLength',
                    {
                        extend: 'csv',
                        text: 'CSV',
                        title: 'Saraswati Globals (Dispatch  Report)',

                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
                        }
                    },
                    {
                        extend: 'print',
                        text: 'PRINT',
                        title: 'Saraswati Globals (Dispatch   Report)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, ],
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
                    }
                ]
            });

            $('.dt-buttons button').addClass('custom-button');


            $('.custom-button, .paginate_button').css({
                'padding': '5px 10px',
                'font-size': '10px'
            });
        });
    </script>



    <script>
function filterButton() {
    // Get filter values
    const filterTodate = $('#filterTodate').val();
    const filterFromdate = $('#filterFromdate').val();
    const filterCompany = $('#filterCompany').val();
    const filterCategory = $('#filterCategory').val();
    const filterStatus = $('#filterstatus').val();
    const filterUser = $('#filteruser').val();

    // Send the AJAX request
    $.ajax({
        type: 'POST',
        url: 'report-po',
        data: {
            filterTodate: filterTodate,
            filterFromdate: filterFromdate,
            filterCompany: filterCompany,
            filterCategory: filterCategory,
            filterStatus: filterStatus,
            filterUser: filterUser,
            _token: "{{ csrf_token() }}"  // CSRF token for security
        },
        success: function(response) {
            // Check if response is an array
            if (Array.isArray(response)) {
                var table = $('#Category_table').DataTable();
                table.clear().draw();  // Clear existing data

                var totalPOUnitPrice = 0;
                var totalPOQty = 0;
                var totalBalancedQty = 0;
                var totalDispatchedQty = 0;

                // Loop through each response item
                response.forEach(function(data, index) {

                    const dispatched_qty = (data.quantity - data.rest_quantity).toLocaleString();  // Calculate dispatched quantity

                    totalPOUnitPrice += parseFloat(data.po_unit_price) || 0;  // Total unit price
                    totalPOQty += parseFloat(data.quantity) || 0;  // Total quantity
                    totalBalancedQty += parseFloat(data.rest_quantity) || 0;  // Total remaining quantity
                    totalDispatchedQty += parseFloat(dispatched_qty.replace(/,/g, '')) || 0;  // Total dispatched quantity

                    // Conditional styling for rest_quantity cell
                    let restQuantityCell = data.rest_quantity;
                    let restQuantityStyle = '';
                    
                    // Apply background color based on conditions
                    if (data.rest_quantity === 0) {
                        restQuantityStyle = 'background-color: #FF0000';  // Red for rest_quantity 0
                    } else if (data.rest_quantity !== data.quantity) {
                        restQuantityStyle = 'background-color: #FFBF00';  // Yellow for non-equal rest_quantity
                    }

                    // Add row to DataTable with inline styling for rest_quantity
                    table.row.add([
                        index + 1,
                        data.date,
                        data.po_document_number,
                        data.po_item_number,
                        data.company_name,
                        data.category,
                        data.po_unit_price,
                        data.quantity,
                        `<td style="${restQuantityStyle}">${restQuantityCell}</td>`, // Apply conditional styling here
                        dispatched_qty,
                        data.dispatch_status,
                        data.user_name
                    ]).draw(false);
                });

                // Update totals
                $('#totalPoUnitPrice').text(totalPOUnitPrice.toFixed(2));
                $('#totalPoQty').text(totalPOQty.toFixed(2));
                $('#totalBalancedQty').text(totalBalancedQty.toFixed(2));
                $('#totalDispatchedQty').text(totalDispatchedQty.toFixed(2));
            } else {
                console.log("Invalid response format");
            }
        },
        error: function(xhr, status, error) {
            console.error("AJAX request failed:", error);
        }
    });
}


        $('#resetButton').click(function() {
            // Reload the page to reset filters
            location.reload();
        });
    </script>




    <script>
        $(document).ready(function() {
            $('.table.dataTable').removeClass('no-footer');
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
                    <th scope="row">3</th>
                    <td>Freight Rate</td>
                    <td>${res.dispatch_freight ?? 0}</td>
                </tr>`,
                            `<tr>
                    <th scope="row">4</th>
                    <td>Insurance Rate</td>
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
                    <th scope="row">3</th>
                    <td>Freight Rate</td>
                    <td>${res.dispatch_freight ?? 0}</td>
                </tr>`,
                            `<tr>
                    <th scope="row">4</th>
                    <td>Insurance Rate</td>
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

@endsection
