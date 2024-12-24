@extends('layouts.main')
@section('title', 'Sales Order Due reports - Saraswati Globals')
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
            <h1>Sales Order Report</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Sales Order Report</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <div class="dashboard-header pagetitle">
            <h1>Sales Order Report</h1>
            <div class="row" style="align-items: flex-end;">
                <div class="col-md-12 col-sm-12 d-flex justify-content-end">


                    <button class=" m-1 btn btn-primary" type="button"
                        onclick="filterButton(
                $('#filterTodate').val(),
                $('#filterFromdate').val(),
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
                    <div class="col-md-3 col-sm-12" style="margin-top: 7px">
                        <label for="filterTodate"><strong>From Date</strong></label>
                        <?php
                        $currentDate = new DateTime();
                        $currentYear = $currentDate->format('Y');
                        $financialYearStart = new DateTime(($currentDate->format('m') >= 4 ? $currentYear : $currentYear - 1) . '-04-01');
                        ?>
                        <input type="date" class="form-control" value="<?php echo $financialYearStart->format('Y-m-d'); ?>" name="to_date"
                            id="filterTodate" required>
                    </div>
                    <div class="col-md-3 col-sm-12" style="margin-top: 7px">
                        <label for="filterFromdate"><strong>To Date</strong></label>
                        <?php
                        $financialYearEnd = new DateTime(($currentDate->format('m') >= 4 ? $currentYear + 1 : $currentYear) . '-03-31');
                        ?>
                        <input type="date" class="form-control" value="<?php echo $financialYearEnd->format('Y-m-d'); ?>" name="from_date"
                            id="filterFromdate" required>
                    </div>
                    

                    <div class="col-md-2 col-sm-12">
                        <label for="filterCompany" class="mb-2"><strong>Company</strong></label>
                        <select class="custom-select form-control" name="company_id" id="filterCompany" required>
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
                        <select class="custom-select form-control" name="category" id="filterCategory" required>
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
                        <label for="filterCategory" class="mb-2"><strong>Sales Person</strong></label>
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

            <!-- Modal  -->
    <div class="modal fade" id="Modalfor_quantity_details" tabindex="-1" aria-labelledby="modal3Label"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal3Label">Dispatched Quantity - History</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"style="width:50px"></button>
            </div>
            <div class="modal-body">
                <h6 class="text-end py-3"><strong>Total Dispatched Quantity</strong> : <span id="add_total_qty"></span></h6>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Dispathed Date</th>
                            <th scope="col">Dispatch Number</th>
                            <th scope="col">Base Item</th>
                            <th scope="col">From</th>
                            <th scope="col">PO unit price</th>
                            <th scope="col">To</th>
                            <th scope="col">SO unit price</th>
                            <th scope="col">Dispatched Qty</th>
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
                                        <h4 class="text-blue h4">Sales Order Report</h4>
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
                                            <th>SO Date​</th>
                                            <th>SO No.</th>
                                            <th>SO Item No.</th>
                                            <th>Buyer Name(Party Name)​</th>
                                            <th>Base Item Name​</th>
                                            <th>SO Unit Price</th>
                                            <th>SO Quantity</th>
                                            <th>Dispatched Quantity</th>
                                            <th>Balanced SO Quantity</th>
                                            <th>SO Dispatch Status</th>
                                            <th>Sales Person</th>
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
                                            <td style="text-align:left; font-weight: bold;" id="totalSOUnitPrice">0</td>
                                            <td style="text-align:left; font-weight: bold;" id="totalSOQty">0</td>
                                            <td style="text-align:left; font-weight: bold;" id="totalDispatchedQty">0</td>
                                            <td style="text-align:left; font-weight: bold;" id="totalRestQty">0</td>
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
               $('#filterTodate').val(),
                $('#filterFromdate').val(),
                $('#filterCompany').val(),
                $('#filterCategory').val(),
        );
    });

function filterButton(filterTodate, filterFromdate, filterCompany, filterCategory) {
    const filterStatus = $('#filterstatus').val();
    const filteruser = $('#filteruser').val();

    $.ajax({
        type: 'POST',
        url: 'report-so',
        data: {
            filterTodate: filterTodate,
            filterFromdate: filterFromdate,
            filterCompany: filterCompany,
            filterCategory: filterCategory,
            filterStatus: filterStatus,
            filteruser: filteruser,
            _token: "{{ csrf_token() }}"
        },
        success: function (response) {
            if (response && Array.isArray(response)) {
                var table = $('#Category_table').DataTable();
                table.clear().draw();

                // Initialize total variables
                var totalSOUnitPrice = 0;
                var totalSOQty = 0;
                var totalRestQty = 0;
                var totalDispatchedQty = 0;

                response.forEach(function (data, index) {
            // Parse quantities as numbers
            const quantity = parseFloat(data.quantity) || 0;
            const rest_qty = parseFloat(data.rest_qty) || 0;
            const dispatched_qty = (quantity - rest_qty).toFixed(3); // Calculate dispatched quantity and format it

            // Update totals
            totalSOUnitPrice += parseFloat(data.so_unit_price) || 0;
            totalSOQty += quantity;
            totalRestQty += rest_qty;
            totalDispatchedQty += parseFloat(dispatched_qty) || 0;

            // Add row to DataTable
            const rowNode = table.row.add([
                index + 1,
                data.date,
                data.so_number,
                data.so_item_number,
                data.company_name,
                data.category,
                parseFloat(data.so_unit_price).toFixed(2), // Format SO Unit Price
                quantity.toFixed(3), // Format Quantity
            
                `<a href="javascript:void(0);" data-bs-toggle="modal" 
                               data-bs-target="#Modalfor_quantity_details" 
                               onclick="get_received_so_qty_for_report('${data.so_item_id}', ' ${dispatched_qty}')">
                               ${dispatched_qty}
                            </a>`,
                // dispatched_qty, // Already formatted
                rest_qty.toFixed(3), // Format Rest Quantity
                data.dispatch_status,
                data.user_name,
            ]).draw(false).node();

            // Optionally add additional styles to the row or other cells
            if (rest_qty !== 0) {
                $(rowNode).find('td:eq(9)').css({
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

                // Update grand totals in the footer
                $('#totalSOUnitPrice').text(totalSOUnitPrice.toFixed(2));
                $('#totalSOQty').text(totalSOQty.toFixed(3));
                $('#totalRestQty').text(totalRestQty.toFixed(3));
                $('#totalDispatchedQty').text(totalDispatchedQty.toFixed(2));
            } else {
                console.error("Invalid or empty response received.");
            }
        },
        error: function (xhr, status, error) {
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


function get_received_so_qty_for_report(so_item_id, total_dispatched_qty) {
let get_so_item_id = so_item_id;
let totalDispatchedQty = total_dispatched_qty;

$.ajax({
    url: "{{ url('get_dispatch_qty') }}",
    method: "POST",
    data: {
        get_so_item_id: get_so_item_id,
        total_dispatched: totalDispatchedQty,
        "_token": "{{ csrf_token() }}",
    },
    success: function(res) {
        let rowsData = res.received_qty_records;
        let totalQty = res.total_dispatched;

        // Update total dispatched quantity
        $('#add_total_qty').html(parseFloat(totalQty).toFixed(3));

        let tableBody = document.querySelector('.modal-body table tbody');
        tableBody.innerHTML = ''; // Clear existing table rows

        rowsData.forEach((rowData, index) => {
            // Parse the date string and format it
            let date = new Date(rowData.dispatch_date);
            let formattedDate = date.toLocaleDateString('en-US', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });

            // Format the dispatched quantity to 3 decimal places
            let dispatchedQuantity = parseFloat(rowData.dispatched_quantity || 0).toFixed(3);

            let row = `<tr>
                            <th scope="row">${index + 1}</th>
                            <td>${formattedDate ?? 'N/A'}</td>
                            <td>${rowData.dispatch_number ?? 'N/A'}</td>
                             <td>${rowData.category_name ?? 'N/A'}</td>
                            <td>${rowData.po_company ?? 'N/A'}</td>
                            <td>${rowData.po_unit_price ?? 'N/A'}</td>
                            <td>${rowData.so_company ?? 'N/A'}</td>
                            <td>${rowData.so_unit_price ?? 'N/A'}</td>
                            <td>${dispatchedQuantity}</td>
                        </tr>`;
            tableBody.insertAdjacentHTML('beforeend', row);
        });
    },
    error: function(xhr, status, error) {
        console.error("AJAX request failed:", error);
    }
});
}

    </script>
@endsection


