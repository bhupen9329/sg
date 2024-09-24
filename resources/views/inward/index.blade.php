@extends('layouts.main')
@section('title', 'Inward - Saraswati Globals')
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
            <h1>Inward Details</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Inward</li>
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
                                        <h4 class="text-blue h4">Inward</h4>

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 d-flex justify-content-end ">
                                    <div class="btn-group">
                                        @can('Inward-create')
                                            <a class="btn btn-primary mb-4 mr-3" data-bs-toggle="modal"
                                                data-bs-target="#PurchaseinwardModal">
                                                New Inward</a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            <!-- Table with stripped rows -->
                            <table class="table" id="Category_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Inward No.​</th>
                                        <th>Date(DD/MM/YY)​</th>
                                        <th>Company </th>
                                        <th>Quantity (Q)</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inward_data as $data)
                                        <tr>
                                            @php
                                                $shortage = $data->godown_weight - $data->plant_weight;
                                                $Unloading_Charges =
                                                    $data->crane_charge + $data->labour_charge + $data->kanta_charge;
                                            @endphp
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
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->inward_number }}</td>
                                            <td>{{ date('d-m-Y', strtotime($data->date)) }}</td>
                                            <td>{{ $data->company_name }}</td>
                                            <td>{{ $data->total_weight }}</td>
                                            <td>{{ $data->status }}</td>
                                            <td>
                                                <div class="filter">
                                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                            class="bi bi-three-dots"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">

                                                        {{-- <li>
                                                            @can('Inward-view')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('inward.edit', $data->i_id) }}"><i
                                                                        class="fa-regular fa-eye"></i>View/Edit</a>
                                                            @endcan
                                                        </li> --}}
                                                        
                                                        @if ($data->status !== 'Approved')
                                                            <li>
                                                                @can('Inward-approve')
                                                                    <form method="POST"
                                                                        action="{{ route('inward.approve', $data->i_id) }}">
                                                                        @csrf
                                                                        @method('POST')
                                                                        <button type="button"
                                                                            class="dropdown-item approve-button">
                                                                            <i class="fa-solid fa-check"></i> Approve
                                                                        </button>
                                                                    </form>
                                                                @endcan
                                                            </li>
                                                            {{-- <li>
                                                                @can('Inward-delete')
                                                                    <form method="POST"
                                                                        action="{{ route('inward.destroy', $data->i_id) }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="button"
                                                                            class="dropdown-item delete-button">
                                                                            <i class="fa-solid fa-trash"></i> Delete
                                                                        </button>
                                                                    </form>
                                                                @endcan
                                                            </li> --}}
                                                        @endif
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


    {{-- .................................. Modal for Credit Note.............................  --}}
    <div class="modal fade" id="ModalforCredit_Note" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal3Label">Update Credit Note Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"style="width:50px"></button>
                </div>
                <form action="{{ route('change_credit_note.status') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Content goes here -->
                        <div class="col-lg-12">
                            <div class="form-group">
                                <select name="credit_note_status" id="item_id${lastItemId}" style="height: 34px; "
                                    class="form-control item-select-${lastItemId}" required>
                                    <option value="" disabled selected>Select Status</option>
                                    <option value="Credit Note Generated">Credit Note Generated</option>
                                    <option value="Credit Note Pending">Credit Note Pending</option>
                                </select>

                                <input type="hidden" name="inward_id" id="set_po_id">
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


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{ route('inward.create') }}" method="post">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Select Company</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <label for="" class="mb-2">Select Company <span
                                    class="required-classes">*</span></label>
                            <div class="col-lg-12">
                                @livewire('purchase')
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

    {{-- ..................................CompanyModal ...........................  --}}
    <div class="modal fade" id="SelectTypeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal3Label">Inward type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"style="width:50px"></button>
                </div>
                <div class="modal-body">
                    <!-- Content goes here -->
                    <div class="col">
                        <div class="form-group">
                            <h5 class="modal-title" id="exampleModalLabel">Select type</h5>
                            <select class="custom-select form-control" name="company" id="typeSelect">
                                <option>Select type</option>
                                {{-- <option value="Sales Return">Sales Return</option> --}}
                                <option value="Purchase">Purchase</option>
                                <!-- Assuming $companies is passed from server-side -->
                                {{-- @foreach ($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                                @endforeach --}}
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>



    <!-- Purchase inward modal  -->
    <div class="modal fade" id="PurchaseinwardModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal3Label">Supplier & PO Number</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"style="width:50px"></button>
                </div>
                <form action="{{ route('Purchase-inward.create') }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <!-- Content goes here -->
                        <div class="col">
                            <div class="form-group">

                                <h5 class="modal-title mt-3" id="exampleModalLabel">Supplier<span
                                        class="required-classes">*</span></h5>
                                <select class="custom-select form-control select_seller_name" name="seller_id"
                                    onchange='get_supplier_id(this.value)' required>
                                    <option value="">Select Seller Company</option>
                                    <!-- Assuming $companies is passed from server-side -->
                                    @foreach ($seller_companies as $seller_data)
                                        <option value="{{ $seller_data->id }}">{{ $seller_data->company_name }}</option>
                                    @endforeach
                                </select>
                                <h5 class="modal-title mt-2" id="exampleModalLabel">PO Number<span
                                        class="required-classes">*</span></h5>
                                <select class="custom-select form-control select_po_number" name="po_number"
                                    id="companySelect" required>
                                    <option value="">Select PO Number</option>
                                    <!-- Assuming $companies is passed from server-side -->
                                </select>
                                <input type="hidden" name="selected_type" id="selected_type_purchase">
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


    <script>
        function get_po_id(po_id) {
            po_id = po_id;
            // console.log(po_id);
            $('#set_po_id').val(po_id);

        }
        document.getElementById("typeSelect").addEventListener("change", function() {
            var type = this.value;

            // $('#get_selected_type').val(type);
            // $('#selected_type_purchase').val(type);

            // // console.log(type);
            // if (type == 'Sales Return') {
            //     $('#warehouseModal').modal('show');
            // } else {
            //     $('#PurchaseinwardModal').modal('show');
            // }
        });
    </script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#Category_table').DataTable({
                dom: 'Bfrtip',
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
                        title: 'Saraswati Globals (Inward  Details)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7,
                                8, 9, 10, 11, 12,
                            ], // Include all columns except the last one with dropdown
                        }
                    },
                    {
                        extend: 'print',
                        text: 'PRINT',
                        title: 'Saraswati Globals (Inward  Details)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7,
                                8, 9, 10, 11, 12,
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
        function get_supplier_id(value) {
            let id = value;
            let SelectOPOption = document.querySelector('.select_po_number');
            // console.log(SelectOPOption);
            $.ajax({
                url: "{{ url('get_po_number_for_supplier') }}",
                method: "POST",
                data: {
                    supplier_id: id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    // console.log(res);

                    let data = res;
                    if (data) {
                        let htmldata = '<option value="">Select PO Number</option>';
                        for (let item of data) {
                            htmldata += `
                    <option value="${item.document_number}">${item.document_number}</option>
                `;
                        }
                        SelectOPOption.innerHTML =
                            htmldata; // Populate the subcategory select element in the same row with dynamic options
                    }
                }
            })
        }
        $(document).ready(function() {
            $('.table.dataTable').removeClass('no-footer');
        });
    </script>
@endsection
