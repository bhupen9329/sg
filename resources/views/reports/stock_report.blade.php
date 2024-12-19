@extends('layouts.main')
@section('title','Stock Reports - Saraswati Globals')
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
        @if ($message = Session::get('approve'))
            <div class="tt active">
                <div class="tt-content">
                    <i class="fas fa-solid fa-check check"></i>
                    <div class="message">
                        <span class="text text-1">Approve</span>
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
            <h1>Virtual Store  Report</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Virtual Store Report</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <div class="dashboard-header pagetitle">
            <h1>Virtual Store Report </h1>
            <div class="row" style="align-items: flex-end;">
                <div class="col-md-12 col-sm-12 d-flex justify-content-end">


                    <button class=" m-1 btn btn-primary" type="button"
                        onclick="filterButton(
                $('#filterCategory').val(),
                $('#filtersubcategory').val(),
                $('#filterVirtualStore').val()
                
            )">
                        Apply
                    </button>
                    <button class=" m-1 btn btn-primary" type="button" id="resetButton">Reset</button>
                </div>
            </div>

            <div class="page-header">
                <div class="row">

                    <div class="col-md-2 col-sm-12">
                        <label for="filterCategory" class="mb-2"><strong>Category</strong></label>
                        <select class="custom-select form-control item-select-" onchange="get_sub_category(this) "
                            name="category" id="filterCategory" required>
                            {{-- <option value="">Select Category</option> --}}
                            <option value="all" selected>All</option>
                            @foreach ($Categorys as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>


                        {{-- <select name="item_category[]" id="item_id${lastItemId}" onchange="get_sub_category(this) "
                            style="height: 34px; width: 220px;" class="form-control item-select-${lastItemId}" required>
                            <option value="" disabled selected>Select Item</option>
                            @foreach ($category as $data)
                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                            @endforeach --}}
                        </select>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="filtersubcategory" class="mb-2"><strong>Sub Category</strong></label>
                        {{-- <select class="custom-select form-control" name="sub_category" id="filtersubcategory" required>
                            <option value="">Select Sub Category</option>
                            @foreach ($SubCategorys as $SubCategory)
                                <option value="{{ $SubCategory->id }}">{{ $SubCategory->sub_category }}</option>
                            @endforeach
                        </select> --}}

                        <select name="sub_category" class="custom-select form-control subcategory-select item-sub-select"
                            id="filtersubcategory" required>
                            {{-- <option value="" disabled >Select Sub Category</option> --}}
                            <option value="all" selected>All</option>
                        </select>
                    </div>

                   
                    <div class="col-md-2 col-sm-12" style="margin-top: 7px">
                        <label for="filterVirtualStore"><strong>Virtual Store</strong></label>
                        <select class="custom-select form-control select-warehouse" name="warehouse" id="filterVirtualStore"
                            required>
                            {{-- <option value="">Select Warehouse</option> --}}
                            <option value="all" selected>All</option>
                            @foreach ($virtual_store as $store)
                                <option value="{{ $store->id }}">{{ $store->virtual_store }}</option>
                            @endforeach
                        </select>
                    </div>
     

                </div>
            </div>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row ">
                                <div class="col-md-6 col-sm-12">
                                    <div class="pd-20">
                                        <h4 class="text-blue h4">Stock Report</h4>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-12 d-flex justify-content-end ">
                                </div>
                            </div>
                            <!-- Table with stripped rows -->
                            <table class="table " id="Category_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item Category</th>
                                        <th>Item Sub Category</th>
                                        <th>Total Quantity​ </th>
                                        <th>Virtual Store </th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main><!-- End #main -->



    {{-- csv  print   --}}
    <script>
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
                        title: 'Saraswati Globals (Stock  Report)',

                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5],
                        }
                    },
                    {
                        extend: 'print',
                        text: 'PRINT',
                        title: 'Saraswati Globals (Stock  Report)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5],
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
        function filterButton(filterCategory, filtersubcategory, filterVirtualStore) {
            $.ajax({
                type: 'POST',
                url: 'report-stock',
                data: {
                    filterCategory: filterCategory,
                    filtersubcategory: filtersubcategory,
                    filterVirtualStore: filterVirtualStore,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    console.log(response);
                    if (response && Array.isArray(response)) {
                        var table = $('#Category_table').DataTable();
                        table.clear().draw();
                        response.forEach(function(data, index) {
                            var quantity = parseFloat(data.total_quantity).toFixed(3);
                            table.row.add([
                                index + 1,
                                data.name,
                                data.sub_category,
                                quantity,
                                data.virtual_store,
                            ]).draw(false);
                        });
                    } else {
                        console.error("Invalid or empty response received.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX request failed:", status, error);
                }
            });
        }

        $('#resetButton').click(function() {
            // Reload the page to reset filters
            location.reload();
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





        function get_sub_category(selectElement) {
            let item_id = selectElement.value;
            let subcategorySelect = $('.subcategory-select');
            // console.log(subcategorySelect);

            $.ajax({
                url: "{{ url('get_subcategory_list') }}",
                method: "POST",
                data: {
                    item_id: item_id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function(res) {
                    let data = JSON.parse(res);
                    // console.log(data);
                    if (data) {
                        let htmldata = '<option value="" disabled selected>Select Subcategory</option>';
                        htmldata += '<option value="all">All</option>';
                        for (let item of data) {
                            htmldata += `<option value="${item.id}">${item.sub_category}</option>`;
                        }
                        subcategorySelect.html(htmldata).trigger('change');
                    }
                }
            });
        }
    </script>
@endsection
