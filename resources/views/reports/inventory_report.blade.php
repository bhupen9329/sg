@extends('layouts.main')
@section('title', 'Stocks - Saraswati Globals')
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
            <h1>Stocks</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Stocks</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <div class="dashboard-header pagetitle">
            <h1>Stocks </h1>
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
                                    <th scope="col">Due Date</th>
                                    <th scope="col">Due Days</th>
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
                    <div class="modal-body-so">
                        <table class="table SO table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Due Date</th>
                                    <th scope="col">Due Days</th>
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
                                        <h4 class="text-blue h4">Stocks</h4>
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
                        title: 'Saraswati Globals (Stock Report)',

                        exportOptions: {
                            columns: [0, 1, 2, 3],
                        }
                    },
                    {
                        extend: 'print',
                        text: 'PRINT',
                        title: 'Saraswati Globals (Stock Report)',
                        exportOptions: {
                            columns: [0, 1, 2, 3],
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

                        // Initialize a map to handle merging of PO and SO data
                        var categoryMap = {};

                        // Process PO totals
                        if (Array.isArray(response.filteredPOTotal)) {
                            response.filteredPOTotal.forEach(function(poData) {
                                categoryMap[poData.category_name] = {
                                    poQuantity: poData.total_quantity,
                                    soQuantity: "N/A",
                                    poCategoryId: poData.category_id,
                                    soCategoryId: null
                                };
                            });
                        }

                        // Process SO totals
                        if (Array.isArray(response.filteredSOTotal)) {
                            response.filteredSOTotal.forEach(function(soData) {
                                if (categoryMap[soData.category_name]) {
                                    // Update existing entry with SO data
                                    categoryMap[soData.category_name].soQuantity = soData
                                    .total_quantity;
                                    categoryMap[soData.category_name].soCategoryId = soData.category_id;
                                } else {
                                    // Add new entry for SO data
                                    categoryMap[soData.category_name] = {
                                        poQuantity: "N/A",
                                        soQuantity: soData.total_quantity,
                                        poCategoryId: null,
                                        soCategoryId: soData.category_id
                                    };
                                }
                            });
                        }

                        // Add rows to the table
                        Object.keys(categoryMap).forEach(function(categoryName, index) {
                            var data = categoryMap[categoryName];
                            var poQuantity = parseFloat(data.poQuantity).toFixed(3);
                            var soQuantity = parseFloat(data.soQuantity).toFixed(3);

                            table.row.add([
                                index + 1,
                                categoryName,
                                data.poQuantity !== "N/A" ?
                                '<a href="#" style="text-decoration: underline; color: blue;" data-bs-toggle="modal" data-bs-target="#Modalfor_quantity_details" class="rest-quantity-link" onclick="get_received_qty_for_report(' +
                                data.poCategoryId + ')">' + poQuantity + '</a>' : "N/A",
                                data.soQuantity !== "N/A" ?
                                '<a href="#" style="text-decoration: underline; color: blue;" data-bs-toggle="modal" data-bs-target="#Modalfor_quantity_details_so" class="rest-quantity-link" onclick="get_received_so_qty_for_report(' +
                                data.soCategoryId + ')">' + soQuantity + '</a>' : "N/A"
                            ]).draw(false);
                        });
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
                    let tableBody = document.querySelector('.modal-body table  tbody');
                    tableBody.innerHTML = ''; // Clear existing table rows

                    rowsData.forEach((rowData, index) => {
                        // Parse the date string and format it
                        let date = new Date(rowData.date);
                        let due_date = new Date(rowData.due_date);
                        let formattedDate = date.toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: '2-digit', // Use '2-digit' for numeric month or 'short' for abbreviated text month
                            year: 'numeric'
                        });

                        let formattedDueDate = due_date.toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: '2-digit', // Use '2-digit' for numeric month or 'short' for abbreviated text month
                            year: 'numeric'
                        });

                        let today = new Date();
                        let timeDifference = due_date.getTime() - today.getTime();
                        let differenceInDays = Math.ceil(timeDifference / (1000 * 60 * 60 * 24));


                        let row = `<tr>
                                    <th scope="row">${index + 1}</th>
                                    <td>${formattedDate}</td>
                                             <td>${formattedDueDate}</td>
                                    <td>${differenceInDays}</td>
                                     <td>${rowData.company_name}</td>
                                    <td>${rowData.document_number}</td>
                                    <td> ${parseFloat(rowData.qty).toFixed(3)}</td>
                                     <td> ${parseFloat(rowData.po_dispatch_rest_qty).toFixed(3)}</td>
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
                        let due_date = new Date(rowData.due_date);
                        let formattedDate = date.toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: '2-digit', // Use '2-digit' for numeric month or 'short' for abbreviated text month
                            year: 'numeric'
                        });

                        let formattedDueDate = due_date.toLocaleDateString('en-GB', {
                            day: '2-digit',
                            month: '2-digit', // Use '2-digit' for numeric month or 'short' for abbreviated text month
                            year: 'numeric'
                        });

                        let today = new Date();
                        let timeDifference = due_date.getTime() - today.getTime();
                        let differenceInDays = Math.ceil(timeDifference / (1000 * 60 * 60 * 24));

                        let row = `<tr>
                                    <th scope="row">${index + 1}</th>
                                    <td>${formattedDate}</td>
                                    <td>${formattedDueDate}</td>
                                    <td>${differenceInDays}</td>
                                     <td>${rowData.company_name}</td>
                                    <td>${rowData.so_number}</td>
                                           <td> ${parseFloat(rowData.qty).toFixed(3)}</td>
                                     <td> ${parseFloat(rowData.so_dispatch_rest_qty).toFixed(3)}</td>
                                </tr>`;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });
                }


            });
        }
    </script>
@endsection
