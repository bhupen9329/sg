@extends('layouts.main')
@section('title', 'Sales Order - Saraswati Globals')
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
            <h1>Sales Orders Details</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Sales Orders</li>
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
                                        <h4 class="text-blue h4">Sales Orders</h4>

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 d-flex justify-content-end ">
                                    <div class="btn-group">
                                        @can('Sales-create')
                                            <a class="btn btn-primary mb-4 mr-3" data-bs-toggle="modal"
                                                data-bs-target="#company_modal2">
                                                New Sales Order</a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            <!-- Table with stripped rows -->

                            <table class="table " id="Category_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>SO Date​</th>
                                        <th>SO No.</th>
                                        <th>SO Item Number</th>
                                        <th>Buyer Name(Party Name)</th>
                                        <th>Item Category</th>
                                        <th>Quantity(Q)</th>
                                        <th>Dispatch Rest Quantity(Q)</th>
                                        <th>SO Unit Price</th>
                                        <th>SO Price</th>
                                        {{-- <th>SO Match Position</th>
                                        <th>SO Item Position</th> --}}
                                        <th>SO Item Dispatch Status</th>
                                        <th>Remarks</th>
                                        <th>Sales Person</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sales_order as $data)
                                        {{-- @dd($data); --}}
                                        <tr>

                                            @if ($data->so_dispatch_rest_qty == $data->qty)
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ date('d-M-Y', strtotime($data->date)) }}</td>
                                                {{-- <td>{{ date('d-m-Y', strtotime($data->date))  }}</td> --}}
                                                <td>{{ $data->so_number }}</td>
                                                <td>{{ $data->so_item_no }}</td>
                                                <td>{{ $data->company_name }}</td>
                                                <td>{{ $data->category_name }}</td>
                                                <td>{{ $data->qty }}</td>
                                                <td style="background-color: #ff3300">{{ $data->so_dispatch_rest_qty }}
                                                </td>
                                                <td>{{ $data->unit_price }}</td>
                                                <td>{{ $data->price }}</td>

                                                <td>{{ $data->so_dispatch_item_status }}</td>
                                                <td>{{ $data->terms_condition ?? 'N/A' }}</td>
                                                <td>{{ $data->name ?? 'N/A' }}</td>
                                            @elseif($data->so_dispatch_rest_qty == 0)
                                                <td style="background-color: #15ff00">{{ $loop->iteration }}</td>
                                                <td style="background-color: #15ff00">
                                                    {{ date('d-M-Y', strtotime($data->date)) }}</td>
                                                {{-- <td>{{ date('d-m-Y', strtotime($data->date))  }}</td> --}}
                                                <td style="background-color: #15ff00">{{ $data->so_number }}</td>
                                                <td style="background-color: #15ff00">{{ $data->so_item_no }}</td>
                                                <td style="background-color: #15ff00">{{ $data->company_name }}</td>
                                                <td style="background-color: #15ff00">{{ $data->category_name }}</td>
                                                <td style="background-color: #15ff00">{{ $data->qty }}</td>
                                                <td style="background-color: #15ff00">{{ $data->so_dispatch_rest_qty }}
                                                </td>
                                                <td style="background-color: #15ff00">{{ $data->unit_price }}</td>
                                                <td style="background-color: #15ff00">{{ $data->price }}</td>

                                                <td style="background-color: #15ff00">{{ $data->so_dispatch_item_status }}
                                                </td>
                                                <td style="background-color: #15ff00">{{ $data->terms_condition ?? 'N/A' }}
                                                </td>
                                                <td style="background-color: #15ff00">{{ $data->name ?? 'N/A' }}</td>
                                            @else
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ date('d-M-Y', strtotime($data->date)) }}</td>
                                                {{-- <td>{{ date('d-m-Y', strtotime($data->date))  }}</td> --}}
                                                <td>{{ $data->so_number }}</td>
                                                <td>{{ $data->so_item_no }}</td>
                                                <td>{{ $data->company_name }}</td>
                                                <td>{{ $data->category_name }}</td>
                                                <td>{{ $data->qty }}</td>
                                                <td style="background-color: #ff3300">{{ $data->so_dispatch_rest_qty }}
                                                </td>
                                                <td>{{ $data->unit_price }}</td>
                                                <td>{{ $data->price }}</td>

                                                <td>{{ $data->so_dispatch_item_status }}</td>
                                                <td>{{ $data->terms_condition ?? 'N/A' }}</td>
                                                <td>{{ $data->name ?? 'N/A' }}</td>
                                            @endif

                                            <td onclick="get_so_id_for_remark({{ $data->id }})">
                                                <div class="filter">
                                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                            class="bi bi-three-dots"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">

                                                        <li>
                                                            @can('Sales-edit')
                                                                
                                                       
                                                            <a class="dropdown-item"
                                                                href="{{ route('sales.edit', $data->so_id) }}"><i
                                                                    class="fa-solid fa-pencil"></i>View/Edit</a>
                                                                    @endcan
                                                            @if ($data->so_dispatch_item_status == 'Open')
                                                            @can('Sales-delete')

                                                                <a class="dropdown-item"
                                                                    href="{{ route('sales.delete', $data->so_item_id) }}"><i
                                                                        class="fa-solid fa-trash"></i>Delete</a>
                                                                    @endcan

                                                            @endif

                                                            <a data-bs-toggle="modal" href="#"
                                                                onclick="get_po_id({{ $data->so_item_id }})"
                                                                class="dropdown-item" data-bs-target="#Modalforselect_type">
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
            </div>
        </section>

        <!-- Modal 1 -->
        <div class="modal fade" id="Modalforselect_type" tabindex="-1" aria-labelledby="modal1Label" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal1Label">Change Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            style="width:50px"></button>
                    </div>
                    <form action="{{ route('so_pre_closed.save') }}" method="POST">
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
                                    <input type="hidden" id="set_po_item_id" name="so_item_id">
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

    </main><!-- End #main -->

    <!-- Company Modal2 -->
    <div class="modal fade" id="company_modal2" tabindex="-1" aria-labelledby="companyModalLabel" aria-hidden="true">
        <form action="" method="post">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="companyModalLabel">Select Company</h5>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <label for="company_id11" class="mb-2">Select Buyer Company<span
                                    class="required-classes">*</span></label>
                            <div class="col-lg-12">
                                <select class="form-select Buyer-Company-select" name="company_id"
                                    id="buyerCompanySelect" required onchange="navigateToSalesCreate(this)">
                                    <option value="" selected disabled>Buyer Company</option>
                                    @foreach ($company as $c_item)
                                        <option value="{{ $c_item->id }}">{{ $c_item->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!-- JavaScript to Handle Modal Data and Toggle Remarks -->
    <script>
        function get_so_id_for_remark(id) {
            let so_id = id;
            // console.log(so_id);

            $.ajax({
                url: "{{ url('get-so-remark-for-modal') }}",
                type: 'post',
                data: {
                    so_id: so_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    // console.log(res);
                    let remark = res.remark;
                    $('#remarks_for_condition').val(remark || 'N/A');

                },
                error: function(xhr) {
                    // Handle errors here
                    console.log(xhr.responseText);
                }
            });
        }
        $(document).ready(function() {
            // Show modal and fetch data on show
            $('#select_closed').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal
                var id = button.data('id'); // Extract info from data-* attributes
                var modal = $(this);

                if (id) {
                    $.ajax({
                        url: '/get_sales_order/' + companyId, // Correct URL with ID
                        type: 'PUT',
                        success: function(data) {
                            // Populate the modal fields with the fetched data
                            modal.find('#close_id').val(data.id);
                            modal.find('#close_type').val(data.so_close_type);
                            modal.find('#remarks').val(data.remarks);

                            // Show/Hide remarks row based on fetched data
                            if (data.so_close_type === 'closed with condition') {
                                $('#remarks_row').show();
                                $('#remarks').prop('disabled', false);
                            } else {
                                $('#remarks_row').hide();
                                $('#remarks').prop('disabled', true);
                            }
                        },
                        error: function(xhr) {
                            // Handle errors here
                            console.log(xhr.responseText);
                        }
                    });
                } else {
                    console.error("No ID found in data-id attribute");
                }
            });

            // Toggle remarks visibility based on selected type
            $('#close_type').on('change', function() {
                var selectedType = $(this).val();
                if (selectedType === 'closed with condition') {
                    $('#remarks_row').show();
                    $('#remarks').prop('disabled', false);
                } else {
                    $('#remarks_row').hide();
                    $('#remarks').prop('disabled', true);
                }
            });
        });
        $(document).ready(function() {
            // Handle form submission via AJAX
            $('#closeTypeForm').on('submit', function(e) {
                e.preventDefault(); // Prevent the default form submission

                var form = $(this);
                var formData = form.serialize(); // Serialize form data

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // Handle the response (e.g., show a success message)
                        console.log('Form submitted successfully:', response);
                        $('#select_closed').modal('hide'); // Hide the modal after submission
                        // Optionally, you can also update parts of your page here
                    },
                    error: function(xhr) {
                        // Handle errors
                        console.error('Form submission failed:', xhr.responseText);
                    }
                });
            });

            // Refresh page if modal is closed without submission Remark
            $('#select_closed').on('hidden.bs.modal', function() {
                // Refresh the page Remark
                location.reload();
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 when the modal is shown


            // Event listener for when a company is selected
            $('#company_id11').on('change', function() {
                var companyId = this.value;
                var type = document.getElementById('type').value;

                if (type === 'direct') {
                    window.location.href = "/sales-create/" + type + "/" + companyId;
                }
            });
        });

        // Function to reset fields
    </script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 on page load for elements that are in the DOM
            $('.js-example-basic-single').select2();

            // Reinitialize Select2 inside the modal when it is shown
            $('#company_modal').on('shown.bs.modal', function() {
                $('#company_id2').select2({
                    dropdownParent: $('#company_modal')
                });
            });
            $('#company_modal_2').on('shown.bs.modal', function() {
                $('#company_id').select2({
                    dropdownParent: $('#company_modal_2')
                });
            });
        });
        document.addEventListener('DOMContentLoaded', (event) => {
            document.getElementById('type').addEventListener("change", function() {
                var type = this.value;
                if (type == 'direct') {
                    var companyModal = new bootstrap.Modal(document.getElementById('company_modal2'));
                    companyModal.show();

                } else {
                    var companyModal = new bootstrap.Modal(document.getElementById('company_modal'));
                    companyModal.show();
                    document.getElementById('type').value = '';
                    document.getElementById('company_id2').value = '';
                    document.getElementById('quotationSelect').value = '';
                }
            });
            document.getElementById('company_id').addEventListener("change", function() {
                var companyId = this.value;
                var type = document.getElementById('type').value;
                if (type == 'direct') {
                    window.location.href = "/sales-create/" + type + "/" + companyId;
                }
                document.getElementById('type').value = '';
                document.getElementById('company_id').value = '';
            });
        });
    </script>

    <script>
        function sendId(Id) {
            document.getElementById('close_id').value = Id;
        }

        function get_qt(Id) {
            $("#quotationSelect").find('option:not(:first)').remove();
            companyId = Id;
            var url = "/get_quotation/" + companyId;

            $.ajax({
                url: url,
                type: "GET",
                dataType: "json",
                success: function(response) {
                    var salesOrderSelect = $("#quotationSelect");
                    $.each(response.data, function(index, documentNumber) {
                        salesOrderSelect.append('<option value="' +
                            documentNumber + '">' + documentNumber +
                            '</option>');
                    });
                }
            });

        }

        function getSalesOrder(Id) {
            $("#selectSalesorder").find('option:not(:first)').remove();
            var companyId = Id;
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
        document.addEventListener('DOMContentLoaded', function() {
            var salesOrderSelect = document.getElementById('selectSalesorder');
            var remarksTextarea = document.getElementById('remarks');

            salesOrderSelect.addEventListener('change', function() {
                var selectedOption = salesOrderSelect.options[salesOrderSelect.selectedIndex];
                var remarks = selectedOption.getAttribute('data-remarks');
                remarksTextarea.value = remarks || '';
            });
        });
    </script>

    <script>
        function refresh() {
            document.getElementById('type').value = '';
            document.getElementById('company_id').value = '';
        }
    </script>

    <script>
        // $(document).ready(function() {
        //     var table = $('#Category_table').DataTable({

        //         lengthMenu: [[5, 10, 15, 50, 100, -1], [5, 10, 15, 50, 100, "All"]]
        //     });

        //     $('.dt-buttons button').addClass('custom-button');

        //     $('.custom-button, .paginate_button').css({
        //         'padding': '5px 10px',
        //         'font-size': '10px',
        //     });
        // });

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
                    'pageLength',
                    {
                        extend: 'csv',
                        text: 'CSV',
                        title: 'Saraswati Globals (Sales Orders Details)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12],
                        }
                    },
                    {
                        extend: 'print',
                        text: 'PRINT',
                        title: 'Saraswati Globals (Sales Orders Details)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12 ],
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
        });
    </script>


    <script>
        $(document).ready(function() {
            $('.table.dataTable').removeClass('no-footer');
        });
    </script>

    <script>
        function navigateToSalesCreate(selectElement) {
            const companyId = selectElement.value; // Get the selected company ID
            if (companyId) {
                const url = `/sales-create/${companyId}`; // Build the URL
                window.location.href = url; // Redirect to the route
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#buyerCompanySelect').select2();
            // Focus the search box when the subcategory dropdown is opened
            $('#buyerCompanySelect').on('select2:open', function() {
                document.querySelector('.select2-search__field').focus();
            });

        });

        function get_po_id(po_item_id) {
            po_item_id = po_item_id;
            $('#set_po_item_id').val(po_item_id);
        }
    </script>

@endsection
