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
                                        <th>Date(DD/MM/YY)​</th>
                                        <th>SO No.</th>
                                        <th>SO Item Number</th>
                                        <th>Buyer Name(Party Name)</th>
                                        <th>Item Category</th>
                                        <th>Quantity(Q)</th>                                        
                                        <th>SO Unit Price</th>
                                        <th>SO Price</th>
                                        <th>SO Match Position</th>
                                        <th>SO Item Position</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sales_order as $data)
                                    {{-- @dd($data); --}}
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ date('d-m-Y', strtotime($data->date)) }}</td>
                                            <td>{{ $data->so_number }}</td>
                                            <td>{{ $data->so_item_no }}</td>
                                            <td>{{ $data->company_name }}</td>
                                            <td>{{ $data->category_name }}</td>
                                            <td>{{ $data->qty }}</td>
                                            <td>{{ $data->unit_price }}</td>
                                            <td>{{ $data->price }}</td>
                                            <td>{{ $data->match_position }}</td>
                                            <td>{{ $data->so_item_status }}</td>
                                            <td>{{ $data->terms_condition ?? 'N/A' }}</td>
                                           
                                            <td onclick="get_so_id_for_remark({{ $data->id }})">
                                                <div class="filter">
                                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                            class="bi bi-three-dots"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                  
                                                        <li>
                                                            @can('Company-edit')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('sales.edit', $data->so_id) }}"><i
                                                                        class="fa-solid fa-pencil"></i>View/Edit</a>
                                                            @endcan
                                                        </li>

                                                        {{-- <li>
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
                                                        </li> --}}


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
                        <option value="quotation">Quotation</option>
                        <option value="direct">Direct</option>
                    </select>
                </div>
            </div>
        </div>
    </div>


    <!-- Company Modal2 -->
    {{-- <div class="modal fade" id="company_modal2"tabindex="-1" aria-labelledby="companyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="companyModalLabel">Select Company</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        onclick="refresh()"></button>
                </div>
                <div class="modal-body">
                    @livewire('sales')
                </div>
            </div>
        </div>
    </div> --}}
    <!-- Company Modal2 -->
    <div class="modal fade" id="company_modal2" tabindex="-1" aria-labelledby="companyModalLabel" aria-hidden="true">
        <form action="{{ route('sales.create') }}" method="post">
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
                                <select class="  form-select Buyer-Company-select" name="company_id"
                                    id=" " required>
                                    <option value="" selected disabled>Buyer Company</option>
                                    @foreach ($company as $c_item)
                                        <option value="{{ $c_item->id }}">{{ $c_item->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>




    <!-- Company Modal -->
    <div class="modal fade" id="company_modal" tabindex="-1" aria-labelledby="companyModalLabel" aria-hidden="true">
        <form action="{{ route('sales_quotation.create') }}" method="post">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="companyModalLabel">Select Company</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            onclick="refresh()"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <label for="" class="mb-2">Select Company <span
                                    class="required-classes">*</span></label>
                            <div class="col-lg-12">
                                <select class="js-example-basic-single form-select custom-select" name="company_id"
                                    id="company_id2" onchange="get_qt(this.value)" required>
                                    <option value="">Company Name</option>
                                    @foreach ($company as $c_item)
                                        <option value="{{ $c_item->id }}">{{ $c_item->company_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <label for="" class="mb-2">Select Quotation <span
                                    class="required-classes">*</span></label>
                            <div class="col-lg-12">
                                <select class="form-select" name="quotation_number" id="quotationSelect" required>
                                    <option value="">Select Quotation</option>
                                </select>
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



    <!-- Modal Structure -->
    <div class="modal fade" id="select_closed" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('sales.closed') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Select Closed Type</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <label for="close_type" class="mb-2">Select Type <span
                                    class="required-classes">*</span></label>
                            <div class="col-lg-12">
                                <select class="form-select" name="so_close_type" id="close_type" required>
                                    <option value="">Select Type</option>
                                    <option value="pending">Pending</option>
                                    <option value="partial pending">Partial Pending</option>
                                    <option value="closed with condition">Closed with Condition</option>
                                    <option value="closed">Closed</option>
                                </select>
                                <input type="hidden" name="id" id="close_id">
                            </div>
                        </div>
                        <!-- Remarks Section -->
                        <div class="row mt-3" id="remarks_row" style="display: none;">
                            <label for="remarks" class="mb-2">Remarks</label>
                            <div class="col-lg-12">
                                <textarea class="form-control" name="remarks" id="remarks_for_condition" rows="2"></textarea>
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
                    'pageLength', 'csv', 'print'
                ]
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('.table.dataTable').removeClass('no-footer');
        });
    </script>

@endsection
