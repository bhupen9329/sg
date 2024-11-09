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
                                            <th>So No.</th>
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
                                            <th>Receivable Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                            </div>

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
        function filterButton(filterTodate, filterFromdate, filterItem_name,filterCompany) {
            $.ajax({
                type: 'POST',
                url: '/dispatch-report-get',
                data: {
                    filterTodate: filterTodate,
                    filterFromdate: filterFromdate,
                    filterItem_name: filterItem_name,
                    filterCompany: filterCompany,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response && Array.isArray(response)) {
                        var table = $('#Category_table').DataTable();
                        table.clear().draw();
                        response.forEach(function(data, index) {
                            table.row.add([
                                index + 1,
                                data.po_company,
                                data.so_company,
                                data.created_at,
                                data.category_name,
                                data.sub_category_name,
                                data.dispatched_quantity,
                                data.vehicle_number ?? 'N/A',
                                data.po_item_no,
                                data.dispatch_total,
                                data.so_item_no,
                                data.dispatch_so_total,
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
            // Reload the page to reset filters
            location.reload();
        });
    </script>



    <script>
        $(document).ready(function() {
            $('.table.dataTable').removeClass('no-footer');
        });
    </script>
@endsection
data.po_company,
data.so_company,
data.created_at,
data.category_name,
data.sub_category_name,
data.dispatched_quantity,
data.vehicle_number,
data.po_item_no,
data.dispatch_total,
data.po_item_no,
data.dispatch_so_total,
