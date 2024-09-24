@extends('layouts.main')
@section('title', 'Outward - Saraswati Globals')
@section('content')
    <style>
        .Hover_tooltip {
            position: relative;
            display: inline-block;
            padding-right: 11px;
            color: red !important;
            font-size: 20px;
            font-weight: bolder;
            top: 0px;
        }

        .Hover_tooltiptext {
            visibility: hidden;
            font-weight: 100;
            font-size: 15px;
            width: 175px;
            background-color: #7b7b7b;
            ;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px 0;
            /* Position the tooltip */
            position: absolute;
            z-index: 1;
            bottom: 100%;
            left: 50%;
            margin-left: -60px;
        }

        .Hover_tooltip:hover .Hover_tooltiptext {
            visibility: visible;
        }
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

        @if (session('msg'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        title: "Oops!",
                        text: "{{ session('msg') }}",
                        icon: "error"
                    });
                });
            </script>
        @endif
        <div class="dashboard-header pagetitle">
            <h1>Outward</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Outward</li>
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
                                        <h4 class="text-blue h4">Outward</h4>

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 d-flex justify-content-end ">
                                    <div class="btn-group">
                                        @can('Outward-create')
                                            <a class="btn btn-primary mb-4 mr-3" data-bs-toggle="modal"
                                                data-bs-target="#select_company_modal_for_outward">
                                                New Outward</a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            <!-- Table with stripped rows -->
                            <table class="table " id="Category_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Outward No.</th>
                                        <th>SO No.</th>
                                        <th>Date(MM/DD/YY)​</th>
                                        <th>Company Name</th>
                                        <th>Total Quantity(Q)</th>
                                        {{-- <th>Status</th> --}}
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($outward_data as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            @php
                                                $exceed = DB::table('outward_items')
                                                    ->where('outward_id', $data->id)
                                                    ->where('outward_items.exceed_pcs', '!=', 0)
                                                    ->get();
                                            @endphp
                                            <td> {{ $data->outward_number }} </td>
                                            <td>{{ $data->so_number ?? 'N/A' }}</td>
                                            <td>{{ date('m-d-Y', strtotime($data->date)) }}</td>
                                            <td>{{ $data->company_name }}</td>
                                            <td>{{ $data->total_weight }}</td>
                                            {{-- <td>{{ $data->status }}</td> --}}
                                            <td onclick="get_data_in_same_page($loop->iteration)">

                                                <div class="filter">
                                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                            class="bi bi-three-dots"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">

                                                        {{-- <li>
                                                            @can('Outward-view')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('outward.edit', $data->ot_id) }}"><i
                                                                        class="fa-regular fa-eye"></i>View/Edit</a>
                                                            @endcan
                                                        </li> --}}

                                                        @if ($data->status !== 'Approved')
                                                            <li>

                                                                @can('Outward-approve')
                                                                    <form method="GET"
                                                                        action="{{ route('outward.approve', $data->ot_id) }}">
                                                                        <button type="button"
                                                                            class="dropdown-item approve-button">
                                                                            <i class="fa-solid fa-check"></i> Approve
                                                                        </button>
                                                                    </form>
                                                                @endcan
                                                            </li>
                                                        @endif

                                                        {{-- @if ($data->status !== 'Approved')
                                                            <li>
                                                                @can('Outward-delete')
                                                                    <form method="GET"
                                                                        action="{{ route('outward.delete', $data->ot_id) }}">
                                                                        <button type="button"
                                                                            class="dropdown-item delete-button">
                                                                            <i class="fa-solid fa-trash"></i> Delete
                                                                        </button>
                                                                    </form>
                                                                @endcan
                                                            </li>
                                                        @endif --}}


                                                    </ul>
                                                </div>
                                            </td>
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


    <!-- Type Modal -->
    <div class="modal fade" id="select_type" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Select Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select class="form-select" name="so_type" id="type" required>
                        <option value="">Select Type</option>
                        <option value="quotation">For Sales Order</option>
                        <option value="direct">Direct</option>
                    </select>
                </div>
            </div>
        </div>
    </div>


    <!-- Company Modal2 -->
    <div class="modal fade" id="select_company_modal_for_outward" tabindex="-1" aria-labelledby="companyModalLabel"
        aria-hidden="true">
        <form action="{{ route('outward.create') }}" method="post">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="companyModalLabel">Select Company</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <label for="" class="mb-2">Select Company <span
                                    class="required-classes">*</span></label>
                            <div class="col-lg-12">
                                <select class="form-select" id="selectcompany_id" name="company_id"
                                    onchange="get_so_number_from_sotable(this.value)">
                                    <option value="">Company Name</option>
                                    @foreach (\App\Models\company::where('type', 'buyer')->get() as $c_item)
                                        <option value="{{ $c_item->id }}">{{ $c_item->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="" class="mb-2">Select Sales Order<span
                                    class="required-classes">*</span></label>
                            <div class="col-lg-12">
                                <select class="form-select" id="selectso_id" name="so_id">
                                    <option value="" selected disabled>Sales Order</option>
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="" class="mb-2">Select Supplier<span
                                    class="required-classes">*</span></label>
                            <div class="col-lg-12">
                                <select class="form-select" id="selectsupplier_id" name="supplier_id">
                                    <option value="">Supplier Name</option>
                                    @foreach (\App\Models\Company::where('type', 'supplier')->get() as $supplier_item)
                                        <option value="{{ $supplier_item->id }}">{{ $supplier_item->company_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="selected_type" value="direct">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>


    <!-- Company Modal -->
    <div class="modal fade" id="company_modal" tabindex="-1" aria-labelledby="companyModalLabel" aria-hidden="true">
        <form action="{{ route('outward_sales.create') }}" method="post">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="companyModalLabel">Select Sales Order</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            onclick="refresh()"></button>
                    </div>
                    <div class="modal-body">

                        <div class="row">
                            <label for="" class="mb-2">Select Company <span
                                    class="required-classes">*</span></label>
                            <div class="col-lg-12">
                                <select class="form-select" name="company_id" id="company_id2"
                                    onchange="get_qt(this.value)" required>
                                    <option value="">Company Name</option>
                                    @foreach ($companies as $c_item)
                                        <option value="{{ $c_item->id }}">{{ $c_item->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div><br>

                        <div class="row">
                            <label for="" class="mb-2">Select Sales Order <span
                                    class="required-classes">*</span></label>
                            <div class="col-lg-12">
                                <select class="form-select" name="so_id" id="selectSalesorder" required>
                                    <option value="">Sales Order</option>
                                    {{-- @foreach ($sales_order as $so)
                                        <option value="{{ $so->id }}">{{ $so->so_number }}</option>
                                    @endforeach --}}
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="" class="mb-2">Select Warehouse <span
                                    class="required-classes">*</span></label>
                            <div class="col-lg-12">
                                @livewire('warehouse')
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- close Modal -->
    <div class="modal fade" id="select_closed" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{ route('outward.bill') }}" method="post">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Bill Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <label for="" class="mb-2">Select Bill <span
                                    class="required-classes">*</span></label>
                            <div class="col-lg-12">
                                <select class="form-select" name="bill_status" required>
                                    <option value="" disabled>Select Bill</option>
                                    <option value="bill pending">Bill Pending</option>
                                    <option value="bill generated">Bill Generated</option>
                                </select>
                                <input type="hidden" name="id" id="close_id">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        function get_so_number_from_sotable(value) {
            let buyer_id = value;
            $.ajax({
                url: "{{ url('get_so_number') }}",
                method: "post",
                data: {
                    buyer_id: buyer_id,
                    "_token": "{{ csrf_token() }}",

                },
                success: function(res) {
                    console.log(res);
                    
                    var salesOrderSelect = $("#selectso_id");
                    $.each(res.data, function(index, data) {
                        salesOrderSelect.append('<option value="' +
                            data.so_number + '">' + data.so_number +
                            '</option>');
                    });
                }
            });
        }
        document.addEventListener('DOMContentLoaded', (event) => {
            document.getElementById('type').addEventListener("change", function() {
                var type = this.value;

                if (type == 'direct') {
                    var companyModal = new bootstrap.Modal(document.getElementById(
                        'select_company_modal_for_outward'));
                    companyModal.show();
                } else {
                    console.log(1);
                    var companyModal = new bootstrap.Modal(document.getElementById('company_modal'));
                    companyModal.show();
                }

                document.getElementById('type').value = '';
            });

        });
    </script>

    <script>
        function sendId(Id) {
            document.getElementById('close_id').value = Id;
        }

        function get_qt(Id) {
            $("#selectSalesorder").find('option:not(:first)').remove();
            companyId = Id;
            var url = "/get_sales_order/" + companyId;

            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    var salesOrderSelect = $("#selectSalesorder");
                    $.each(response.data, function(index, data) {
                        salesOrderSelect.append('<option value="' +
                            data.so_id + '">' + data.documentNumber +
                            '</option>');
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
                        title: 'Saraswati Globals (Outward Details)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7,
                                8, 9,
                            ], // Include all columns except the last one with dropdown
                        }
                    },
                    {
                        extend: 'print',
                        text: 'PRINT',
                        title: 'Saraswati Globals (Outward Details)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7,
                                8, 9,
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

    <script>
        function refresh() {
            document.getElementById('type').value = '';
            document.getElementById('company_id').value = '';
        }
    </script>


@endsection
