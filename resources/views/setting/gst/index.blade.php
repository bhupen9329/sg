@extends('layouts.main')
@section('title','GST - Saraswati Globals')
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
            <h1>Gst Settings</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Gst Settings</li>
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
                                        <h4 class="text-blue h4">Gst Settings</h4>

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 d-flex justify-content-end ">
                                    <div class="btn-group">
                                        @can('GST-create')
                                            <a class="btn btn-primary mb-4 mr-3" data-bs-toggle="modal"
                                                data-bs-target="#modal1">
                                                Add</a>
                                        @endcan

                                    </div>
                                </div>
                            </div>
                            <!-- Table with stripped rows -->
                            <table class="table datatable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>GST Prefix</th>
                                        <th>%</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $gst)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $gst->gst_prefix }}</td>
                                            <td>{{ $gst->percent }}</td>
                                            <td>
                                                <div class="filter">
                                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                            class="bi bi-three-dots"></i></a>
                                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                        {{-- <li> <a class="dropdown-item"
                                                            href="{{ route('buyers.show', $data->id) }}"><i
                                                                class="fa-regular fa-eye"></i> View</a></li>
                                                    <li>
                                                        @can('Company-edit')
                                                            <a class="dropdown-item"
                                                                href="{{ route('buyers.edit', $data->id) }}"><i
                                                                    class="fa-solid fa-pencil"></i>Edit</a>
                                                        @endcan
                                                    </li> --}}

                                                        <li>
                                                            @can('GST-edit')
                                                                <a class="dropdown-item" href="" data-bs-toggle="modal"
                                                                    onclick="get_data({{ $gst->id }})"
                                                                    data-bs-target="#modal2">
                                                                    <i class="fa-solid fa-pencil"></i>Edit</a>
                                                            @endcan
                                                        </li>
                                                        <li>
                                                            @can('GST-delete')
                                                                <a class="dropdown-item"
                                                                    href="{{ route('gst_setting.destroy', $gst->id) }}"
                                                                    class="dropdown-item delete-button"><i
                                                                        class="fa-solid fa-trash"></i>Delete</a>
                                                            @endcan


                                                        </li>





                                                    </ul>
                                                </div>
                                            </td>
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
    <div class="modal fade" id="modal1" tabindex="-1" aria-labelledby="modal1Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal1Label">ADD GSt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="width:50px"></button>
                </div>
                <form action="{{ route('setting_gst.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row  ">
                            <label for="inputPassword3" class="col-sm-12 col-form-label"><strong>GST Prefix</strong> <span
                                    class="required-classes">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="gst_prefix" id="set_po_id" required>
                            </div>

                            <label for="inputPassword3" class="col-sm-12 col-form-label"><strong>GST %</strong><span
                                    class="required-classes">*</span> </label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" name="gst_percent" id="set_po_id" required
                                    max="100">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal 1 -->
    <div class="modal fade" id="modal2" tabindex="-1" aria-labelledby="modal1Label" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal1Label">Edit GST</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="width:50px"></button>
                </div>
                <form action="{{ route('setting_gst.update') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row  ">
                            <label for="inputPassword3" class="col-sm-12 col-form-label"><strong>GST Prefix</strong> <span
                                    class="required-classes">*</span></label>
                            <div class="col-sm-12">
                                <input type="text" class="form-control" name="gst_prefix" id="gst_prefix" required>
                                <input type="hidden" class="form-control" name="gst_id" id="gst_id" required>
                            </div>

                            <label for="inputPassword3" class="col-sm-12 col-form-label"><strong>GST %</strong><span
                                    class="required-classes">*</span> </label>
                            <div class="col-sm-12">
                                <input type="number" class="form-control" name="gst_percent" id="gst_percent"
                                    max="100" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">

                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>










    <script>
        function get_data(gst_id) {

            let item_id = gst_id;

            $.ajax({
                url: "{{ url('get_gst_details') }}",
                method: "POST",
                data: {
                    item_id: item_id,
                    "_token": "{{ csrf_token() }}",
                },

                success: function(res) {
                    if (res) {
                        let gst_prefix = $('#gst_prefix');
                        let gst_percent = $('#gst_percent');
                        let gst_id = $('#gst_id');


                        gst_prefix.val(res.data.gst_prefix);
                        gst_percent.val(res.data.percent);
                        gst_id.val(res.data.id);
                    }
                }


            });
        }
    </script>
