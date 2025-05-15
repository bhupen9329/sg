@extends('layouts.main')
@section('title', 'Dispatch Reports - Saraswati Globals')
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
            <h1>Dispatch Report</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Dispatch Report</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <div class="dashboard-header pagetitle">
            <h1>Dispatch Report </h1>
            <div class="row" style="align-items: flex-end;">
                <div class="col-md-12 col-sm-12 d-flex justify-content-end">


                    <button class=" m-1 btn btn-primary" type="button"
                        onclick="filterButton(
                $('#filterTodate').val(),
                $('#filterFromdate').val(),
                $('#filterItem_name').val(),
                $('#filterCompany').val(),
                $('#filterDispatch').val(),
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
                        $currentDate = new DateTime();
                        $currentYear = $currentDate->format('Y');
                        $financialYearStart = new DateTime(($currentDate->format('m') >= 4 ? $currentYear : $currentYear - 1) . '-04-01');
                        ?>
                        <input type="date" class="form-control" name="to_date" id="filterTodate"
                            value="<?php echo $financialYearStart->format('Y-m-d'); ?>" required>
                    </div>
                    <div class="col-md-2 col-sm-12" style="margin-top: 7px">
                        <label for="filterFromdate"><strong>To Date</strong></label>
                        <?php
                        $financialYearEnd = new DateTime(($currentDate->format('m') >= 4 ? $currentYear + 1 : $currentYear) . '-03-31');
                        ?>
                        <input type="date" class="form-control" name="from_date" id="filterFromdate"
                            value="<?php echo $financialYearEnd->format('Y-m-d'); ?>" required>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="filterItem_name" class="mb-2"><strong>Item Name</strong></label>
                        <select class="custom-select form-control company-select" name="company_id" id="filterItem_name"
                            required>
                            <option value="all" selected>All</option>
                            @foreach ($category as $data)
                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                            @endforeach
                        </select>
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

                    <div class="col-md-2 col-sm-12">
                        <label for="filterItem_name" class="mb-2"><strong>Dispatch Number</strong></label>
                        <select class="custom-select form-control company-select" name="dispatch_number" id="filterDispatch"
                            required>
                            <option value="all" selected>All</option>
                            @foreach ($dispatch as $data)
                                <option value="{{ $data->dispatch_number }}">{{ $data->dispatch_number }}</option>
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
                                        <h4 class="text-blue h4">Dispatch Report</h4>
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
                                            <th style="width: 72.8125px;">Dispatch Date</th>
                                            <th style="width: 72.8125px;">Dispatch Number</th>
                                            <th style="width: 72.8125px;">Vehicle Number</th>
                                            <th style="width: 84.8125px;">From (Party Name)</th>
                                            <th style="width: 84.8125px;">PO Item No</th>
                                            <th>Payable Total</th>
                                            <th>Category</th>
                                            <th>Conv Item Name</th>
                                            <th>Dispatch Qty</th>
                                            <th style="width: 84.8125px;">To (Party Name)</th>
                                            <th>SO Item No</th>
                                            <th>Receivable Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align:center;"><strong>Grand Total:</strong></td>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align:left; font-weight: bold;" id="totalDispatchTotal">0</td>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align:left; font-weight: bold;" id="totalDispatchedQty">0</td>
                                            <td></td>
                                            <td></td>
                                            <td style="text-align:left; font-weight: bold;" id="totalDispatchSoTotal">0
                                            </td>
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

        $(document).ready(function () {
    var table = $('#Category_table').DataTable({
        dom: 'Bfrtip',
        lengthMenu: [
            [10, 20, 50, 100, 150, -1],
            ['10 rows', '20 rows', '50 rows', '100 rows', '150 rows', 'Show all']
        ],
        buttons: [
            'pageLength',

            // CSV Export with footer totals manually added
            {
                extend: 'csvHtml5',
                text: 'CSV',
                title: 'Saraswati Globals (Dispatch Report)',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12]
                },
                customize: function (csv) {
                    var footer = [
                        '', '', '', 'Grand Total:',
                        '', '',
                        $('#totalDispatchTotal').text(),
                        '', '', 
                        $('#totalDispatchedQty').text(),
                        '', '',
                        $('#totalDispatchSoTotal').text()
                    ];
                    var footerRow = '\n"' + footer.join('","') + '"';
                    return csv + footerRow;
                }
            },

        

            // Print with visible footer
            {
                extend: 'print',
                text: 'PRINT',
                title: 'Saraswati Globals (Dispatch Report)',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                    footer: true
                },
                customize: function (win) {
                    // Style table
                    $(win.document.body).find('table')
                        .addClass('table')
                        .css({ 'margin': '10px', 'padding': '10px' });

                    // Style title
                    $(win.document.body).find('h1')
                        .css({ 'text-align': 'center', 'font-size': '20px', 'margin-top': '20px' });

                    // Append the original footer
                    var tfoot = $('#Category_table').find('tfoot').clone();
                    $(win.document.body).find('table').append(tfoot);
                }
            }
        ]
    });

    // Style DataTable buttons
    $('.dt-buttons button').addClass('custom-button');
    $('.custom-button, .paginate_button').css({
        'padding': '5px 10px',
        'font-size': '10px'
    });
});


        // $(document).ready(function() {
        //     var table = $('#Category_table').DataTable({
        //         dom: 'Bfrtip',
        //         lengthMenu: [
        //             [10, 20, 50, 100, 150, -1],
        //             ['10 rows', '20 rows', '50 rows', '100 rows', '150 rows', 'Show all']
        //         ],
        //         buttons: [
        //             'pageLength',
        //             {
        //                 extend: 'csv',
        //                 text: 'CSV',
        //                 title: 'Saraswati Globals (Dispatch  Report)',

        //                 exportOptions: {
        //                     columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
        //                 }
        //             },
        //             {
        //                 extend: 'print',
        //                 text: 'PRINT',
        //                 title: 'Saraswati Globals (Dispatch   Report)',
        //                 exportOptions: {
        //                     columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
        //                 },
        //                 customize: function(win) {
        //                     $(win.document.body).find('table')
        //                         .addClass('table')
        //                         .css({
        //                             'margin': '10px',
        //                             'padding': '10px'
        //                         });

        //                     $(win.document.body).find('h1')
        //                         .css({
        //                             'text-align': 'center',
        //                             'font-size': '20px',
        //                             'margin-top': '20px'
        //                         });
        //                 }
        //             }
        //         ]
        //     });

        //     $('.dt-buttons button').addClass('custom-button');


        //     $('.custom-button, .paginate_button').css({
        //         'padding': '5px 10px',
        //         'font-size': '10px'
        //     });
        // });
    </script>


<script>

    $(document).ready(function () {
        filterButton(
      $('#filterTodate').val(),
                $('#filterFromdate').val(),
                $('#filterItem_name').val(),
                $('#filterCompany').val(),
                $('#filterDispatch').val(),
        );
    });


    function filterButton(filterTodate, filterFromdate, filterItem_name, filterCompany, filterDispatch) {
        $.ajax({
            type: 'POST',
            url: '/dispatch-report-get',
            data: {
                filterTodate: filterTodate,
                filterFromdate: filterFromdate,
                filterItem_name: filterItem_name,
                filterCompany: filterCompany,
                filterDispatch: filterDispatch,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response && Array.isArray(response)) {
                    var table = $('#Category_table').DataTable();
                    table.clear().draw();

                    // Initialize total variables
                    var totalDispatchTotal = 0;
                    var totalDispatchSoTotal = 0;
                    var totalDispatchedQty = 0;

                    response.forEach(function(data, index) {
                        const dispatchTotal = parseFloat(data.dispatch_total) || 0;
                        const dispatchSoTotal = parseFloat(data.dispatch_so_total) || 0;
                        const dispatchedQty = parseFloat(data.dispatched_quantity) || 0;

                        let dispatchedqty = dispatchedQty.toFixed(3);

                        // Update grand totals
                        totalDispatchTotal += dispatchTotal;
                        totalDispatchSoTotal += dispatchSoTotal;
                        totalDispatchedQty += dispatchedQty;

                        // Add row to the table
                        table.row.add([
                            index + 1,
                            data.disaptch_date,
                            data.dispatch_number,
                            data.vehicle_number ?? 'N/A',
                            data.po_company,
                            data.po_item_no,
                            `<a href="javascript:void(0);" data-bs-toggle="modal" 
                               data-bs-target="#Modalfor_quantity_details_so" 
                               onclick="get_received_so_qty_for_report('${data.dispatch_id}')">
                               ${dispatchTotal.toFixed(2)}
                            </a>`,
                            data.category_name,
                            data.sub_category_name ?? 'N/A',
                            dispatchedqty,
                            data.so_company,
                            data.so_item_no,
                            `<a href="javascript:void(0);" data-bs-toggle="modal" 
                               data-bs-target="#Modalfor_quantity_details_po" 
                               onclick="get_received_po_qty_for_report('${data.dispatch_id}')">
                               ${dispatchSoTotal.toFixed(2)}
                            </a>`,
                        ]).draw(false);
                    });

                    // Update grand totals in the footer
                    $('#totalDispatchTotal').text(totalDispatchTotal.toFixed(2));
                    $('#totalDispatchSoTotal').text(totalDispatchSoTotal.toFixed(2));
                    $('#totalDispatchedQty').text(totalDispatchedQty.toFixed(3));

                } else {
                    console.error("Invalid or empty response received.");
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX request failed:", status, error);
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
                    <td>Loading + Insurance Rate</td>
                    <td>${res.dispatch_other ?? 0}</td>
                </tr>`,

                            `<tr>
                    <th scope="row"></th>
                    <td><strong>Total</strong></td>
   <td><strong>${(parseFloat(res.dispatch_unit_price ?? 0) + parseFloat(res.dispatch_other ?? 0) + parseFloat(res.conv_rate ?? 0)).toFixed(2)}</strong></td>
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
                    <td>Loading + Insurance Rate</td>
                    <td>${res.dispatch_other ?? 0}</td>
                </tr>`,
                            `<tr>
                    <th scope="row"></th>
                    <td><strong>Total</strong></td>
   <td><strong>${(parseFloat(res.dispatch_so_unit_price ?? 0) + parseFloat(res.dispatch_other ?? 0) + parseFloat(res.conv_rate ?? 0)).toFixed(2)}</strong></td>
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
