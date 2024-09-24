@extends('layouts.main')
@section('title','Stock Adjustment - Saraswati Globals')
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
        <h1>Stock Adjustment Details</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                <li class="breadcrumb-item">Stock Adjustment</li>
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
                                    <h4 class="text-blue h4">Stock Adjustment</h4>

                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12 d-flex justify-content-end ">
                                <div class="btn-group">
                                    @can('Stock-Adjustment-create')
                                    <a class="btn btn-primary mb-4 mr-3" data-bs-toggle="modal" data-bs-target="#warehouseModal">
                                        Add</a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                        <!-- Table with stripped rows -->
                        <table class="table" id="Category_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date​(mm/dd/yy)</th>
                                    <th>Adjustment No.​</th>
                                    <th>Warehouse </th>
                                    <th>No. Of Items Adjusted (PCs)</th>
                                    <th>Adjusted By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($adjustment_data as $data)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    {{-- <td>{{ $data->date }}</td> --}}
                                    <td>{{ date('m-d-Y', strtotime($data->date))}}</td>

                                    <td>{{ $data->adjustment_number }}</td>
                                    <td>{{ $data->warehouse_title }}</td>
                                    <td>
                                        <a data-bs-toggle="modal" href="#" class="dropdown-item" data-bs-target="#Modalfor_Category_details" style="text-decoration: underline; color: blue;" onclick="get_Categorydetails({{ $data->sadj_id }})">
                                            <strong>{{ $data->piece }}</strong>
                                        </a>
                                    </td>
                                    <td>{{ $data->name }}</td>
                                    <td>
                                        <div class="filter">
                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">

                                                <li>
                                                    @can('Stock-Adjustment-delete')
                                                    <form method="POST" action="{{ route('stock-adjustment​.destroy', $data->sadj_id) }}">
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


{{-- .................................. modal.............................  --}}
<!-- Button trigger modal -->


<!-- Ware house modal  -->
<div class="modal fade" id="warehouseModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal3Label">Company & WareHouse</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="width:50px"></button>
            </div>
            <form action="{{ route('stock-adjustment​.create') }}" method="post">
                @csrf
                <div class="modal-body">
                    <!-- Content goes here -->
                    <div class="col">
                        <div class="form-group">
                            <h5 class="modal-title mt-3" id="exampleModalLabel">Warehouse<span class="required-classes">*</span></h5>
                            <select class="custom-select form-control" name="warehouse_id" id="wareHouseSelect" required>
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


{{-- ---------------------------- Category details ------------------------------------ --}}
<div class="modal fade" id="Modalfor_Category_details" tabindex="-1" aria-labelledby="modal3Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal3Label">Items List​</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="width:50px"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Category</th>
                            <th scope="col" style="text-wrap: nowrap;">Sub Category</th>
                            <th scope="col">Length</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Type</th>
                            <th scope="col">Remark</th>
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
    function get_Categorydetails(sadj_id) {
        id = sadj_id;
        // console.log(id);
        $.ajax({
            url: "{{ url('get_Category_details') }}",
            method: "POST",
            data: {
                id: id,
                "_token": "{{ csrf_token() }}",
            },
            success: function(res) {
                console.log(res); // Log the response to the console
                let tableBody = document.querySelector('.modal-body table tbody');
                tableBody.innerHTML = ''; // Clear existing table rows

                res.forEach((res, index) => {
                    let row = `<tr>
                                        <th scope="row">${index + 1}</th>
                                        <td>${res.name}</td>
                                        <td>${res.sub_category}</td>
                                        <td>${res.length}</td>
                                        <td>${res.piece}</td>
                                        <td>${res.type}</td>
                                         <td style=width:50px;>${res.remark ?? 'N/A'} </td>
                                    </tr>`;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            }


        });
    }



    $(document).ready(function() {
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
                    title: 'Saraswati Globals (Stock Adjustment Details)',

                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5,6,],
                    }
                },
                {
                    extend: 'print',
                    text: 'PRINT',
                    title: 'Saraswati Globals (Stock Adjustment Details)',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5,6,],
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
            'font-size': '10px'
        });
    });
</script>


<script>
    $(document).ready(function() {
        $('.table.dataTable').removeClass('no-footer');
    });
</script>
@endsection
