@extends('layouts.main')
@section('title', 'Index - Dispatch')
@section('content')
    <style>

    </style>
    <main id="main" class="main">
        @if ($message = Session::get('Credit_note_status'))
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
        @if ($message = Session::get('error'))
            <div class="tt active">
                <div class="tt-content">
                    <i class="fas fa-solid fa-times-circle error-icon"></i>
                    <div class="message">
                        <span class="text text-1">Error</span>
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
            <h1>Dispatch Summary</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Open Dispatch Positions</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="pd-20">
                                        <h4 class="text-blue h4">Dispatch</h4>
                                    </div>
                                </div>


                                <div class="col-md-6 col-sm-12 d-flex justify-content-end">
                                    <div class="btn-group">
                                        @can('Inward-create')
                                            <a href="{{ route('dispatch.create') }}" class="btn btn-primary mb-4 mr-3">Add
                                                Dispatch</a>
                                        @endcan
                                    </div>


                                </div>
                            </div>
                            <div style="overflow-x: auto">
                                <table class="table " id="Category_table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>#</th>
                                            <th style="width: 72.8125px;">Date</th>
                                            <th style="width: 72.8125px;">Vehicle Number</th>
                                            <th style="width: 84.8125px;">From (Party Name)</th>
                                            <th style="width: 84.8125px;">PO Item No</th>
                                            <th style="width: 84.8125px;">PO Item Unit Price</th>
                                            <th>Payable Total</th>
                                            <th>Category</th>
                                            <th>Conv Item Name</th>
                                            <th>Dispatch Qty</th>
                                            <th style="width: 84.8125px;">To (Party Name)</th>
                                            <th>SO Item No</th>
                                            <th style="width: 84.8125px;">SO Item Unit Price</th>
                                            <th>Receivable Total</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($disaptch_data as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ date('d-M-Y', strtotime($data->date)) }}</td>
                                                <td>{{ $data->vehicle_number ?? 'N/A' }}</td>
                                                <td>{{ $data->po_company }}</td>
                                                <td><a
                                                        href="{{ route('purchase.edit', ['id' => $data->po_id]) }}">{{ $data->po_item_no }}</a>
                                                </td>
                                                <td>{{ $data->dispatch_unit_price }}</td>
                                                <td>
                                                    <a href="javascript:void(0);" data-bs-toggle="modal"
                                                        data-bs-target="#Modalfor_quantity_details_so"
                                                        onclick="get_received_so_qty_for_report('{{ $data->dispatch_id }}')">
                                                        {{ $data->dispatch_total }}
                                                    </a>
                                                </td>

                                                <td>{{ $data->category_name }}</td>
                                                <td>{{ $data->sub_category_name }}</td>

                                                <td>{{ $data->dispatched_quantity }}</td>

                                                <td>{{ $data->so_company }}</td>

                                                <td><a
                                                        href="{{ route('sales.edit', ['id' => $data->so_id]) }}">{{ $data->so_item_no }}</a>
                                                </td>
                                                <td>{{   $data->dispatch_so_unit_price}}</td>
                                                <td>
                                                    <a href="javascript:void(0);" data-bs-toggle="modal"
                                                        data-bs-target="#Modalfor_quantity_details_po"
                                                        onclick="get_received_po_qty_for_report('{{ $data->dispatch_id }}')">
                                                        {{ $data->dispatch_so_total }}
                                                    </a>
                                                </td>
                                                {{-- <td>{{ $data->dispatch_so_unit_price }}</td> --}}
                                                {{-- <td>{{ $data->dispatch_so_total }}</td> --}}


                                                <td>
                                                    <div class="filter">
                                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                class="bi bi-three-dots"></i></a>
                                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">

                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('dispatch.edit', $data->dispatch_id) }}"><i
                                                                        class="fa-solid fa-pencil"></i>Edit</a>

                                                            </li>

                                                            <li>
                                                                <form method="GET"
                                                                    action="{{ route('dispatch.destroy', $data->dispatch_id) }}">
                                                                    @method('DELETE')
                                                                    <button type="button"
                                                                        class="dropdown-item delete-button">
                                                                        <i class="fa-solid fa-trash"></i> Delete
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>




                        </div>
                    </div>
                </div>
            </div>
        </section>
        <br><br><br>

        <div class="modal fade" id="Modalfor_quantity_details_so" tabindex="-1" aria-labelledby="modal3Label"
            aria-hidden="true">
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
        </div>


        <div class="modal fade" id="Modalfor_quantity_details_po" tabindex="-1" aria-labelledby="modal3Label"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        
                        <h5 class="modal-title" id="modal3Label">Payable Total Details</h5>
                        
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body-po">
                        <h6 class="text-end py-3"><strong>PO Quantity</strong> : <span id="add_total_qty"></span></h6>
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



    </main><!-- End #main -->





    <script>
        $(document).ready(function() {
            $('#Category_table').DataTable({
                dom: 'Bfrtip',
                // Set the default page length
                pageLength: 50,
                // Configure the drop down options
                lengthMenu: [
                    [10, 20, 50, 100, 150, -1],
                    ['10 rows', '20 rows', '50 rows', '100 rows', '150 rows', 'Show all']
                ],
                // Add to buttons the pageLength option
                buttons: [
                    'pageLength', 'csv', 'print'
                ]
            });
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
                    </tr>`
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

            // Check if the required properties exist in the response
            if (res) {
                // Create rows according to the table structure
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
                    </tr>`
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
        $(document).ready(function() {
            $('.table.dataTable').removeClass('no-footer');
        });
    </script>


@endsection
