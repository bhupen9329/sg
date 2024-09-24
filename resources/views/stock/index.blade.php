@extends('layouts.main')
@section('title', 'Stock - Saraswati Globals')
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
            <h1>Virtual Store Details</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Virtual Store</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row ">
                                <div class="col-md-6 col-sm-12">
                                    <div class="pd-20">
                                        <h4 class="text-blue h4">Virtual Store</h4>

                                    </div>
                                </div>
                                {{-- <div class="col-md-6 col-sm-12 d-flex justify-content-end ">
                                    <div class="btn-group">
                                        @can('Stock-create')
                                            <a class="btn btn-primary mb-4 mr-3" data-bs-toggle="modal"
                                                data-bs-target="#warehouseModal">
                                                New Stock</a>
                                        @endcan
                                    </div>
                                </div> --}}
                            </div>
                            <!-- Table with stripped rows -->
                            <table class="table " id="Category_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item Category​</th>
                                        <th>Item Sub Category</th>
                                        <th>Quantity(Q)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stock_item_data as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>{{ $data->sub_category }}</td>
                                            <td>{{ $data->w_weight }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->

    <!-- Ware house modal  -->
    <div class="modal fade" id="warehouseModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal3Label">Company & WareHouse</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"style="width:50px"></button>
                </div>
                <form action="{{ route('stock.create') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <!-- Content goes here -->
                        <div class="col">
                            <div class="form-group">
                                <h5 class="modal-title mt-3" id="exampleModalLabel">Warehouse<span
                                        class="required-classes">*</span></h5>
                                <select class="custom-select form-control" name="warehouse_id" id="wareHouseSelect"
                                    required>
                                    <option value="">Select Warehouse</option>
                                    <!-- Assuming $companies is passed from server-side -->
                                    @foreach ($warehouse as $ware_house)
                                        <option value="{{ $ware_house->id }}">{{ $ware_house->warehouse_title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ---------------------------- Modal for reserved details ------------------------------------ --}}
    <div class="modal fade" id="Modalfor_reserved_details" tabindex="-1" aria-labelledby="modal3Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal3Label">Sales Order List​</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"style="width:50px"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Sales Order ID</th>
                                <th scope="col">Company ID</th>
                                <th scope="col">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        function get_reserved_details(w_id, s_id) {
            let warehouse_id = w_id;
            let stock_items_id = s_id;
            // console.log(warehouse_id);
            $.ajax({
                url: "{{ url('get_reserved_details') }}",
                method: "POST",
                data: {
                    warehouse_id: warehouse_id,
                    stock_items_id: stock_items_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    // console.log(res);  
                    let tableBody = document.querySelector('.modal-body table tbody');
                    tableBody.innerHTML = ''; // Clear existing table rows

                    res.forEach((res, index) => {
                        let row = `<tr>
                                        <th scope="row">${index + 1}</th>
                                        <td>${res.so_number}</td>
                                        <td>${res.company_name}</td>
                                        <td>${res.rest_pcs}</td>
                                    </tr>`;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });
                }


            });
        }
    </script>

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
                        title: 'Saraswati Globals (Stock  Details)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 
                            ], // Include all columns except the last one with dropdown
                        }
                    },
                    {
                        extend: 'print',
                        text: 'PRINT',
                        title: 'Saraswati Globals (Stock  Details)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 
                            ], // Include all columns except the last one with dropdown
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
            $('.custom-button, .paginate_button ').css({
                'padding': '5px 10px', // Adjust padding as needed
                'font-size': '10px' // Adjust font size as needed
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.table.dataTable').removeClass('no-footer');
        });
    </script>
@endsection
