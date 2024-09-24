@extends('layouts.main')
@section('title','Quotation - Saraswati Globals')
@section('content')
    <style>
        .note-editor.note-frame {
            border: 1px solid #a9a9a975;

        }

        .note-editor {

            width: 464px !important;
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
            <h1>Quotation Details</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Quotation</li>
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
                                        <h4 class="text-blue h4">Quotation</h4>

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 d-flex justify-content-end ">
                                    <div class="btn-group">
                                        @can('Quotation-create')
                                            <a class="btn btn-primary mb-4 mr-3" data-bs-toggle="modal"
                                                data-bs-target="#exampleModal">
                                                Quotation</a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            <!-- Table with stripped rows -->
                            <table class="table " id="Category_table">
                                <thead>
                                    <tr>
                                        <th>S.No​</th>
                                        <th>Quotation No.​</th>
                                        <th>Date(MM/DD/YY)​</th>
                                        <th>Company </th>
                                        <th>Total Quantity(kg)</th>
                                        @can('price')
                                            <th>Total Amount</th>
                                        @endcan
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($quotation_data as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->document_number }}</td>
                                            {{-- <td>{{ $data->quotation_date }}</td> --}}
                                            <td>{{ date('m-d-Y', strtotime($data->quotation_date))}}</td>
                                            <td>{{ $data->company_name }}</td>

                                            <td>{{ $data->total_weight }}</td>
                                            @can('price')
                                                <td>{{ $data->grand_total }}</td>
                                            @endcan
                                            <td>{{ $data->status }}</td>
                                            <td>
                                                <div class="filter">
                                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                            class="bi bi-three-dots"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                        @can('Quotation-view')
                                                            <li> <a class="dropdown-item"
                                                                    href="{{ route('quotation.edit', $data->q_id) }}"><i
                                                                        class="fa-regular fa-eye"></i>View/Edit</a></li>
                                                            <li>
                                                            @endcan
                                                            @if ($data->status != 'sales generated')
                                                                @can('Quotation-delete')
                                                                    <form method="GET"
                                                                        action="{{ route('quotation.destroy', $data->q_id) }}">
                                                                        @method('DELETE')
                                                                        <button type="button"
                                                                            class="dropdown-item delete-button">
                                                                            <i class="fa-solid fa-trash"></i> Delete
                                                                        </button>
                                                                    </form>
                                                                @endcan
                                                            @endif
                                                        </li>

                                                        <li>
                                                            @can('Quotation-download')
                                                                <a class="dropdown-item" href="{{ $data->document_file }}"
                                                                    target="_blank">
                                                                    <i class="fa-solid fa-download"></i> Download
                                                                </a>
                                                            @endcan
                                                        </li>


                                                        <li>
                                                            @can('Quotation-email')
                                                                <a class="dropdown-item" href="" data-bs-toggle="modal"
                                                                    onclick="get_data({{ $data->q_id }})"
                                                                    data-bs-target="#modal2">
                                                                    <i class="fa-solid fa-envelope"></i>Email</a>
                                                            @endcan

                                                        <li>
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


    {{-- .................................. modal.............................  --}}
    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{ route('quotation.create') }}" method="post">
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

    <!-- Email -->
    <div class="modal fade" id="modal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Send Via Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('quotation.send_email') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">To</label>
                            <input type="text" value="" class="form-control" name="to_email" id="to_emails">
                        </div><br>

                        <div class="form-group">
                            <label for="">CC</label>
                            <input type="text" value="{{ $company_email->cc }}" class="form-control" name="cc">
                        </div><br>
                        <div class="form-group">
                            <label for="">BCC</label>
                            <input type="text" value="{{ $company_email->bcc }}" class="form-control"
                                name="bcc">
                        </div><br>
                        <div class="form-group">

                            <label for="">From</label>
                            <input type="text" value="{{ $company_email->from_address }}" class="form-control"
                                name="from_email" readonly>
                        </div><br>
                        <div class="form-group">
                            <label for="">Subject</label>
                            <input type="text" value="Quotation" class="form-control" name="subject" readonly>
                        </div><br>
                        <div class="form-group">
                            <label for="">Body</label><br>
                            <div class="row">
                                <div class="col-lg-12">

                                    <textarea id="summernote" name="body" style="width: 464px;" class="textarea_editor form-control border-radius-0"
                                        placeholder="Enter text ..."></textarea>
                                </div>
                            </div>
                        </div><br>


                        <div class="form-group">
                            <label for="">Attachment</label><br>
                            <div>
                                <a href="" target="_blank" class="btn btn-info" id="attachmentLink">Open
                                    Attachment</a>
                                <input type="hidden" id="document_file" class="form-control" name="attachment">
                                <input type="hidden" name="qt_id" id="qt_id">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>

        </div>
    </div>


    <script>
        function get_data(id) {
            let item_id = id;
            $.ajax({
                url: "{{ url('get_email_details') }}",
                method: "POST",
                data: {
                    item_id: item_id,
                    "_token": "{{ csrf_token() }}",
                },

                success: function(res) {
                    if (res.quotation && res.company) {

                        let document_file = $('#document_file');
                        let qt_id = $('#qt_id');
                        let to_emails = $('#to_emails');

                        document_file.val(res.quotation.document_file);
                        qt_id.val(res.quotation.id);
                        to_emails.val(res.company.email);

                        $('#attachmentLink').attr('href', res.quotation.document_file);
                    }
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
                        title: 'Saraswati Globals (Quotation Details)',

                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5,
                                6,
                            ], // Include all columns except the last one with dropdown
                        }
                    },
                    {
                        extend: 'print',
                        text: 'PRINT',
                        title: 'Saraswati Globals (Quotation Details)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5,
                                6,
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

            $('.custom-button, .paginate_button').css({
                'padding': '5px 10px', // Adjust padding as needed
                'font-size': '10px' // Adjust font size as needed
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('.table.dataTable').removeClass('no-footer');
        });


        $(document).ready(function() {
            $('.custom-select').select2();
            // Focus the search box when the subcategory dropdown is opened
            $('.custom-select').on('select2:open', function() {
                document.querySelector('.select2-search__field').focus();
            });

        });
    </script>
@endsection
