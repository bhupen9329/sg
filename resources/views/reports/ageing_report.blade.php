@extends('layouts.main')
@section('title','Ageing Reports - Saraswati Globals')
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
            <h1>Ageing Report</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Ageing Report</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <div class="dashboard-header pagetitle">
            <h1>Ageing Report </h1>
            <div class="row" style="align-items: flex-end;">
                <div class="col-md-12 col-sm-12 d-flex justify-content-end">


                    <button class=" m-1 btn btn-primary" type="button"
                        onclick="filterButton(
                $('#filterTodate').val(),
                $('#filterFromdate').val(),
                $('#filterCategory').val(),
                $('#filtersubCategory').val(),
                $('#filterWarehouse').val(),
                $('#filterage').val(),
                 $('#filterLength').val(),
            )">
                        Apply
                    </button>
                    <button class=" m-1 btn btn-primary" type="button" id="resetButton">Reset</button>
                </div>
            </div>

            <div class="page-header">
                <div class="row">
                    <div class="col-md-2 col-sm-12" style="margin-top: 7px">
                        <label for="filterTodate"><strong>From Date</strong></label>
                        <?php
                        $firstDayOfMonth = (new DateTime('first day of this month'))->format('Y-m-d');
                        ?>
                        <input type="date" class="form-control" name="to_date"  value="<?php echo $firstDayOfMonth; ?>"   id="filterTodate" required>
                    </div>
                    <div class="col-md-2 col-sm-12" style="margin-top: 7px">
                        <label for="filterFromdate"><strong>To Date</strong></label>
                        <?php
                        $lastDayOfMonth = (new DateTime('last day of this month'))->format('Y-m-d');
                        ?>
                        <input type="date" class="form-control"  value="<?php echo $lastDayOfMonth; ?>" name="from_date" id="filterFromdate" required>
                    </div>


                    <div class="col-md-2 col-sm-12">
                        <label for="" class="mb-2"><strong>Category</strong></label>
                        <select class="custom-select form-control" name="category" id="filterCategory"
                            onchange="get_sub_category(this) " required>
                            <option value="" disabled>Select Category</option>
                            <option value="all" selected>All</option>
                            @foreach ($category as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="" class="mb-2"><strong>Sub Category</strong></label>
                        <select class="custom-select form-control subcategory-select" name="sub_category"
                            id="filtersubCategory" required>
                            <option value="" disabled>Select Sub Category</option>
                            <option value="all" selected>All</option>
                        </select>
                    </div>

                    <div class="col-md-2 col-sm-12" style="margin-top: 7px">
                        <label for="filterLength"><strong>Length</strong></label>
                        <input type="number" class="form-control" name="length" id="filterLength" required step="0.01">
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="filterWarehouse" class="mb-2"><strong>Warehouse</strong></label>
                        <select class="custom-select form-control" name="warehouse" id="filterWarehouse" required>
                            <option value="" disabled>Select Warehouse</option>
                            <option value="all" selected>All</option>
                            @foreach ($warehouse as $warehouse)
                                <option value="{{ $warehouse->id }}">{{ $warehouse->warehouse_title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="" class="mb-2"><strong>Age Group</strong></label>
                        <select class="custom-select form-control" name="type" id="filterage" required>
                            <option value="" disabled>Select Age Group</option>
                            <option value="all">All</option>
                            <option value="0-30">0-30</option>
                            <option value="30-60">30-60</option>
                            <option value="60-90">60-90</option>
                            <option value="90+">90 +</option>
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
                                        <h4 class="text-blue h4">Ageing Report</h4>
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
                                        <th>Category​</th>
                                        <th>Sub Category​</th>
                                        <th>Length</th>
                                        <th>Warehouse</th>
                                        <th>Total Qty</th>
                                        <th>Average Age</th>
                                        <th>Age-Group</th>
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


    <script>
        $(document).ready(function() {
            $('.custom-select').select2();
                  // Focus the search box when the subcategory dropdown is opened
        $('.custom-select').on('select2:open', function () {
        document.querySelector('.select2-search__field').focus();
    });

        });
    </script>
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
                        title: 'Saraswati Globals (Ageing  Report)',

                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7],
                        }
                    },
                    {
                        extend: 'print',
                        text: 'PRINT',
                        title: 'Saraswati Globals (Ageing Report)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7],
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
        function filterButton(filterTodate, filterFromdate, filterCategory, filtersubCategory, filterWarehouse, filterage,
            filterlength) {
            $.ajax({
                type: 'POST',
                url: 'ageing-report-get',
                data: {
                    filterTodate: filterTodate,
                    filterFromdate: filterFromdate,
                    filterCategory: filterCategory,
                    filtersubCategory: filtersubCategory,
                    filterWarehouse: filterWarehouse,
                    filterage: filterage,
                    filterlength: filterlength,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response && Array.isArray(response)) {
                        var table = $('#Category_table').DataTable();
                        table.clear().draw();
                        response.forEach(function(data, index) {
                            // Prepare the row data
                            var rowData = [
                                index + 1,
                                data.category,
                                data.sub_category,
                                data.length,
                                data.warehouse,
                                data.total_qty,
                                data.average_age,
                                data.age_group,
                            ];

                            // Add the row to the table
                            var rowNode = table.row.add(rowData).draw(false).node();

                            // Check the age_group and apply the corresponding color
                            if (data.age_group === '0-30') {
                                $('td', rowNode).eq(7).css('color', 'green'); // 0-30 (Green)
                            } else if (data.age_group === '30-60') {
                                $('td', rowNode).eq(7).css('color', 'orange'); // 31-60 (Yellow)
                            } else if (data.age_group === '60-90') {
                                $('td', rowNode).eq(7).css('color', 'red'); // 61-90 (Red)
                            } else {
                                $('td', rowNode).eq(7).css('color', 'red'); // 90+ (Red)
                            }
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
                        htmldata += '<option value="all" selected>All</option>';
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
