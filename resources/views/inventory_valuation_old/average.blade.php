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
                        <div class="container">
                            <h1>Average Cost Inventory</h1>
                            <h2>Item: {{ $item_name }}</h2>
                        
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Transaction Type</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Transaction Date</th>
                                        <th>Balance Quantity</th>
                                        <th>Balance Value</th>
                                        <th>Average Cost</th>
                                        <th>Cost of Goods Sold</th>
                                        <th>Profit/Loss</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaction_logs as $log)
                                        <tr>
                                            <td>{{ $log['transaction_type'] }}</td>
                                            <td>{{ $log['quantity'] }}</td>
                                            <td>
                                                @if(isset($log['selling_price']))
                                                    {{ number_format($log['selling_price'], 2) }}
                                                @else
                                                    {{ number_format($log['unit_price'], 2) }}
                                                @endif
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($log['transaction_date'])->format('Y-m-d') }}</td>
                                            <td>{{ $log['balance_qty'] }}</td>
                                            <td>{{ number_format($log['balance_value'], 2) }}</td>
                                            <td>{{ number_format($log['average_cost'], 2) }}</td>
                                            <td>{{ isset($log['cost_of_goods_sold']) ? number_format($log['cost_of_goods_sold'], 2) : 'N/A' }}</td>
                                            <td>{{ number_format($log['profit_loss'], 2) }}</td>
                                        </tr>
                                        @if(isset($log['details']) && count($log['details']) > 0)
                                            <tr>
                                                <td colspan="9">
                                                    <table class="table table-sm">
                                                        <thead>
                                                            <tr>
                                                                <th>Used Quantity</th>
                                                                <th>Unit Price</th>
                                                                <th>Remaining Quantity</th>
                                                                <th>Remaining Value</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach($log['details'] as $detail)
                                                                <tr>
                                                                    <td>{{ $detail['used_qty'] }}</td>
                                                                    <td>{{ number_format($detail['unit_price'], 2) }}</td>
                                                                    <td>{{ $detail['remaining_qty'] }}</td>
                                                                    <td>{{ number_format($detail['remaining_value'], 2) }}</td>
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
                        
                            <p>Total Quantity: {{ $final_balance_qty }}</p>
                            <p>Total Value: {{ $final_balance_value }}</p>
                            {{-- <p>Total Profit/Loss: {{ number_format($final_profit_loss, 2) }}</p> --}}
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

            <table class="table" id="Category_table">
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











@endsection
