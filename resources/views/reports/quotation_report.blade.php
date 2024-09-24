@extends('layouts.main')
@section('title','Quotation Reports - Saraswati Globals')
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
            <h1>Quotation Report</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Quotation Report</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <div class="dashboard-header pagetitle">
            <h1>Quotation Report </h1>
            <div class="row" style="align-items: flex-end;">
                <div class="col-md-12 col-sm-12 d-flex justify-content-end">


                    <button class="m-1 btn btn-primary" type="button"
                        onclick="filterButton(
                $('#filterTodate').val(),
                $('#filterFromdate').val(),
                  $('#filterCompany').val()
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
                        <input type="date" class="form-control" value="<?php echo $firstDayOfMonth; ?>" name="to_date" id="filterTodate" required>
                    </div>
                    <div class="col-md-2 col-sm-12" style="margin-top: 7px">
                        <label for="filterFromdate"><strong>To Date</strong></label>
                        <?php
                        $lastDayOfMonth = (new DateTime('last day of this month'))->format('Y-m-d');
                        ?>
                        <input type="date" class="form-control" name="from_date" value="<?php echo $lastDayOfMonth; ?>" id="filterFromdate" required>
                    </div>

                    {{-- <div class="col-md-2 col-sm-12">
                        <label for="filterCompany" class="mb-2"><strong>Company</strong></label>
                        <select class="custom-select form-control" name="company_id" id="filterCompany" required>
                            <option value="" disabled>Select Company</option>
                            <option value="all" selected>All</option>
                            @foreach ($companys as $company)
                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
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
                                        <h4 class="text-blue h4">Quotation Report</h4>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 d-flex justify-content-end ">
                                </div>
                            </div>
                            <!-- Table with stripped rows -->
                            <table class="table " id="Category_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date(MM/DD/YY)​</th>
                                        <th>Quotation Number</th>
                                        <th>Buyer​</th>
                                        <th>Total PCs</th>
                                        <th>Total Weight</th>
                                        <th>GST Type</th>
                                        <th>Loading Charges</th>
                                        <th>Additional Charges</th>
                                        <th>Freight</th>
                                        <th>Total CGST</th>
                                        <th>Total SGST</th>
                                        <th>Total IGST</th>
                                        <th>Grand Total</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->
                            <div class="row" style="justify-content: end;">
                                <div class="col-md-2 col-sm-12 mt-4 " style="margin-right: 0px;">
                                    <label for="executionRate"><strong>Execution Rate</strong></label>
                                    <input class="form-control" type="number" id="executionRate" readonly>
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
                        title: 'Saraswati Globals (Quotation Report)',

                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14],
                        }
                    },
                    {
                        extend: 'print',
                        text: 'PRINT',
                        title: 'Saraswati Globals (Quotation Report)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, ],
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
            const filterTodate = $('#filterTodate').val();
            const filterFromdate = $('#filterFromdate').val();
            // const filterCompany = $('#filterCompany').val();


            $.ajax({
                type: 'POST',
                url: 'report-quotation',
                data: {
                    filterTodate: filterTodate,
                    filterFromdate: filterFromdate,
                    // filterCompany: filterCompany,


                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response && typeof response === 'object' && Array.isArray(response.data)) {
                        document.getElementById('executionRate').value = response.execution_rate.toFixed(1);
                        var table = $('#Category_table').DataTable();
                        table.clear().draw();
                        response.data.forEach(function(data, index) {
                            table.row.add([
                                index + 1,
                                data.date,
                                data.qt_number,
                                data.company_name,
                                data.total_pcs,
                                data.total_weight,
                                data.gst_type,
                                data.loading_charges,
                                data.additional_charges,
                                data.freight_charges,
                                data.total_sgst ?? 'N/A',
                                data.total_cgst ?? 'N/A',
                                data.total_igst ?? 'N/A',
                                data.grand_total,
                                data.status
                            ]).draw(false);
                        });
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
@endsection
