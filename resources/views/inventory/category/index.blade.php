@extends('layouts.main')
@section('title', 'Category - Saraswati Globals')
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
            <h1>Base Item</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Base Item</li>
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
                                        <h4 class="text-blue h4">Base Item</h4>

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 d-flex justify-content-end ">
                                    <div class="btn-group">
                                        @can('Base Item-create')
                                            <a class="btn btn-primary mb-4 mr-3 "href="{{ route('category.create') }}">Add New
                                                Base Item</a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            <!-- Table with stripped rows -->

                            <table class="table" id="Category_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Base Item Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($category as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->name }}</td>
                                            <td>
                                                <div class="filter">
                                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                            class="bi bi-three-dots"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                        <li>
                                                            @can('Base Item-edit')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('category.edit', $data->id) }}"><i
                                                                        class="fa-solid fa-pencil"></i>Edit</a>
                                                            @endcan
                                                        </li>

                                                        <li>
                                                            @can('Base Item-delete')
                                                                <form method="POST"
                                                                    action="{{ route('category.delete', $data->id) }}">
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
                        extend: 'csv',
                        text: 'CSV',
                        title: 'Saraswati Globals (Category Details)',

                        exportOptions: {
                            columns: [0, 1] // Include all columns except the last one with dropdown
                        }
                    },
                    {
                        extend: 'print',
                        text: 'PRINT',
                        title: 'Saraswati Globals (Category Details)',
                        exportOptions: {
                            columns: [0, 1] // Include all columns except the last one with dropdown
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
