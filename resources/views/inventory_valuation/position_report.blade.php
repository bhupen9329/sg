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

                                    <button class=" m-1 btn btn-primary" type="button"
                                    onclick="filterButton(
                                        $('#filterType').val(),  
                                        $('#filterTodate').val(),
                                        $('#filterFromdate').val(),
                                    )">
                                    Apply
                                </button>
                                
                                </div>

                            </div>
            
                            <!-- Transaction Logs -->
                            <h2>Transaction Logs</h2>
            
                            <div style="overflow-x: auto">
                                <table class="table table-bordered text-center" id="Category_table" style="width: 100%; border-collapse: collapse;">
                                    <thead>
                                        <tr style="background-color: #f2f2f2; text-align: center;">
                                            <!-- Table Headers with proper alignment -->
                                            <th style="padding: 8px;">Report Date</th>
                                            <th style="padding: 8px;">Item Name</th>
                                            <th style="padding: 8px;">Position (MT)</th>
                                            <th style="padding: 8px;">LIFO Valuation</th>
                                            <th style="padding: 8px;">FIFO Valuation</th>
                                            <th style="padding: 8px;">Manual Match</th>
                                            <th style="padding: 8px;">Monthly Average</th>
                                            <th style="padding: 8px;">Netwise</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {{-- @dd($avgData); --}}
                                        @if(!empty($lifoData))
                                            <!-- Table row with data -->
                                            <tr>
                                                <td style="padding: 8px;">{{ $lifoData['last_transaction_date'] ?? 'N/A' }}</td>
                                                <td style="padding: 8px;">{{ $lifoData['item_name'] ?? 'N/A' }}</td>
                                                <td style="padding: 8px;">{{ $lifoData['final_balance_qty'] ?? 'N/A' }}</td>
                                                <td style="padding: 8px;">
                                                    <a href="{{ route('show.lifo') }}" >
                                                        {{ $lifoData['final_balance_value'] ?? 'N/A' }}
                                                    </a>
                                                </td>
                                                <td style="padding: 8px;">
                                                    <a href="{{ route('show.fifo') }}" >
                                                        {{ $fifoData['final_balance_value'] ?? 'N/A' }}
                                                    </a>  
                                                </td>
                                                <td style="padding: 8px;">{{ $lifoData['manual_match'] ?? 'N/A' }}</td>
                                                <td style="padding: 8px;">
                                                    <a href="{{ route('show.average') }}" >
                                                        {{ $avgData['final_balance_value'] ?? 'N/A' }}
                                                    </a>
                                                </td>
                                             
                                                <td style="padding: 8px;">{{ $lifoData['netwise'] ?? 'N/A' }}</td>
                                            </tr>
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
                    </section>
                </div>
            </div>
            

                


              <!-- Modal HTML -->
<div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabel">Transaction Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <table class="table" id="">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Inward No.​</th>
                        <th>Date(DD/MM/YY)​</th>
                        <th>Company </th>
                        <th>Quantity (Q)</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                  
                        <tr>
                        
                            
                         
                         
                        </tr>
            
                </tbody>
            </table>
 
        </div>
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
        function filterButton(filterType, filterTodate, filterFromdate) {
       $.ajax({
           type: 'POST',
           url: 'get_position_report_list',
           data: {
               filterTodate: filterTodate,
               filterFromdate: filterFromdate,
               filterType: filterType,
               _token: "{{ csrf_token() }}"
           },
           success: function(response) {
               if (response && Array.isArray(response)) {
                   var table = $('#Category_table').DataTable();
                   table.clear().draw();
                   response.forEach(function(data, index) {
                       var indentQty = data.indent_qty ?? 0;
                       var poQty = data.po_qty ?? 0; // Default to 0 if null or undefined
                       var inwardQty = data.inward_qty ?? 0; // Default to 0 if null or undefined
                       var allocateQty = data.allocation_qty ?? 0;
                       
                       // Calculate remainingQty and pendingIndent
                       var remainingQty = poQty != 0 ? poQty - inwardQty : 0;
                       var pendingIndent = indentQty - (poQty + allocateQty);
   
                       table.row.add([
                           index + 1,
                           data.last_transaction_date,
                           data.item_name ?? 'N/A',
                           data.final_balance_qty ?? 'N/A',
                           data.quantity ?? 'N/A',
                           data.item_name ?? 'N/A',
                           data.item_name ?? 'N/A',
                           data.item_name ?? 'N/A',
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
   
       </script>








@endsection
