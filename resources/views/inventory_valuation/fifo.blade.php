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
            <h1>LIFO Report</h1>
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
                    <div class="container">
                        <h2 class="mb-4">FIFO Calculation Details for Item: {{ $item_name }}</h2>
                    
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Transaction Date</th>
                                    <th>Transaction Type</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Selling Price</th>
                                  
                                    <th>Balance Quantity</th>
                                    <th>Balance Value</th>
                                    <th>Cost of Goods Sold</th>
                                    <th>Profit/Loss</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction_logs as $log)
                                    <tr>
                                        <td>{{ $log['transaction_date'] }}</td>
                                        <td>{{ $log['transaction_type'] }}</td>
                                        <td>{{ $log['quantity'] }}</td>
                                        <td>{{ isset($log['unit_price']) ? number_format($log['unit_price'], 2) : '-' }}</td>
                                        <td>{{ isset($log['selling_price']) ? number_format($log['selling_price'], 2) : '-' }}</td>
                                       
                                        <td>{{ number_format($log['balance_qty'], 2) }}</td>
                                        <td>{{ number_format($log['balance_value'], 2) }}</td>
                                        <td>{{ number_format($log['cost_of_goods_sold'], 2) }}</td>
                                        <td>{{ number_format($log['profit_loss'], 2) }}</td>
                                        <td>{{ $log['status'] }}</td>
                                    </tr>
                                    @foreach($log['details'] as $detail)
                                    <tr>
                                        <td colspan="3">Detail</td>
                                        <td>{{ $detail['used_qty'] }}</td>
                                        <td>{{ isset($detail['unit_price']) ? number_format($detail['unit_price'], 2) : '-' }}</td>
                                        <td>{{ number_format($detail['remaining_qty'], 2) }}</td>
                                        <td>{{ number_format($detail['remaining_value'], 2) }}</td>
                                        <td colspan="3"></td>
                                    </tr>
                                @endforeach
                                @endforeach
                               
                            </tbody>
                        </table>
                    
                        <h4 class="mt-4">Final Summary</h4>
                        <ul>
                            <li><strong>Final Balance Quantity:</strong> {{ $final_balance_qty }}</li>
                            <li><strong>Final Balance Value:</strong> {{ number_format($final_balance_value, 2) }}</li>
                            <li><strong>Final Profit/Loss:</strong> {{ number_format($final_profit_loss, 2) }}</li>
                            <li><strong>Last Transaction Status:</strong> {{ $last_transaction_status }}</li>
                        </ul>
                    </div>
                </section>

                {{-- <section>

                    <table>
                        <thead>
                            <tr>
                                <th>Transaction Date</th>
                                <th>Transaction Type</th>
                                <th>Used Qty</th>
                                <th>Unit Price</th>
                                <th>Total Value</th>
                                <th>Current Balance Qty</th>
                                <th>Current Balance Value</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($calculatedLogs as $log)
                                <tr>
                                    <td>{{ $log['transaction_date'] }}</td>
                                    <td>{{ $log['transaction_type'] }}</td>
                                    <td>{{ number_format($log['used_qty'] ?? 0, 2) }}</td>
                                    <td>{{ number_format($log['unit_price'] ?? 0, 2) }}</td>
                                    <td>{{ number_format($log['total_value'] ?? 0, 2) }}</td>
                                    <td>{{ number_format($log['current_balance_qty'] ?? 0, 2) }}</td>
                                    <td>{{ number_format($log['current_balance_value'] ?? 0, 2) }}</td>
                                    <td>{{ $log['status'] ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                                      
        
        
                </section> --}}


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
            <div class="modal-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Used Qty</th>
                            <th>Unit Price</th>
                            <th>Remaining Qty</th>
                            <th>Remaining Value</th>
                        </tr>
                    </thead>
                    <tbody id="modalDetailsBody">
                        @if (isset($transaction_logs) && !empty($transaction_logs))
                            @foreach ($transaction_logs as $log)
                                @if (isset($log['details']) && !empty($log['details']))
                                    @foreach ($log['details'] as $detail)
                                        <tr>
                                            <td>{{ number_format($detail['used_qty'], 2) }}</td>
                                            <td>{{ number_format($detail['unit_price'], 2) }}</td>
                                            <td>{{ number_format($detail['remaining_qty'], 2) }}</td>
                                            <td>{{ number_format($detail['remaining_value'], 2) }}</td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="4">No transaction details available for this entry.</td>
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4">No logs available.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


                        


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
        function showModal(element) {
            const details = JSON.parse(element.getAttribute('data-details'));
            let modalBody = '';

            details.forEach((detail) => {
                modalBody += `
                <p><strong>Used Quantity:</strong> ${detail.used_qty}</p>
                <p><strong>Unit Price:</strong> ${detail.unit_price}</p>
                <p><strong>Remaining Quantity:</strong> ${detail.remaining_qty}</p>
                <p><strong>Remaining Value:</strong> ${detail.remaining_value}</p>
                <hr/>
            `;
            });

            document.querySelector('#transactionModal .modal-body').innerHTML = modalBody;
            $('#transactionModal').modal('show');
        }
    </script>











@endsection
