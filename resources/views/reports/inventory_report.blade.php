@extends('layouts.main')
@section('title', 'Inventory Reports - Saraswati Globals')
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
            <h1>Inventory Report</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Inventory Report</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <div class="dashboard-header pagetitle">
            <h1>Inventory Report </h1>
            <div class="row" style="align-items: flex-end;">
                <div class="col-md-12 col-sm-12 d-flex justify-content-end">


                    <button class=" m-1 btn btn-primary" type="button"
                        onclick="filterButton(
                $('#filterCategory').val(),

            )">
                        Apply
                    </button>
                    <button class=" m-1 btn btn-primary" type="button" id="resetButton">Reset</button>
                </div>
            </div>

            <div class="page-header">
                <div class="row">
                    {{-- <div class="col-md-2 col-sm-12" style="margin-top: 7px">
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
                    </div> --}}

                    <div class="col-md-2 col-sm-12">
                        <label for="filterCategory" class="mb-2"><strong>Base Item</strong></label>
                        <select class="custom-select form-control category-select" name="category" id="filterCategory"
                            required>
                            <option value="all" selected>All</option>
                            @foreach ($category as $categorys)
                                <option value="{{ $categorys->id }}">{{ $categorys->name }}</option>
                            @endforeach
                        </select>
                    </div>



                </div>
            </div>
        </div>

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
                                    <th scope="col">Date</th>
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

        <div class="modal fade" id="Modalfor_quantity_details_so" tabindex="-1" aria-labelledby="modal3Label"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal3Label">Sales Order Quantity - History</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"style="width:50px"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
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

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row ">
                                <div class="col-md-6 col-sm-12">
                                    <div class="pd-20">
                                        <h4 class="text-blue h4">Inventory Report</h4>
                                    </div><br>
                                </div>
                                <div class="col-md-6 col-sm-12 d-flex justify-content-end ">
                                </div>
                            </div>
                            <!-- Table with stripped rows -->
                            <table class="table " id="Category_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Base Item</th>
                                        <th>Total PO Qty</th>
                                        <th>Total SO Qty</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

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
                        title: 'Saraswati Globals (Outward  Report)',

                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
                        }
                    },
                    {
                        extend: 'print',
                        text: 'PRINT',
                        title: 'Saraswati Globals (Outward   Report)',
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
        function filterButton(filterCategory) {
            $.ajax({
                type: 'POST',
                url: 'report-inventory',
                data: {
                    filterCategory: filterCategory,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response) {
                        var table = $('#Category_table').DataTable();
                        table.clear().draw();

                        // Check if filteredPOTotal and filteredSOTotal are arrays
                        if (Array.isArray(response.filteredPOTotal) && Array.isArray(response
                            .filteredSOTotal)) {
                            // Loop through filteredPOTotal and add rows to the table
                            response.filteredPOTotal.forEach(function(poData, index) {
                                table.row.add([
                                    index + 1,
                                    poData.category_name,
                                    '<a href="#" style="text-decoration: underline; color: blue;" data-bs-toggle="modal" data-bs-target="#Modalfor_quantity_details" class="rest-quantity-link" onclick="get_received_qty_for_report(' +
                                    poData.category_id + ')">' + poData.total_quantity + '</a>',
                                    ''

                                ]).draw(false);
                            });

                            // Loop through filteredSOTotal and add rows to the table
                            response.filteredSOTotal.forEach(function(soData, index) {
                                // Find a matching row by category name to update the SO Quantity, if it exists
                                var rowFound = false;
                                table.rows().every(function(rowIdx, tableLoop, rowLoop) {
                                    var data = this.data();
                                    if (data[1] === soData
                                        .category_name) { // Compare category name
                                        data[3] =
                                            '<a href="#" style="text-decoration: underline; color: blue;" data-bs-toggle="modal" data-bs-target="#Modalfor_quantity_details_so" class="rest-quantity-link" onclick="get_received_qty_for_report(' +
                                            soData.po_id + ', ' + soData.rest_quantity + ')">' +
                                            soData.total_quantity + '</a>',
                                            this.data(data).draw(false);
                                        rowFound = true;
                                        return false; // Break out of loop
                                    }
                                });

                                // If no matching PO row found, add new row with SO data
                                if (!rowFound) {
                                    table.row.add([
                                        table.rows().count() + 1,
                                        soData.category_name, // SO Category Name
                                        '', // Placeholder for PO Quantity
                                        soData.total_quantity // SO Total Quantity
                                    ]).draw(false);
                                }
                            });
                        } else {
                            console.error("Invalid or empty response data received.");
                        }
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
            location.reload();
        });
    </script>




    <script>
        $(document).ready(function() {
            $('.table.dataTable').removeClass('no-footer');
        });
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
                    let tableBody = document.querySelector('.modal-body table tbody');
                    tableBody.innerHTML = ''; // Clear existing table rows

                    rowsData.forEach((rowData, index) => {
                        // Parse the date string and format it
                        let date = new Date(rowData.date);
                        let formattedDate = date.toLocaleDateString('en-US', {
                            day: '2-digit',
                            month: 'short',
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
    </script>
@endsection
