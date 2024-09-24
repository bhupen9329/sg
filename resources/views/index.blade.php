@extends('layouts.main')
@section('title','Dashboard- Saraswati Globals')
@section('content')
    <main id="main" class="main">

        <div class="dashboard-header pagetitle">
            <h1>Dashboard</h1>
        </div><!-- End Page Title -->

        <section class="section dashboard">
            <div class="row">
                <style>
                    .card {
                        padding-top: 0px !important;
                    }

                    .dashboard-container {
                        display: grid;
                        grid-template-areas:
                            'first first two two three three'
                            'four four four five five five';
                        gap: 10px;
                        padding: 10px;
                    }

                    .dashboard-card-1 {
                        grid-area: first;
                    }

                    .dashboard-card-2 {
                        grid-area: two;
                    }

                    .dashboard-card-3 {
                        grid-area: three;
                    }

                    .dashboard-card-4 {
                        grid-area: four;
                        width: 823px;
                    }

                    .dashboard-card-5 {
                        grid-area: five;
                        width: 678px;
                    }

                    .dashboard_dataTables_wrapper {
                        height: 213px;
                        overflow-y: scroll;
                    }

                    /* .dashboard_dataTables_wrapper_low {
                        height: 600px;
                        overflow-y: scroll;
                    } */

                    .note-toolbar .btn-primary:hover,
                    .note-toolbar .btn-primary:active,
                    .note-toolbar .btn-primary:focus {
                        background-color: #007bff;
                    }

                    .row{
                        --bs-gutter-x: -0.5rem !important;
                    } 
                </style>
                <!-- Left side columns -->
                <div class="col-lg-12">
                    <div class="row dashboard-container">

                        <!--User ​ Card -->
                        <div class="dashboard-card-1">
                            <div class="card info-card sales-card">
                                <div class="card-body">
                                    <h5 class="card-title">Open Sales Order</h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fas fa-chart-line"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $sales_order }}</h6>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End User Card -->

                        <!-- Revenue Card -->
                        <div class="dashboard-card-2">
                            <div class="card info-card revenue-card">

                                <div class="card-body">
                                    <h5 class="card-title">Open Purchase Order</h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="fas fa-shopping-cart"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $purchase_order }}</h6>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Revenue Card -->


                        <div class="dashboard-card-3 ">
                            <div class="card info-card customers-card">

                                <div class="card-body">
                                    <h5 class="card-title"> Virtual Store
                                        <span style="color: #fff">Pending</span>
                                    </h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bi bi-people"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $virtual_store }}</h6>
                                            {{-- <h6>0</h6> --}}
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                        <!-- Customers Card -->
                     
                        {{-- <div class="dashboard-card-4">
                            <div class="card info-card customers-card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-lg-6 ">
                                            <h5 class="card-title">Base Price </h5>
                                        </div>
                                    </div>
                                    <!-- Table with stripped rows -->
                                    <div class="dashboard_dataTables_wrapper">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Category</th>
                                                    @can('price')
                                                    <th>Price</th>
                                                    <th>Margin</th>
                                                    @can('Dashboard-set-base-pirce')
                                                        <th>Action</th>
                                                    @endcan
                                                    @endcan
                                                </tr>
                                                
                                            </thead>
                                            <tbody>
                                               
                                                @if ($base_price->isNotEmpty())
                                                    @foreach ($base_price as $base_price_item)
                                                        <tr>
                                                       
                                                            <td>{{ $base_price_item->name }}</td>
                                                            @can('price')
                                                            <td>{{ $base_price_item->price }}</td>
                                                            <td>{{ $base_price_item->margin }}</td>
                                                            @can('Dashboard-set-base-pirce')
                                                                <td>
                                                                    <button type="button"
                                                                        class="btn btn-outline-primary btn-sm"
                                                                        data-bs-toggle="modal" data-bs-target="#exampleModal"
                                                                        onclick="get_category_id({{ $base_price_item->id }})">
                                                                        <i class="fa-solid fa-pencil"></i>
                                                                    </button>
                                                                </td>
                                                            @endcan
                                                            @endcan

                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr>
                                                        <td colspan="4" class="text-center">No Entries Found</td>
                                                    </tr>
                                                @endif
                                               
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- End Table with stripped rows -->
                                </div>
                            </div>
                        </div> --}}
                        <!-- End Customers Card -->
                        <!-- Customers Card -->
                        {{-- <div class="dashboard-card-5">

                            <div class="card">

                                <div class="card-body">
                                    <h5 class="card-title">Notes</h5>
                                    <form method="post" action="{{ route('save_notes') }}" id="noteForm">
                                        @csrf
                                        <div>
                                            <div id="summernote">{!! $my_notes ? $my_notes->description : '' !!}</div>
                                            <input type="hidden" name="description" id="note_content">
                                        </div>
                                        <div class="text-end mt-3">
                                            <button type="submit" class="btn btn-primary" hidden>Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div> --}}
                    </div>

                </div><!-- End Customers Card -->


                {{-- <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Low Stock​ </h5>

                            <!-- Table with stripped rows -->
                            <div class="dashboard_dataTables_wrapper_low">
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>WareHouse</th>
                                            <th>Category</th>
                                            <th>Sub Category</th>
                                            <th>Weight(kg)</th>
                                            <th>Approx Weight(kg)</th>
                                            <th>PCs</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($low_stock_items as $stocks)
                                        <?php
                                           $actual_weight = ($stocks->weight*$stocks->piece*$stocks->length); 
                                           $formatted_weight = number_format($actual_weight, 3, '.', '');
                                           $formatted_weight_si = number_format($stocks->si_weight, 3, '.', '');

                                        ?>
                                            <tr>
                                                <td>{{ $stocks->warehouse_title }}</td>
                                                <td>{{ $stocks->name }}</td>
                                                <td>{{ $stocks->sub_category }}</td>
                                                <td>{{  $formatted_weight_si }}</td>
                                                <td>{{ $formatted_weight }}</td>
                                                <td>{{ $stocks->piece }}</td>
                                            </tr>
                                        @endforeach
                                       
                                    </tbody>
                                </table>
                            </div>
                            <!-- End Table with stripped rows -->

                        </div>

                    </div>

                </div> --}}

                {{-- <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Stocks to Order​ </h5>

                            <!-- Table with stripped rows -->
                            <div class="dashboard_dataTables_wrapper_low">
                               
                                <table class="table datatable">
                                    <thead>
                                        <tr>
                                            <th>Category</th>
                                            <th>Sub Category</th>
                                            <th>Length</th>
                                            <th>WareHouse</th>
                                            <th>Current Quantity</th>
                                            <th>Sales Order Quantity</th>
                                            <th>Required</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($stock_store as $stocks)
                                            <?php
                                            $stock_order = $stocks->stock_piece - $stocks->block_stock;
                                            ?>
                                            @if ($stock_order < 0)
                                                <tr>
                                                    <td>{{ $stocks->category_name }}</td>
                                                    <td>{{ $stocks->subcategory_name }}</td>
                                                    <td>{{ $stocks->so_length }}</td>
                                                    <td>{{ $stocks->warehouse_title }}</td>
                                                    <td>{{ $stocks->stock_piece ?? 0 }}</td>
                                                    <td>{{ $stocks->block_stock }}</td>
                                                    <td>{{ $stock_order }}</td>
                                                </tr>
                                            @else
                                                @continue
                                            @endif
                                        @endforeach
                                         
                                    </tbody>
                                </table>
                            </div>
                           
                            <!-- End Table with stripped rows -->
                        </div>
                    </div>
                </div> --}}
            </div>
            </div><!-- End Left side columns -->
            </div>

            <!-- Base Price Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h1 class="modal-title fs-5" id="exampleModalLabel">Set Base Price</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form class="row g-3" method="post" action="{{ route('base-price.store') }}">
                            @csrf
                            <div class="modal-body" style="padding: 30px 43px;">
                                <div class="col-md-12">
                                    <label for="Category" class="form-label">Category<span
                                            class="required-classes">*</span></label>
                                    <input type="text" name="name" id="Category" val=""
                                        class="form-control" required readonly>
                                </div>
                                <div class="col-md-12">
                                    <label for="Price" class="form-label">Price<span
                                            class="required-classes">*</span></label>
                                    <input type="number" name="price" id="Price" min="1" val=""
                                        class="form-control" required>
                                </div>
                                <div class="col-12">
                                    <label for="Margin" class="form-label">Margin<span
                                            class="required-classes">*</span></label>
                                    <input type="number" name="margin" id="Margin" val=""
                                        class="form-control" min="1" required>
                                    <input type="hidden" name="id" id="Id" class="form-control">
                                </div>

                                <div class="d-flex justify-content-end pt-3">
                                    <button type="button" class="btn btn-secondary m-1"
                                        data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary m-1">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </section>

        <script>
            function get_category_id(id) {
                var category_id = id;

                $.ajax({
                    url: "{{ route('get_category_data') }}",
                    method: "POST",
                    data: {
                        category_id: category_id,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(res) {
                        var data = res.data;
                        $('#Category').val(data.name);
                        $('#Price').val(data.price);
                        $('#Margin').val(data.margin);
                        $('#Id').val(data.id);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        </script>
    </main><!-- End #main -->
@endsection
