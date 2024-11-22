@extends('layouts.main')
@section('title', 'Purchase Order Due reports - Saraswati Globals')
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
            <h1>Due Purchase Order Report</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Due Purchase Order Report</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <div class="dashboard-header pagetitle">
            <h1>Due Purchase Order Report</h1>
            <div class="row" style="align-items: flex-end;">
                <div class="col-md-12 col-sm-12 d-flex justify-content-end">


                    <button class=" m-1 btn btn-primary" type="button"
                        onclick="filterButton(
                $('#filterCompany').val(),
                $('#filterCategory').val(),
               
            )">
                        Apply
                    </button>
                    <button class=" m-1 btn btn-primary" type="button" id="resetButton">Reset</button>
                </div>
            </div>

            <div class="page-header">
                <div class="row">
                    {{-- <div class="col-md-3 col-sm-12" style="margin-top: 7px">
                        <label for="filterTodate"><strong>From Date</strong></label>
                        <?php
                        $firstDayOfMonth = (new DateTime('first day of this month'))->format('Y-m-d');
                        ?>
                        <input type="date" class="form-control" max="" value="<?php echo $firstDayOfMonth; ?>" name="to_date"
                            id="filterTodate" required>
                    </div>
                    <div class="col-md-3 col-sm-12" style="margin-top: 7px">
                        <label for="filterFromdate"><strong>To Date</strong></label>
                        <?php
                        $lastDayOfMonth = (new DateTime('last day of this month'))->format('Y-m-d');
                        ?>
                        <input type="date" class="form-control" value="<?php echo $lastDayOfMonth; ?>" name="from_date"
                            id="filterFromdate" required>
                    </div> --}}

                    
                    <div class="col-md-2 col-sm-12">
                        <label for="filterCompany" class="mb-2"><strong>Due Date</strong></label>
                        <select class="custom-select form-control" name="due_date" id="filterDueDate" required>
                            <option value="" disabled>Select Type</option>
                            <option value="all" selected>All</option>
                            <option value="due_future">Due in Future</option>
                            <option value="due_by_today">Due by Today</option>
                            <option value="due_today">Due Today</option>
                        </select>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="filterCompany" class="mb-2"><strong>Company</strong></label>
                        <select class="custom-select form-control" name="company_id" id="filterCompany" required>
                            <option value="" disabled>Select Company</option>
                            <option value="all" selected>All</option>
                            @foreach ($companys as $company)
                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="filterCategory" class="mb-2"><strong>Category</strong></label>
                        <select class="custom-select form-control" name="category" id="filterCategory" required>
                            <option value="" disabled>Select Category</option>
                            <option value="all" selected>All</option>
                            @foreach ($Categorys as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <div class="col-md-2 col-sm-12">
                        <label for="filterCategory" class="mb-2"><strong>Dispatch Status</strong></label>
                        <select class="custom-select form-control item_select   " name="category" id="filterstatus"
                            required>
                            <option value="all">All</option>
                            <option value="Open">Open</option>
                            <option value="Partial Pending">Partial Pending</option>
                            <option value="Not Close" selected>Not Close</option>
                            <option value="Close">Close</option>

                        </select>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="filterCategory" class="mb-2"><strong>Sales Person</strong></label>
                        <select class="custom-select form-control item_select" name="filteruser" id="filteruser" required>
                            <option value="all">All</option>
                            @foreach ($user as $users)
                                <option value="{{ $users->id }}">{{ $users->name }}</option>
                            @endforeach

                        </select>
                    </div> --}}

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
                                        <h4 class="text-blue h4">Due Purchase Order Report</h4>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 d-flex justify-content-end ">
                                </div>
                            </div>
                            <!-- Table with stripped rows -->
                            <div class="table-responsive">
                                <table class="table " id="Category_table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>PO Due Date​</th>
                                            <th>PO No.</th>
                                            <th>PO Item No.</th>
                                            <th>Seller Name(Party Name)​</th>
                                            <th>Base Item Name​</th>
                                            <th>PO Quantity</th>
                                            <th>Balanced PO Quantity</th>
                                            <th>Dispatched Quantity</th>
                                            <th>PO Dispatch Status</th>
                                            <th>Purchase Person</th>
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
                                            <td style="text-align:left; font-weight: bold;" id="totalSOQty">0</td>
                                            <td style="text-align:left; font-weight: bold;" id="totalRestQty">0</td>
                                            <td style="text-align:left; font-weight: bold;" id="totalDispatchedQty">0</td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
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
                        title: 'Saraswati Globals (SO Report)',

                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
                        }
                    },
                    {
                        extend: 'print',
                        text: 'PRINT',
                        title: 'Saraswati Globals (SO Report)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 12, 13, 14, 15, ],
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

$(document).ready(function () {
        // Call filterButton on page load with default or initial filter values
        filterButton(
            $('#filterCompany').val(),
            $('#filterCategory').val()
        );
    });
    
    function filterButton(filterCompany, filterCategory) {
        const filterStatus = $('#filterstatus').val();
        const filteruser = $('#filteruser').val();
        
        $.ajax({
            type: 'POST',
            url: 'get_due_po_report',
            data: {
                filterCompany: filterCompany,
                filterCategory: filterCategory,
                _token: "{{ csrf_token() }}"
            },
            success: function(response) {
                if (response && Array.isArray(response)) {
                    var table = $('#Category_table').DataTable();
                    table.clear().draw();

                    // Initialize total variables
                    var totalSOUnitPrice = 0;
                    var totalSOQty = 0;
                    var totalRestQty = 0;
                    var totalDispatchedQty = 0;

                    response.forEach(function(data, index) {
                        // Calculate dispatched quantity
                        const dispatched_qty = (data.quantity - data.rest_qty).toLocaleString();

                        // Update totals
                        totalSOQty += parseFloat(data.quantity) || 0;
                        totalRestQty += parseFloat(data.rest_qty) || 0;
                        totalDispatchedQty += parseFloat(dispatched_qty.replace(/,/g, '')) || 0;

                        // Add row to DataTable
                        table.row.add([
                            index + 1,
                            data.date,
                            data.po_number,
                            data.po_item_number,
                            data.company_name,
                            data.category,
                            data.quantity,
                            data.rest_qty,
                            dispatched_qty,
                            data.dispatch_status,
                            data.user_name,
                        ]).draw(false);
                    });

                    // Update grand totals in the footer
                    $('#totalSOQty').text(totalSOQty.toFixed(2));
                    $('#totalRestQty').text(totalRestQty.toFixed(2));
                    $('#totalDispatchedQty').text(totalDispatchedQty.toFixed(2));
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

        $(document).ready(function() {
            $('.custom-select').select2();
            // Focus the search box when the subcategory dropdown is opened
            $('.custom-select').on('select2:open', function() {
                document.querySelector('.select2-search__field').focus();
            });
        });
    </script>
@endsection
