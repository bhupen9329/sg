@extends('layouts.main')
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Logs</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 16px;
            text-align: left;
        }
    
        table, th, td {
            border: 1px solid #dddddd;
        }
    
        th, td {
            padding: 12px;
            text-align: center;
        }
    
        th {
            background-color: #4CAF50;
            color: white;
        }
    
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
    
        .purchase {
            background-color: #d1ecf1;
        }
    
        .sell {
            background-color: #f8d7da;
        }
    
        .balance {
            background-color: #fff3cd;
        }
    
        .highlight {
            font-weight: bold;
            color: #343a40;
        }
    
        .aggregate {
            font-weight: bold;
            color: #FF5733;
        }
    </style>
</head>
@section('title','Sales Order reports - Saraswati Globals')
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
            <h1>LIFO Report</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">LIFO Report</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <div class="dashboard-header pagetitle">
            <h1>LIFO Report </h1>
            <div class="row" style="align-items: flex-end;">
                <div class="col-md-12 col-sm-12 d-flex justify-content-end">


                    <button class=" m-1 btn btn-primary" type="button"
                        onclick="filterButton(
                $('#filterTodate').val(),
                $('#filterFromdate').val(),
                $('#filterCompany').val(),
                $('#filterCategory').val(),
                $('#filterStatus').val()
            )">
                        Apply
                    </button>
                    <button class=" m-1 btn btn-primary" type="button" id="resetButton">Reset</button>
                </div>
            </div>

            <div class="page-header">
                <div class="row">
                    <div class="col-md-3 col-sm-12" style="margin-top: 7px">
                        <label for="filterTodate"><strong>From Date</strong></label>
                        <?php
                        $firstDayOfMonth = (new DateTime('first day of this month'))->format('Y-m-d');
                        ?>
                        <input type="date" class="form-control"  max="" value="<?php echo $firstDayOfMonth; ?>"  name="to_date" id="filterTodate" required>
                    </div>
                    <div class="col-md-3 col-sm-12" style="margin-top: 7px">
                        <label for="filterFromdate"><strong>To Date</strong></label>
                        <?php
                        $lastDayOfMonth = (new DateTime('last day of this month'))->format('Y-m-d');
                        ?>
                        <input type="date" class="form-control"  value="<?php echo $lastDayOfMonth; ?>"   name="from_date" id="filterFromdate" required>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="filterCompany" class="mb-2"><strong>Company</strong></label>
                        <select class="custom-select form-control" name="company_id" id="filterCompany" required>
                            <option value="" disabled>Select Company</option>
                            <option value="all" selected>All</option>
                            {{-- @foreach ($companys as $company)
                                <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                            @endforeach --}}
                        </select>
                    </div>

                    <div class="col-md-2 col-sm-12">
                        <label for="filterCategory" class="mb-2"><strong>Category</strong></label>
                        <select class="custom-select form-control" name="category" id="filterCategory" required>
                            <option value="" disabled>Select Category</option>
                            <option value="all" selected>All</option>
                            {{-- @foreach ($Categorys as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach --}}
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="container">
                <h1>LIFO Inventory Report</h1>
                
                <h2>Final Summary</h2>
                <p><strong>Final Balance Quantity:</strong> {{ $final_balance_qty }}</p>
                <p><strong>Final Balance Value:</strong> {{ number_format($final_balance_value, 2) }}</p>
                <p><strong>Total Profit/Loss:</strong> {{ number_format($final_profit_loss, 2) }}</p>
                
                <h2>Transaction Logs</h2>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Transaction Type</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Balance Qty</th>
                            <th>Balance Value</th>
                            <th>Cost of Goods Sold</th>
                            <th>Profit/Loss</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transaction_logs as $log)
                            <tr>
                                <td>{{ $log['transaction_date'] }}</td>
                                <td>{{ $log['transaction_type'] }}</td>
                                <td>{{ $log['quantity'] }}</td>
                                <td>{{ isset($log['unit_price']) ? number_format($log['unit_price'], 2) : 'N/A' }}</td>
                                <td>{{ $log['balance_qty'] }}</td>
                                <td>{{ number_format($log['balance_value'], 2) }}</td>
                                <td>{{ number_format($log['cost_of_goods_sold'] ?? 0, 2) }}</td>
                                <td>{{ number_format($log['profit_loss'] ?? 0, 2) }}</td>
                            </tr>
        
                            {{-- If it's a sell transaction, display the used and remaining quantities from purchases --}}
                            @if (!empty($log['details']))
                                <tr>
                                    <td colspan="8">
                                        <h4>Details for Sell Transaction:</h4>
                                        <table class="table table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Used Qty</th>
                                                    <th>Unit Price</th>
                                                    <th>Used Value</th>
                                                    <th>Remaining Qty from Batch</th>
                                                    <th>Remaining Value</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($log['details'] as $detail)
                                                    <tr>
                                                        <td>{{ $detail['used_qty'] }}</td>
                                                        <td>{{ number_format($detail['unit_price'], 2) }}</td>
                                                        <td>{{ number_format($detail['used_qty'] * $detail['unit_price'], 2) }}</td>
                                                        <td>{{ $detail['remaining_qty'] }}</td>
                                                        <td>{{ isset($detail['remaining_value']) ? number_format($detail['remaining_value'], 2) : 'N/A' }}</td> <!-- Added conditional check -->
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
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
                        title: 'Saraswati Globals (SO Report)',

                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18],
                        }
                    },
                    {
                        extend: 'print',
                        text: 'PRINT',
                        title: 'Saraswati Globals (SO Report)',
                        exportOptions: {
                            columns: [0, 1, 2, 3, 4, 5, 6, 12, 13, 14, 15,],
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
        function filterButton(filterTodate, filterFromdate, filterCompany, filterCategory,) {
            $.ajax({
                type: 'POST',
                url: 'report-so',
                data: {
                    filterTodate: filterTodate,
                    filterFromdate: filterFromdate,
                    filterCompany: filterCompany,
                    filterCategory: filterCategory,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if (response && Array.isArray(response)) {
                        var table = $('#Category_table').DataTable();
                        table.clear().draw();
                        response.forEach(function(data, index) {
                            table.row.add([
                                index + 1,
                                data.date,
                                data.so_number,
                                data.company_name,
                                data.total_quantity,
                                data.rest_qty,
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
    </script>
@endsection
