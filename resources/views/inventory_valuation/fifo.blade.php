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
                    <div style="overflow-x: auto">
                        {{-- <h2 class="mb-4">FIFO Calculation Details for Item: {{ $transaction_logs }}</h2> --}}
                    
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Transaction Date</th>
                                    <th>Transaction Type</th>
                                    <th colspan="3" class="text-center">Purchase</th>
                                    <th colspan="3" class="text-center">Sell</th>
                                    <th colspan="3" class="text-center">Balance</th>
                                    <th colspan="3" class="text-center">Used Qty</th>
                                    <th colspan="3" class="text-center">Stock Balance</th>
                                    <th colspan="3" class="text-center">Cost of Goods Sold(COGS)</th>
                                    <th colspan="2" class="text-center">Actual Sales</th>
                                    <th>Profit/Loss</th>
                                    <th>Status</th>
                                    {{-- <th>Break Even Unit Price</th> --}}
                                    <th>Action</th>
                                </tr>
                                <tr>

                                    <th></th>
                                    <th></th>
                                    <!-- Purchase   -->
                                    <th class="text-center">Qty MT </th>
                                    <th class="text-center">Rate</th>
                                    <th class="text-center">Amount</th>
                                    <!-- Sell -->
                                    <th class="text-center">Qty MT </th>
                                    <th class="text-center">Rate</th>
                                    <th class="text-center">Amount</th>

                                    <!-- Balance -->
                                    <th class="text-center">Qty MT </th>
                                    <th class="text-center">Rate</th>
                                    <th class="text-center">Amount</th>


                                    <!-- Used Qty -->
                                    <th class="text-center">Qty MT </th>
                                    <th class="text-center">Rate</th>
                                    <th class="text-center">Amount</th>


                                    <!-- Stock balance -->
                                    <th class="text-center">Bal Qty </th>
                                    <th class="text-center">Bal Value</th>
                                    <th class="text-center">Position</th>

                                    <!-- COGS -->
                                    <th class="text-center">Qty </th>
                                    <th class="text-center">Unit COGS Price</th>
                                    <th class="text-center">COGS</th>

                                    <!-- Actual Sales -->
                                    <th class="text-center">Unit Sell Price </th>
                                    <th class="text-center">Sales</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transaction_logs as $log)
                                    <tr>
                                        <td>{{ $log['transaction_date'] }}</td>
                                        <td class="transaction-type"
                                            @if (isset($log['details'])) data-details="{{ json_encode($log['details']) }}" @endif>
                                            <a href="javascript:void(0);"
                                                onclick="showModal(this);">{{ $log['transaction_type'] }}</a>
                                        </td>

                                         <!-- Purchase -->
                                         <td>
                                            @if ($log['transaction_type'] == 'Purchase')
                                                +{{ number_format($log['quantity'], 2) }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($log['transaction_type'] == 'Purchase')
                                                {{ isset($log['unit_price']) ? number_format($log['unit_price'], 2) : 'N/A' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if ($log['transaction_type'] == 'Purchase' && isset($log['unit_price']))
                                                {{ number_format($log['quantity'] * $log['unit_price'], 2) }}
                                              
                                            @else
                                               
                                            @endif
                                        </td>

                                          <!-- Sell -->

                                          <td>
                                            @if ($log['transaction_type'] == 'Sell')
                                                -{{ number_format($log['quantity'], 2) }}
                                            @endif
                                        </td>                                   
                                        <td>
                                            @if ($log['transaction_type'] == 'Sell')
                                                {{ isset($log['selling_price']) ? number_format($log['selling_price'], 2) : 'N/A' }}
                                            @endif
                                        </td>                                 
                                        <td>
                                            @if ($log['transaction_type'] == 'Sell' && isset($log['selling_price']))
                                                {{ number_format($log['quantity'] * $log['selling_price'], 2) }}
                                              
                                            @else
                                               
                                            @endif
                                        </td>     
                                        
                                        
                                          <!-- Balance -->                         
                                            <td></td>
                                            <td></td>
                                            <td></td>

                                            <!-- Used Qty -->                         
                                            <td>
                                                <?php
                                                $totalUsedQty = 0;
                                                $totalPurchaseQty = 0;
                                                $totalSellQty = 0;
                                                ?>
                                                @if (isset($log['details']) && is_array($log['details']))
                                                    @foreach ($log['details'] as $detail)
                                                        @if ($log['transaction_type'] == 'Purchase')
                                                            <?php $totalPurchaseQty += $detail['used_qty']; ?>
                                                            <!-- Display purchase details -->
                                                            <div>
                                                                {{ number_format($detail['used_qty'], 2) }},

                                                            </div>
                                                        @elseif ($log['transaction_type'] == 'Sell')
                                                            <?php $totalSellQty += $detail['used_qty']; ?>

                                                            <div>
                                                                {{ number_format($detail['used_qty'], 2) }},

                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    N/A
                                                @endif
                                               
                                            </td>
                                            <td>
                                                <?php
                                                $totalUsedQty = 0;
                                                $totalPurchaseQty = 0;
                                                $totalSellQty = 0;
                                                ?>
                                                @if (isset($log['details']) && is_array($log['details']))
                                                    @foreach ($log['details'] as $detail)
                                                        @if ($log['transaction_type'] == 'Purchase')
                                                            <?php $totalPurchaseQty += $detail['used_qty']; ?>
                                                            <!-- Display purchase details with unit price -->
                                                            <div>

                                                                {{ number_format($detail['unit_price'], 2) }}
                                                            </div>
                                                        @elseif ($log['transaction_type'] == 'Sell')
                                                            <?php $totalSellQty += $detail['used_qty']; ?>
                                                            <!-- Display sell details with unit price -->
                                                            <div>

                                                                {{ number_format($detail['unit_price'], 2) }}
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    N/A
                                                @endif


                                            </td>
                                            <td>
                                                <?php
                                                $totalPurchaseQty = 0;
                                                $totalSellQty = 0;
                                                $totalPurchaseValue = 0;
                                                $totalSellValue = 0;
                                                ?>
                                                @if (isset($log['details']) && is_array($log['details']))
                                                    @foreach ($log['details'] as $detail)
                                                        @if (strtolower($log['transaction_type']) == 'purchase')
                                                            <?php
                                                            $totalPurchaseQty += $detail['used_qty'];
                                                            $purchaseValue = $detail['used_qty'] * $detail['unit_price']; 
                                                            $totalPurchaseValue += $purchaseValue;
                                                            ?>
                                                            <div>
                                                                <!-- Displaying the multiplied value for purchase -->
                                                                {{ number_format($purchaseValue, 2) }} <!-- Formatted to 2 decimal places -->
                                                            </div>
                                                        @elseif (strtolower($log['transaction_type']) == 'sell')
                                                            <?php
                                                            $totalSellQty += $detail['used_qty'];
                                                            $sellValue = $detail['used_qty'] * $detail['unit_price']; 
                                                            $totalSellValue += $sellValue;
                                                            ?>
                                                            <div>
                                                            
                                                                {{ number_format($detail['unit_price'], 2) }} @ {{ number_format($detail['used_qty'], 2) }} = 
                                                                {{ number_format($sellValue, 2) }} 
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            
                                                         
                                            <!-- Stock balance -->
                                            <td>{{ number_format($log['balance_qty'], 2) }}</td>
                                            <td>{{ number_format($log['balance_value'], 2) }}</td>
                                            <td>{{ $log['status'] }}</td>

                                               <!-- COGS -->                         
                                               <td></td>
                                               <td></td>
                                               <td></td>


                                                <!-- Actual Sales -->                         
                                                <td></td>
                                                <td></td>
                                               
                                             <!-- P/L and Status -->   
                                                <td></td>
                                                <td></td>
                                               



                                        <td><button type="button" class="btn btn-info" data-toggle="modal"
                                            data-target="#transactionModal"
                                            data-details='@json($log)'>View
                                            Transaction</button></td>
                                    </tr>

                                @endforeach
                               
                            </tbody>
                        </table>
                    
                     
                    </div>
                </section>

                     
        
        
             


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
