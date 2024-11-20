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
                url: 'company-wise-report-get',
                data: {
                    filterTodate: filterTodate,
                    filterFromdate: filterFromdate,
                    filterCompany: filterCompany,
                    filterCategory: filterCategory,
                    filterStatus: filterStatus,
                    filterUser: filterUser,
                    _token: "{{ csrf_token() }}" // CSRF token for security
                },
                success: function(response) {
                    // Check if response is an array
                    if (Array.isArray(response)) {
                        var table = $('#Category_table').DataTable();
                        table.clear().draw(); // Clear existing data

                        var totalSOQty = 0;
                        var totalPOQty = 0;

                        // Loop through each response item
                        response.forEach(function(data, index) {

                            totalSOQty += data.total_rest_qty_so;
                            totalPOQty += data.total_rest_qty_po;

                            table.row.add([
                                index + 1,
                                data.companies,
                                data.year,
                                data.month,
                                data.total_rest_qty_so,
                                data.total_rest_qty_po,
                            ]).draw(false);
                        });

                        // Update totals
                        $('#totalDispatchSoTotal').text(totalSOQty.toFixed(3));
                        $('#totalDispatchPoTotal').text(totalPOQty.toFixed(3));
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




@endsection
