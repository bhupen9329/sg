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

        table,
        th,
        td {
            border: 1px solid #dddddd;
        }

        th,
        td {
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
@section('title', 'Sales Order reports - Saraswati Globals')
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
            <h1>Position Report</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">LIFO Report</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->


        <div class="dashboard-header pagetitle">
            <div class="breadcrum">
                <section class="section">
                    <div>
                        <div class="row mb-4">

                            <div class="col-md-4 col-sm-6">
                                <label for="date_filter" class="form-label">Select Filter</label>
                                <select class="form-select" id="filterType" name="filterType">
                                    <option value="">Select Filter Type</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="monthly">Monthly</option>
                                </select>
                            </div>
                            <div class="col-md-4 col-sm-6">
                                <label for="date_filter" class="form-label">Item Name</label>
                                <select class="form-select" id="category_filter" name="category">
                                    <option value="">Select Item</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 col-sm-6" style="margin-top: 7px">
                                <label for="filterTodate"><strong>From Date</strong></label>
                                <?php
                                $firstDayOfMonth = (new DateTime('first day of this month'))->format('Y-m-d');
                                ?>
                                <input type="date" class="form-control" value="<?php echo $firstDayOfMonth; ?>" name="to_date"
                                    id="filterTodate" required>
                            </div>
                            <div class="col-md-2 col-sm-6" style="margin-top: 7px">
                                <label for="filterFromdate"><strong>To Date</strong></label>
                                <?php
                                $lastDayOfMonth = (new DateTime('last day of this month'))->format('Y-m-d');
                                ?>
                                <input type="date" class="form-control" value="<?php echo $lastDayOfMonth; ?>" name="from_date"
                                    id="filterFromdate" required>
                            </div>
                            <div class="col-md-4 col-sm-12 d-flex align-items-end">

                               
                                <button class="m-1 btn btn-primary" type="button"
                                    onclick="filterButton(
                                        $('#filterType').val(),  
                                        $('#filterTodate').val(),
                                        $('#filterFromdate').val(),
                                        $('#category_filter').val() // Add the selected category here
                                    )">
                                    Apply
                                </button>


                            </div>

                            <!-- Transaction Logs -->
                            <h2>Transaction Logs</h2>

                            <div style="overflow-x: auto">
                                <table class="table table-bordered text-center" id="Category_table"
                                    style="width: 100%; border-collapse: collapse;">
                                    <thead>
                                        <tr style="background-color: #f2f2f2; text-align: center;">
                                            <!-- Table Headers with proper alignment -->
                                            <th style="padding: 8px;">Report Date</th>
                                            <th style="padding: 8px;">Item Name</th>
                                            <th style="padding: 8px;">Transaction Type</th>
                                            <th style="padding: 8px;">Quantity</th>
                                            <th style="padding: 8px;">Position (MT)</th>
                                            <th style="padding: 8px;">LIFO Valuation (र)</th>
                                            <th style="padding: 8px;">FIFO Valuation (र)</th>
                                            {{-- <th style="padding: 8px;">Manual Match</th> --}}
                                            <th style="padding: 8px;">Average Valuation (र)</th>
                                            {{-- <th style="padding: 8px;">Netwise</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @dd($avgData); --}}
                                        @if (!empty($lifoData))
                                            <!-- Table row with data -->
                                            {{-- @dump($lifo_transaction); @dump($inventory_transaction); --}}
                                            @foreach ($inventory_transaction as $data)
                                                <tr>
                                                    {{-- <td style="padding: 8px;">{{ ($data['transaction_date']) }}</td> --}}
                                                    <td style="padding: 8px;">{{ date('d-M-Y', strtotime($data['transaction_date'])) }}</td>
                                                    <td style="padding: 8px;">{{ $data['item_name'] ?? 'N/A' }}</td>
                                                    <td style="padding: 8px;">{{ $data['transaction_type'] ?? 'N/A' }}</td>
                                                    <td style="padding: 8px;">{{ $data['quantity'] ?? 'N/A' }}</td>
                                    
                                                    <!-- LIFO Transactions -->
                                                    @foreach ($lifo_transaction as $lifo_transactions)
                                                        @if ($data['id'] == $lifo_transactions['transaction_id'] && $data['item_id'] == $lifo_transactions['item_id'])
                                                            <td style="padding: 8px;">
                                                                {{ number_format($lifo_transactions['balance_qty'], 2) ?? 'N/A' }}
                                                            </td>
                                                            <td style="padding: 8px;">
                                                                <a href="{{ route('show.lifo', ['id' => $data['id'], 'item_id' => $data['item_id']]) }}">
                                                                    {{ number_format($lifo_transactions['balance_unit_price'], 2) ?? 'N/A' }}
                                                                </a>
                                                            </td>
                                                        @endif
                                                    @endforeach
                                    
                                                    <!-- FIFO Transactions -->
                                                    @foreach ($fifo_transaction as $fifo_transactions)
                                                        @if ($data['id'] == $fifo_transactions['transaction_id'] && $data['item_id'] == $fifo_transactions['item_id'])
                                                            <td style="padding: 8px;">
                                                                <a href="{{ route('show.fifo', ['id' => $data['id'], 'item_id' => $data['item_id']]) }}">
                                                                    {{ number_format($fifo_transactions['balance_unit_price'], 2) ?? 'N/A' }}
                                                                </a>
                                                            </td>
                                                        @endif
                                                    @endforeach


                                                    @foreach ($avg_transaction as $avg_transactions)
                                                    @if ($data['id'] == $avg_transactions['transaction_id'] && $data['item_id'] == $avg_transactions['item_id'])
                                                        <td style="padding: 8px;">
                                                            <a href="{{ route('show.average', ['id' => $data['id'], 'item_id' => $data['item_id']]) }}">
                                                                {{ number_format($avg_transactions['balance_unit_price'], 2) ?? 'N/A' }}
                                                            </a>
                                                        </td>
                                                    @endif
                                                @endforeach

                                                   
                                    
                                                    <!-- LIFO Manual Match and Netwise -->
                                                    {{-- <td style="padding: 8px;">{{ $lifoData['manual_match'] ?? 'N/A' }}</td> --}}
                                                    {{-- <td style="padding: 8px;">
                                                        
                                                            <a href="{{ route('show.average', ['id' => $data['id'], 'item_id' => $data['item_id']]) }}">
                                                            N/A
                                                        </a>
                                                    </td> --}}
                                                    {{-- <td style="padding: 8px;">{{ $lifoData['netwise'] ?? 'N/A' }}</td> --}}
                                                </tr>
                                            @endforeach
                                        @else
                                            <!-- No data row -->
                                            <tr>
                                                <td colspan="7" style="padding: 8px; text-align: center;">No data available</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <!-- Transaction Logs -->


                    </div>
                </section>
            </div>
        </div>








        <!-- Final Summary -->
        {{-- <h2>Final Summary</h2>
                        <p><strong>Final Balance Quantity:</strong> {{ $final_balance_qty }}</p>
                        <p><strong>Final Balance Value:</strong> {{ number_format($final_balance_value, 2) }}</p>
                        @if (isset($last_transaction_status))
                            <p><strong>Final Position:</strong> {{ $last_transaction_status }}</p>
                        @else
                            <p>No transactions recorded.</p>
                        @endif --}}

        {{-- <p><strong>Total Profit/Loss:</strong> {{ number_format($final_profit_loss, 2) }}</p> --}}


        </div>
        </section>
        </div>
        </div>











    </main><!-- End #main -->

    <!-- Add necessary JS and CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>


   


<script>
    function filterButton(filterType, toDate, fromDate, category) {
        $.ajax({
            url: '{{ route('item_name.filter_data') }}', // Your route for filtering
            type: 'GET', // or 'POST' depending on your setup
            
            data: {
                filterType: filterType,
                to_date: toDate,
                from_date: fromDate,
                category: $('#category_filter').val() // Include the selected category
            },

            success: function(response) {
                // Access the relevant data based on your returned structure
                let data = response.inventory_transaction; // Adjusted to match the controller's return
                // Clear the existing table body
                $('#Category_table tbody').empty();

                if (!data || data.length === 0) {
                    $('#Category_table tbody').append(
                        '<tr><td colspan="10" style="text-align: center;">No data available</td></tr>');
                    return;
                }

                // Populate the table with new data
                data.forEach(item => {
    let transactionDate = item?.transaction_date || 'N/A';
    let itemName = item?.item_name || 'N/A';
    let transactionType = item?.transaction_type || 'N/A';
    let transactionqty = item?.quantity || 'N/A';


    let row = `<tr>
         <td style="padding: 8px;">
        ${new Date(transactionDate).toLocaleDateString('en-GB')}
    </td>
        <td style="padding: 8px;">${itemName}</td>
        <td style="padding: 8px;">${transactionType}</td>
          <td style="padding: 8px;">${transactionqty}</td>`;

    // Handle LIFO data
    let lifoMatched = response.lifo_transaction.find(lifo =>
        lifo.transaction_id === item.id && lifo.item_id === item.item_id
    );

    if (lifoMatched) {
        row += `<td style="padding: 8px;">${lifoMatched.balance_qty || 'N/A'}</td>`;
        row += `<td style="padding: 8px;"><a href="/show_lifo_report/${item.id}/${item.item_id}">${formatNumber(lifoMatched.balance_unit_price)}</a></td>`;
    } else {
        row += `<td style="padding: 8px;">N/A</td><td style="padding: 8px;">N/A</td>`;
    }

    // Handle FIFO data
    let fifoMatched = response.fifo_transaction.find(fifo =>
        fifo.transaction_id === item.id && fifo.item_id === item.item_id
    );

    if (fifoMatched) {
        row += `<td style="padding: 8px;"><a href="/show_fifo_report/${item.id}/${item.item_id}">${formatNumber(fifoMatched.balance_unit_price)}</a></td>`;
    } else {
        row += `<td style="padding: 8px;">N/A</td>`;
    }

    let avgMatched = response.avg_transaction.find(avg =>
    avg.transaction_id === item.id && avg.item_id === item.item_id
    );

    if (avgMatched) {
        row += `<td style="padding: 8px;"><a href="/show_average_report/${item.id}/${item.item_id}">${formatNumber(avgMatched.balance_unit_price)}</a></td>`;
    } else {
        row += `<td style="padding: 8px;">N/A</td>`;
    }

    // Add manual match, average, and netwise fields
    
    row += `</tr>`;

    $('#Category_table tbody').append(row);
});

            },
            error: function(xhr, status, error) {
                console.error("Error fetching data: ", error);
                // Optionally, show an error message to the user
            }
        });
    }


    function number_format(number, decimals = 2) {
    if (isNaN(number) || number == null) {
        return 'N/A'; // Return 'N/A' if the input is not a number
    }

    // Round the number to the specified number of decimals
    number = parseFloat(number).toFixed(decimals);

    // Convert to string and add comma as thousand separator
    return number.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}


function formatNumber(number) {
    return number != null && !isNaN(number) ? parseFloat(number).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) : 'N/A';
}

</script>









@endsection
