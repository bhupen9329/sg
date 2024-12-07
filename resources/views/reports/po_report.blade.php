@extends('layouts.main')
@section('title', 'Purchase Order Reports - Saraswati Globals')
@section('content')
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
            <h1>Purchase Order Report</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Purchase Order Report</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <div class="dashboard-header pagetitle">
            <h1>Purchase Order Report</h1>
            <div class="row" style="align-items: flex-end;">
                <div class="col-md-12 col-sm-12 d-flex justify-content-end">


                    <button class="m-1 btn btn-primary" type="button"
                        onclick="filterButton(
                $('#filterTodate').val(),
                $('#filterFromdate').val(),
                $('#filterCompany').val(),  
                $('#filterCategory').val(),
                $('#filterstatus').val(),
                $('#filteruser').val(),

            )">
                        Apply
                    </button>
                    <button class="m-1 btn btn-primary" type="button" id="resetButton">Reset</button>
                </div>
            </div>

            <div class="page-header">
                <div class="row">
                    <div class="col-md-2 col-sm-12" style="margin-top: 7px">
                        <label for="filterTodate"><strong>From Date</strong></label>
                        <?php
                        $firstDayOfMonth = (new DateTime('first day of this month'))->format('Y-m-d');
                        ?>

                        <input type="date" class="form-control" name="to_date" id="filterTodate"
                            value="<?php echo $firstDayOfMonth; ?>" required>
                    </div>
                    <div class="col-md-2 col-sm-12" style="margin-top: 7px">
                        <label for="filterFromdate"><strong>To Date</strong></label>
                        <?php
                        $lastDayOfMonth = (new DateTime('last day of this month'))->format('Y-m-d');
                        ?>
                        <input type="date" class="form-control" name="from_date" id="filterFromdate"
                            value="<?php echo $lastDayOfMonth; ?>" required>
                    </div>
                    {{-- <div class="col-md-2 col-sm-12">
                        <label for="filterCompany" class="mb-2"><strong>Company</strong></label>
                        <select class="custom-select form-control company-select" name="company_id" id="filterCompany"
                            required>
                            <option value="" disabled>Select Company</option>
                            <option value="all" selected>All</option>
                            @foreach ($companys as $company)
                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                            @endforeach
                        </select>
                    </div> --}}

                    <div class="col-md-2 col-sm-12">
                        <label for="filterCompany" class="mb-2"><strong>Company</strong></label>
                        <select class="custom-select form-control company-select" name="company_id" id="filterCompany" required>
                            <option value="" disabled {{ is_null($selectedCompany) ? 'selected' : '' }}>Select Company</option>
                            <option value="all" {{ is_null($selectedCompany) ? 'selected' : '' }}>All</option>
                            @foreach ($companys as $company)
                            <option value="{{ $company->id }}" 
                                {{ isset($selectedCompany) && $selectedCompany->id == $company->id ? 'selected' : '' }}>
                                {{ $company->company_name }}
                            </option>
                            
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="filterCategory" class="mb-2"><strong>Category</strong></label>
                        <select class="custom-select form-control item_select" name="category" id="filterCategory" required>
                            <option value="" disabled>Select Category</option>
                            <option value="all" {{ is_null($selectedCategory) ? 'selected' : '' }}>All</option>
                            @foreach ($Categorys as $category)
                                <option value="{{ $category->id }}" 
                                    {{ isset($selectedCategory) && $selectedCategory->id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    

                    <div class="col-md-2 col-sm-12">
                        <label for="filterCategory" class="mb-2"><strong>Dispatch Status</strong></label>
                        <select class="custom-select form-control item_select   " name="category" id="filterstatus"
                            required>
                            <option value="all">All</option>
                            <option value="Open">Open</option>
                            <option value="Partial Pending">Partial Pending</option>
                            <option value="Not Close" selected>Not Close</option>
                            <option value="Close">Close</option>
                            <option value="Pre Closed">Pre Closed</option>
                            <option value="Cancelled">Cancelled</option>

                        </select>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="filterCategory" class="mb-2"><strong>Purchase Person</strong></label>
                        <select class="custom-select form-control item_select" name="filteruser" id="filteruser" required>
                            <option value="all">All</option>
                            @foreach ($user as $users)
                                <option value="{{ $users->id }}">{{ $users->name }}</option>
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
                                        <h4 class="text-blue h4">Purchase Order Report</h4>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 d-flex justify-content-end ">
                                    {{-- <div class="btn-group">
                                        @can('Company-create')
                                            <a class="btn btn-primary mb-4 mr-3" data-bs-toggle="modal"
                                                data-bs-target="#SelectTypeModal">
                                                New Inward</a>
                                        @endcan
                                    </div> --}}
                                </div>
                            </div>
                            <!-- Table with stripped rows -->
                            <table class="table " id="Category_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>PO Date​</th>
                                        <th>PO No.</th>
                                        <th>PO Item No.</th>
                                        <th>Seller Name(Party Name)​</th>
                                        <th>Base Item Name​</th>
                                        <th>PO Unit Price</th>
                                        <th>PO Quantity</th>
                                        <th>Balanced PO Quantity</th>
                                        <th>Dispatched Quantity</th>
                                        <th>PO Dispatch Status</th>
                                        <th>PO Person</th>

                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                                <tfoot id="">
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align:center;"><strong>Grand Total:</strong></td>
                                        <td></td>
                                        <td></td>
                                        <td style="text-align:left; font-weight: bold;" id="totalPoUnitPrice">0</td>
                                        <td style="text-align:left; font-weight: bold;" id="totalPoQty">0</td>
                                        <td style="text-align:left; font-weight: bold;" id="totalBalancedQty">0</td>
                                        <td style="text-align:left; font-weight: bold;" id="totalDispatchedQty">0</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main><!-- End #main -->


    <!-- Modal  -->
    <div class="modal fade" id="Modalfor_quantity_details" tabindex="-1" aria-labelledby="modal3Label"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal3Label">Received Quantity - History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"style="width:50px"></button>
                </div>
                <div class="modal-body">
                    <h6 class="text-end py-3"><strong>PO Quantity</strong> : <span id="add_total_qty"></span></h6>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Date</th>
                                <th scope="col">Received Qty</th>
                                <th scope="col">Balance Qty</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- csv  print   --}}
    <script>
        // $(document).ready(function() {
        //     $('.item_select').select2();
        //     $('.company-select').select2();
        //     $('.mode-select').select2();
        //     $('.status-select').select2();
        // });

        $(document).ready(function() {
            $('.custom-select').select2();
            // Focus the search box when the subcategory dropdown is opened
            $('.custom-select').on('select2:open', function() {
                document.querySelector('.select2-search__field').focus();
            });
        });

        $(document).ready(function() {
            // Initialize DataTable
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
                        title: 'Saraswati Globals (PO Report)',

                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7],
                        }
                    },
                    {
                        extend: 'print',
                        text: 'PRINT',
                        title: 'Saraswati Globals (PO Report)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7],
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

            // Modify button styles
            $('.dt-buttons button').addClass('custom-button');

            // Add additional CSS styles

            $('.custom-button, .paginate_button').css({
                'padding': '5px 10px', // Adjust padding as needed
                'font-size': '10px' // Adjust font size as needed
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            // Call filterButton on page load with default or initial filter values
            filterButton(
                $('#filterTodate').val(),
                $('#filterFromdate').val(),
                $('#filterCompany').val(),
                $('#filterCategory').val(),
                $('#filterstatus').val(),
                $('#filteruser').val(),
            );
        });


        function filterButton() {
            const filterTodate = $('#filterTodate').val();
            const filterFromdate = $('#filterFromdate').val();
            const filterCompany = $('#filterCompany').val();
            const filterCategory = $('#filterCategory').val();
            const filterStatus = $('#filterstatus').val();
            const filterUser = $('#filteruser').val();

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
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (Array.isArray(response)) {
                        var table = $('#Category_table').DataTable();
                        table.clear().draw();

                        var totalPOUnitPrice = 0;
                        var totalPOQty = 0;
                        var totalBalancedQty = 0;
                        var totalDispatchedQty = 0;

                        response.forEach(function(data, index) {
                            const dispatched_qty = (data.quantity - data.rest_quantity)
                        .toLocaleString();

                            totalPOUnitPrice += parseFloat(data.po_unit_price) || 0;
                            totalPOQty += parseFloat(data.quantity) || 0;
                            totalBalancedQty += parseFloat(data.rest_quantity) || 0;
                            totalDispatchedQty += parseFloat(dispatched_qty.replace(/,/g, '')) || 0;

                            const rowNode = table.row.add([
                                index + 1,
                                data.date,
                                data.po_document_number,
                                data.po_item_number,
                                data.company_name,
                                data.category,
                                data.po_unit_price,
                                data.quantity,
                                data.rest_quantity,
                                dispatched_qty,
                                data.dispatch_status,
                                data.user_name
                            ]).draw(false).node();

                            // Conditional styling for rows
                            if (data.rest_quantity != 0) {
                                $(rowNode).find('td:eq(8)').css({
                                    'background-color': '#ff3300',
                                    'color': 'black',
                                });
                            } else {
                                $(rowNode).find('td').css({
                                    'background-color': '#15ff00',
                                    'color': 'black',
                                });
                            }
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
            location.reload();
        });
    </script>



    <script>
        $(document).ready(function() {
            $('.table.dataTable').removeClass('no-footer');
        });
    </script>

    <script>
        function get_received_qty_for_report(po_id, rest_qty) {
            let get_po_id = po_id;
            let total_balance_qty = rest_qty;

            // console.log(po_id, total_balance_qty);
            $.ajax({
                url: "{{ url('get_received_qty') }}",
                method: "POST",
                data: {
                    po_id: get_po_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    // console.log(res); // Log the response to the console
                    let rowsData = res.rows_data;
                    let totalQty = res.total_qty;
                    $('#add_total_qty').html(totalQty);

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
                                        <td>${rowData.received_qty}</td>
                                        <td>${rowData.balance_qty}</td>
                                    </tr>`;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });
                }


            });
        }
    </script>
@endsection
