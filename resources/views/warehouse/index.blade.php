@extends('layouts.main')
@section('title','Warehouse - Saraswati Globals')
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
            <h1>WareHouse Details Details</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">WareHouse Details Details</li>
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
                                        <h4 class="text-blue h4">WareHouse Master List</h4>

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 d-flex justify-content-end ">
                                    <div class="btn-group">
                                        @can('Warehouse-create')
                                            <a class="btn btn-primary mb-4 mr-3 "href="{{ route('warehouse.create') }}">Add New
                                                WareHouse </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>
                            <!-- Table with stripped rows -->
                            <div class="dataTables_wrapper">
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>WareHouse Prefix</th>
                                            <th>Mobile</th>
                                            <th>Address</th>
                                            <th>Pincode</th>
                                            <th>GSTN</th>
                                            <th>PAN</th>
                                            <th>TAN</th>
                                            <th>CIN No</th>
                                            <th>Registration No</th>
                                            <th>Store Manager</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            @foreach ($data as $warehouse)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $warehouse->warehouse_title }}</td>
                                                    <td>{{ $warehouse->mobile }}</td>
                                                    <td>{{ $warehouse->address }}</td>
                                                    <td>{{ $warehouse->pincode }}</td>
                                                    <td>{{ $warehouse->gstn }}</td>
                                                    <td>{{ $warehouse->pan }}</td>
                                                    <td>{{ $warehouse->tan }}</td>
                                                    <td>{{ $warehouse->cin_no }}</td>
                                                    <td>{{ $warehouse->registration_no }}</td>
                                                    <td>{{ $warehouse->name }}</td>
                                                    <td>
                                                        <div class="filter">
                                                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                                    class="bi bi-three-dots"></i></a>
                                                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                                                <li> <a class="dropdown-item"
                                                                        href="{{ route('warehouse.show', $warehouse->id) }}"><i
                                                                            class="fa-regular fa-eye"></i> View</a></li>
                                                                <li>
                                                                    @can('Warehouse-edit')
                                                                        <a class="dropdown-item"
                                                                            href="{{ route('warehouse.edit', $warehouse->id) }}"><i
                                                                                class="fa-solid fa-pencil"></i>Edit</a>
                                                                    @endcan
                                                                </li>
                                                                <li>
                                                                    @can('Warehouse-delete')
                                                                        <form method="POST"
                                                                            action="{{ route('warehouse.destroy', $warehouse->id) }}">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="button"
                                                                                class="dropdown-item delete-button">
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
                            </div>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->


    
@endsection
