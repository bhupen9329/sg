@extends('layouts.main')
@section('title', 'Purchase order - Saraswati Globals')
@section('content')
    <main id="main" class="main">

        <div>
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
            @if ($message = Session::get('Total_closed'))
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
            @if ($message = Session::get('Partial_closed'))
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
            @if ($message = Session::get('Partial_created'))
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
        </div>
        <div class="dashboard-header pagetitle">
            <h1>Purchase Orders Details</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Purchase Orders</li>
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
                                        <h4 class="text-blue h4">Purchase Orders</h4>

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 d-flex justify-content-end ">
                                    <div class="btn-group">
                                        @can('Purchase-create')
                                            <a class="btn btn-primary mb-4 mr-3" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal">
                                                New Purchase Order​</a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            <!-- Table with stripped rows -->
                            <table class="table " id="Category_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date(DD/MM/YY)​</th>
                                        <th>PO No.</th>
                                        <th>PO Item Number</th>
                                        <th>Seller Name(Party Name)</th>
                                        <th>Item Category</th>
                                        <th>Item Sub-Category</th>
                                        <th>Quantity(Q)</th>                                        
                                        <th>PO Unit Price</th>
                                        <th>PO Price</th>
                                        <th>PO Match Position</th>
                                        <th>Remarks</th>                                      
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($po_data as $data)
                                    {{-- @dd($data); --}}
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ date('d-m-Y', strtotime($data->date)) }}</td>
                                            <td>{{ $data->document_number }}</td>
                                            <td>{{ $data->po_item_no }}</td>
                                            <td>{{ $data->company_name }}</td>
                                            <td>{{ $data->category_name }}</td>
                                            <td>{{ $data->sub_category_name }}</td>
                                            <td>{{ $data->qty }}</td>
                                            <td>{{ $data->unit_price }}</td>
                                            <td>{{ $data->price }}</td>
                                            <td>{{ $data->match_position }}</td>

                                            <td>{{ $data->terms_condition ?? 'N/A' }}</td>
                                           
                                            <td onclick="get_so_id_for_remark({{ $data->id }})">
                                                <div class="filter">
                                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                            class="bi bi-three-dots"></i></a>
                                                    {{-- <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                        <li> <a class="dropdown-item"
                                                                href="{{ route('buyers.show', $data->id) }}"><i
                                                                    class="fa-regular fa-eye"></i> View</a></li>
                                                        <li>
                                                            @can('Company-edit')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('buyers.edit', $data->id) }}"><i
                                                                        class="fa-solid fa-pencil"></i>Edit</a>
                                                            @endcan
                                                        </li>

                                                        <li>
                                                            @can('Sales-view')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('sales.show', $data->id) }}"><i
                                                                        class="fa-solid fa-eye"></i>View</a>
                                                            @endcan
                                                        </li>
                                                        <li>
                                                            @can('Sales-view')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('sales.edit', $data->id) }}"><i
                                                                        class="fa-solid fa-pencil"></i>Edit</a>
                                                            @endcan
                                                        </li>
                                                        @if ($data->status == 'pending')
                                                            <li>
                                                                @can('Sales-delete')
                                                                    <form method="POST"
                                                                        action="{{ route('sales.destroy', $data->id) }}">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="button"
                                                                            class="dropdown-item delete-button">
                                                                            <i class="fa-solid fa-trash"></i> Delete
                                                                        </button>
                                                                    </form>
                                                                @endcan
                                                            </li>
                                                        @endif
                                                        <li>
                                                            @can('Sales-close')
                                                                <a class="dropdown-item" data-bs-toggle="modal"
                                                                    data-bs-target="#select_closed"
                                                                    onclick="sendId('{{ $data->id }}')"><i
                                                                        class="fa-regular fa-close"></i> Closed</a>
                                                            @endcan
                                                        <li>
                                                        <li>
                                                            @can('Sales-download')
                                                            <a class="dropdown-item" href="{{ $data->document_file }}"
                                                                target="_blank">
                                                                <i class="fa-solid fa-download"></i> Download
                                                            </a>
                                                            @endcan
                                                        </li>


                                                    </ul> --}}
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


    {{-- .................................. modal.............................  --}}
    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{ route('purchase.create') }}" method="post">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Select Company</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"style="width:50px"></button>
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


    <!-- Modals -->
    <!-- Modal 1 -->
    <div class="modal fade" id="modal1" tabindex="-1" aria-labelledby="modal1Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal1Label">Partial Receive​</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="width:50px"></button>
                </div>
                <form action="{{ route('po-partial-receive.save') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row  ">
                            <label for="inputPassword3" class="col-sm-12 col-form-label"><strong>Enter Received
                                    Quantity<span class="required-classes">*</span>​</strong> </label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" name="received_quantity"
                                    id="received_total_qty_quantity" required>
                                <input type="hidden" class="form-control" name="po_id" id="set_po_id">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal 2 -->
    <div class="modal fade" id="modal2" tabindex="-1" aria-labelledby="modal2Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal2Label">Partial Closed​</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"style="width:50px"></button>
                </div>
                <form action="{{ route('po-partial-closed.save') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row  ">
                            <label for="inputPassword3" class="col-sm-12 col-form-label"><strong>Enter Received
                                    Quantity​ <span class="required-classes">*</span></strong> </label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" name="received_quantity"
                                    id="for_partisaly_closed" required>
                                <input type="hidden" class="form-control" name="po_id" id="set_po_id2">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal 3 -->
    <div class="modal fade" id="modal3" tabindex="-1" aria-labelledby="modal3Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal3Label">Total Closed</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"style="width:50px"></button>
                </div>
                <form action="{{ route('total.closed') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row  ">
                            <label for="inputPassword3" class="col-sm-12 col-form-label text-center"><strong>Are You
                                    Sure?​ <br>
                                    You want to Permanently close the Purchase Order.​</strong> </label>
                            <input type="date" class="form-control" name="closed_date" id="closed_date" required>

                            <div class="col-sm-12">
                                <input type="hidden" class="form-control" name="po_id" id="set_po_id3">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-evenly">
                        <button type="submit" class="btn btn-primary">Closed</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal 4 -->
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
                                <th scope="col" style="width: 20px">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal 5 -->
    <div class="modal fade" id="Modalfor_update_received_qty" tabindex="-1" aria-labelledby="modal3Label"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal3Label">Update Received Quantity </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"style="width:50px"></button>
                </div>
                <form action="{{ url('update-partial-received-quantity') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="modal-body">
                            <div class="col-md-12" id="remove_Update_Quantity">
                                <label for="Price" class="form-label">Update Quantity</label>
                                <input type="number" name="received_quantity" id="update_received_qty"
                                    class="form-control" required>
                            </div>
                            <div class="col-12 mt-3">
                                <label for="Margin" id="set_Received_Quantity_Completed" class="form-label"
                                    style="color: red">Received Quantity Is Completed</label>
                            </div>
                            <div class="col-12 mt-3">
                                <label for="Margin" id="set_max_qty" class="form-label">Max Value: </label>
                            </div>


                            <input type="hidden" class="form-control" name="po_received_id" id="received_id">
                            <input type="hidden" class="form-control" name="po_id" id="set_po_id_for_update">
                            {{-- <div class="d-flex justify-content-end pt-3">
                                <button type="button" class="btn btn-secondary m-1"
                                    data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary m-1">Submit</button>
                            </div> --}}
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="update_button">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            // Initialize modal instances
            const main_modal = new bootstrap.Modal(document.getElementById('Modalforselect_type'));
            const modal1 = new bootstrap.Modal(document.getElementById('modal1'));
            const modal2 = new bootstrap.Modal(document.getElementById('modal2'));
            const modal3 = new bootstrap.Modal(document.getElementById('modal3'));

            // Event listeners to hide modal1 when modal2 or modal3 is shown
            document.getElementById('modal2').addEventListener('show.bs.modal', function() {
                modal1.hide();
            });
            document.getElementById('modal3').addEventListener('show.bs.modal', function() {
                modal1.hide();
            });

            // Event listeners to show modal1 when modal2 or modal3 is hidden
            document.getElementById('modal1').addEventListener('hidden.bs.modal', function() {
                main_modal.show();
            });
            document.getElementById('modal2').addEventListener('hidden.bs.modal', function() {
                main_modal.show();
            });
            document.getElementById('modal3').addEventListener('hidden.bs.modal', function() {
                main_modal.show();
            });

            // Ensure modal2 and modal3 are hidden when modal1 is shown
            document.getElementById('modal1').addEventListener('show.bs.modal', function() {
                let modal2Instance = bootstrap.Modal.getInstance(document.getElementById('modal2'));
                let modal3Instance = bootstrap.Modal.getInstance(document.getElementById('modal3'));
                if (modal2Instance) modal2Instance.hide();
                if (modal3Instance) modal3Instance.hide();
            });
        });

        function get_po_id(po_id) {
            po_id = po_id;
            $('#set_po_id').val(po_id);
            $('#set_po_id2').val(po_id);
            $('#set_po_id3').val(po_id);
            $.ajax({
                url: "{{ url('get_received_quantity') }}",
                method: "POST",
                data: {
                    po_id: po_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    // console.log(res);
                    let total_qty = res.total_qty.rest_quantity;
                    $('#received_total_qty_quantity').attr('max', total_qty);
                    $('#for_partisaly_closed').attr('max', total_qty);
                    //  $("#remaining_max").attr("max", difference);
                }
            });

        }

        function get_received_qty(po_id, rest_qty) {
            let get_po_id = po_id;
            let total_balance_qty = rest_qty;

            // console.log(po_id);
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
                                        <td>
                                             <a data-bs-toggle="modal" href="#" class="dropdown-item"
                                                    data-bs-target="#Modalfor_update_received_qty"
                                                    style="text-decoration: underline; color: blue;text-align: center;"
                                                    onclick="update_received_qty(${rowData.id},'${get_po_id}', '${total_balance_qty}', '${totalQty}' , ${rowData.received_qty}, ${rowData.balance_qty})">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </a>
                                            </td>
                                    </tr>`;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });
                }


            });
        }


        function update_received_qty(id, get_po_id, total_balance_qty, totalQty, received_qty, balance_qty) {
            let po_received_id = id;
            let po_id = get_po_id;
            let po_received_total_balance_qty = parseFloat(total_balance_qty);
            // console.log(po_received_total_balance_qty);
            let po_received_received_qty = parseFloat(received_qty);
            let max_qty = po_received_total_balance_qty + po_received_received_qty;
            // console.log(max_qty);

            if (po_received_total_balance_qty === 0) {
                $('#update_received_qty').attr('max', 0);
                $('#set_max_qty').html('Max Value : ' + 0);
                $('#update_button').hide();
                $('#remove_Update_Quantity').hide();
                $('#set_Received_Quantity_Completed').show();
            } else {
                // $('#update_button').hide();
                $('#update_received_qty').attr('max', max_qty);
                $('#set_max_qty').html('Max Value : ' + max_qty);
                $('#remove_Update_Quantity').show();
                $('#set_Received_Quantity_Completed').hide();
            }
            // console.log(max_qty);

            $('#received_id').val(po_received_id);
            $('#set_po_id_for_update').val(po_id);
        }
    </script>





    <script>
        $(document).ready(function() {
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
                        title: 'Saraswati Globals (Purchase Orders Details)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8,],
                        }
                    },
                    {
                        extend: 'print',
                        text: 'PRINT',
                        title: 'Saraswati Globals (Purchase Orders Details)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, ],
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
                'font-size': '10px',
                'margin': '10px'
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('.table.dataTable').removeClass('no-footer');
        });
    </script>
@endsection
