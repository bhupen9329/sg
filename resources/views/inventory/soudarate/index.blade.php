@extends('layouts.main')
@section('title', 'Souda Rate - Saraswati Globals')
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
            <h1>Conv Rate</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Conv Rate</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            {{-- <div class="card">
                <div class="card-body">

                    <form action="{{ route('subcategory_import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="form-control" required>
                        @if ($errors->has('file'))
                            <div style="color: red;">
                                {{ $errors->first('file') }}
                            </div>
                        @endif
                        <p class="" style="color: red">Note : Import data here to modify the 'difference' column.</p>
                        <br>
                        <button class="btn btn-primary" type="submit">Import</button>
                        <button style="margin-left:10px;" class="btn btn-primary" type="button"
                            onclick="window.location.href='subcategory-export'">Export</button>
                    </form><br>
                </div>
            </div> --}}



            <div class="row">

                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="row ">
                                <div class="col-md-6 col-sm-12">
                                    <div class="pd-20">
                                        <h4 class="text-blue h4">Conv Rate</h4>

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 d-flex justify-content-end ">
                                    <div class="btn-group">
                                        {{-- @can('Sub-Category-create') --}}
                                            @can('Conversion Rate-create')
                                                <a class="btn btn-primary mb-4 mr-3 "href="{{ route('rate.create') }}">Add
                                                    New
                                                    Conv Rate</a>
                                            @endcan
                                        {{-- @endcan --}}

                                    </div>
                                </div>
                            </div>
                            <!-- Table with stripped rows -->
                            <table class="table" id="Category_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Date(DD/MM/YY)</th>
                                        <th>Base Item</th>
                                        <th>Conv Item</th>
                                        <th>Conv Rate</th>
                                        <th>Remarks</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($convItems as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ date('d-M-Y', strtotime($data->selected_date)) }}</td>
                                            <td>{{ $data->name }}</td>                                           
                                            <td>{{ $data->sub_category }}</td>
                                            <td>{{ $data->item_price }}</td>
                                            <td>{{ $data->remarks ?? 'N/A' }}</td>
                                        
                                            <td>
                                                <div class="filter">
                                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                            class="bi bi-three-dots"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                        <li>
                                                            @can('Conversion Rate-edit')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('rate.edit', $data->conv_id) }}"><i
                                                                        class="fa-solid fa-pencil"></i>Edit</a>
                                                            @endcan
                                                        </li>

                                                        <li>
                                                            @can('Conversion Rate-delete')
                                                                <form method="POST"
                                                                    action="{{ route('rate.delete', $data->conv_id) }}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="button" class="dropdown-item delete-button">
                                                                        <i class="fa-solid fa-trash"></i> Delete
                                                                    </button>
                                                                </form>
                                                            @endcan
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



        {{-- ---------------------------- Modalfor providers details ------------------------------------ --}}
        <div class="modal fade" id="Modalfor_providers_details" tabindex="-1" aria-labelledby="modal3Label"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal3Label">Provider List​</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"style="width:50px"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Supplier Name</th>
                                    <th scope="col">Contact</th>
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
            function get_providers_details(id) {
                id = id;
                // console.log(po_id);
                $.ajax({
                    url: "{{ url('get_providers_details') }}",
                    method: "POST",
                    data: {
                        id: id,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        // console.log(res); // Log the response to the console
                        let tableBody = document.querySelector('.modal-body table tbody');
                        tableBody.innerHTML = ''; // Clear existing table rows

                        res.forEach((res, index) => {
                            let row = `<tr>
                                        <th scope="row">${index + 1}</th>
                                        <td>${res.company_name}</td>
                                        <td>${res.mobile}</td>
                                    </tr>`;
                            tableBody.insertAdjacentHTML('beforeend', row);
                        });
                    }


                });
            }
        </script>
    </main><!-- End #main -->


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
                        extend: 'print',
                        text: 'PRINT',
                        title: 'Saraswati Globals (Sub Category Details)',
                        exportOptions: {
                            columns: [0, 1,
                                2, 3, 4, 5 ] // Include all columns except the last one with dropdown
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
    </script>
@endsection
