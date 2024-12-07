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
                                        <th>PO Date​</th>
                                        <th>PO No.</th>
                                        <th>PO Item Number</th>
                                        <th>Seller Name(Party Name)</th>
                                        <th>Item Category</th>
                                        {{-- <th>Item Sub-Category</th> --}}
                                        <th>Quantity(Q)</th>
                                        <th>Dispatch Rest Quantity(Q)</th>
                                        <th>PO Unit Price</th>
                                        <th>PO Price</th>
                                        {{-- <th>PO Match Position</th>
                                        <th>PO Item Match Position</th> --}}
                                        <th>PO Item Dispatch Status</th>

                                        <th>Remarks</th>
                                        <th>Purchase Person</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($po_data as $data)
                                        {{-- @dd($data); --}}
                                        <tr>
                                            @if ($data->po_dispatch_rest_qty == $data->qty)
                                                <td style="background-color: transparent;">{{ $loop->iteration }}</td>
                                                <td style="background-color: transparent;">
                                                    {{ date('d-M-Y', strtotime($data->date)) }}</td>
                                                <td style="background-color: transparent;">{{ $data->document_number }}</td>
                                                <td style="background-color: transparent;">{{ $data->po_item_no }}</td>
                                                <td style="background-color: transparent;">{{ $data->company_name }}</td>
                                                <td style="background-color: transparent;">{{ $data->category_name }}</td>
                                                <td style="background-color: transparent;">{{ $data->qty }}</td>
                                                <td style="background-color: #ff3300;">{{ $data->po_dispatch_rest_qty }}
                                                </td>
                                                <td style="background-color: transparent;">{{ $data->unit_price }}</td>
                                                <td style="background-color: transparent;">{{ $data->price }}</td>
                                                <td style="background-color: transparent;">
                                                    {{ $data->po_dispatch_item_status }}</td>
                                                <td style="background-color: transparent;">{{ $data->remark ?? 'N/A' }}
                                                </td>
                                                <td style="background-color: transparent;">{{ $data->name ?? 'N/A' }}</td>
                                            @elseif ($data->po_dispatch_rest_qty == 0)
                                                <td style="background-color: #15ff00;">{{ $loop->iteration }}</td>
                                                <td style="background-color: #15ff00;">
                                                    {{ date('d-M-Y', strtotime($data->date)) }}</td>
                                                <td style="background-color: #15ff00;">{{ $data->document_number }}</td>
                                                <td style="background-color: #15ff00;">{{ $data->po_item_no }}</td>
                                                <td style="background-color: #15ff00;">{{ $data->company_name }}</td>
                                                <td style="background-color: #15ff00;">{{ $data->category_name }}</td>
                                                <td style="background-color: #15ff00;">{{ $data->qty }}</td>
                                                <td style="background-color: #15ff00;">{{ $data->po_dispatch_rest_qty }}
                                                </td>
                                                <td style="background-color: #15ff00;">{{ $data->unit_price }}</td>
                                                <td style="background-color: #15ff00;">{{ $data->price }}</td>
                                                <td style="background-color: #15ff00;">{{ $data->po_dispatch_item_status }}
                                                </td>
                                                <td style="background-color: #15ff00;">{{ $data->remark ?? 'N/A' }}</td>
                                                <td style="background-color: #15ff00;">{{ $data->name ?? 'N/A' }}</td>
                                            @else
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ date('d-M-Y', strtotime($data->date)) }}</td>
                                                <td>{{ $data->document_number }}</td>
                                                <td>{{ $data->po_item_no }}</td>
                                                <td>{{ $data->company_name }}</td>
                                                <td>{{ $data->category_name }}</td>
                                                <td>{{ $data->qty }}</td>
                                                <td style="background-color: #ff3300;">{{ $data->po_dispatch_rest_qty }}
                                                </td>
                                                <td>{{ $data->unit_price }}</td>
                                                <td>{{ $data->price }}</td>
                                                <td>{{ $data->po_dispatch_item_status }}</td>
                                                <td>{{ $data->remark ?? 'N/A' }}</td>
                                                <td>{{ $data->name ?? 'N/A' }}</td>
                                            @endif


                                            <td onclick="get_so_id_for_remark({{ $data->id }})">
                                                <div class="filter">
                                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                            class="bi bi-three-dots"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                        {{-- <li> <a class="dropdown-item"
                                                                href="{{ route('buyers.show', $data->id) }}"><i
                                                                    class="fa-regular fa-eye"></i> </a></li> --}}
                                                        <li>

                                                            <a class="dropdown-item"
                                                                href="{{ route('purchase.edit', $data->po_id) }}"><i
                                                                    class="fa-solid fa-pencil"></i>View/Edit</a>

                                                            @if ($data->po_dispatch_item_status == 'Open')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('purchase.delete', $data->po_item_id) }}"><i
                                                                        class="fa-solid fa-trash"></i>Delete</a>
                                                            @endif
                                                     
                                                                    <a data-bs-toggle="modal" href="#"
                                                                    onclick="get_po_id({{ $data->po_item_id }})"
                                                                    class="dropdown-item"
                                                                    data-bs-target="#Modalforselect_type">
                                                                    <i class="fa-solid fa-ban"></i>
                                                                    Change Status
                                                                </a>
                                                        </li>
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

        <!-- Modal 1 -->
        <div class="modal fade" id="Modalforselect_type" tabindex="-1" aria-labelledby="modal1Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal1Label">Change Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="width:50px"></button>
                    </div>
                    <form action="{{ route('po_pre_closed.save') }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="row  ">
                                <label for="inputPassword3" class="col-sm-12 col-form-label"><strong>
                                        Select Status<span class="required-classes">*</span>​</strong> </label>
                                <div class="col-sm-12">
                                    <select name="status" id="statusSelect" class="form-select" required>
                                        <option value="" selected disabled>Select Status</option>
                                        <option value="Pre Closed">Pre Close</option>
                                        <option value="Cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <label for="inputPassword3" class="col-sm-12 col-form-label"><strong> Date 
                                    <span class="required-classes">*</span>​</strong> </label>
                            <div class="col-sm-12">
                                <input type="date" class="form-control" name="date" id="dateInput" required 
                                value="<?php echo date('Y-m-d'); ?>">
                            </div>
                            <label for="inputPassword3" class="col-sm-12 col-form-label"><strong> Remarks
                               ​</strong> </label>
                        <div class="col-sm-12">
                            <textarea class="form-control" name="remarks" id="remarks_for_closure" rows="2"></textarea>
                            <input type="hidden" id="set_po_item_id" name="po_item_id">
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
    


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="" method="post">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Select Company</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close" style="width:50px"></button>
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
                </div>
            </div>
        </form>
    </div>


    <!-- Modals -->
    <!-- Modal 1 -->
  

  



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

        function get_po_id(po_item_id) {
            po_item_id = po_item_id;
            $('#set_po_item_id').val(po_item_id);
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
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, ],
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

<script>
    function navigateToPurchaseCreate(selectElement) {
        const companyId = selectElement.value; // Get the selected company ID
        if (companyId) {
            const url = `/purchase-create/${companyId}`; // Build the URL
            window.location.href = url; // Redirect to the route
        }
    }
</script>

<script>
    $(document).ready(function() {
        $('.custom-select').select2();
        // Focus the search box when the subcategory dropdown is opened
        $('.custom-select').on('select2:open', function() {
            document.querySelector('.select2-search__field').focus();
        });

    });
</script>
@endsection
