@extends('layouts.main')
@section('title', 'Index - Dispatch')
@section('content')
    <style>

    </style>
    <main id="main" class="main">
        @if ($message = Session::get('Credit_note_status'))
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
        @if ($message = Session::get('error'))
            <div class="tt active">
                <div class="tt-content">
                    <i class="fas fa-solid fa-times-circle error-icon"></i>
                    <div class="message">
                        <span class="text text-1">Error</span>
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
            <h1>Dispatch Summary</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Open Dispatch Positions</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
     
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="pd-20">
                                        <h4 class="text-blue h4">Dispatch</h4>
                                    </div>
                                </div>

                                
                                <div class="col-md-6 col-sm-12 d-flex justify-content-end">
                                    <div class="btn-group">
                                        @can('Inward-create')
                                            <a href="{{route('dispatch.create')}}" class="btn btn-primary mb-4 mr-3">Add Dispatch</a>
                                        @endcan
                                    </div>
                                 
                                  
                                </div>
                            </div>
                         <div style="overflow-x: auto">
                            <table class="table " id="Category_table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th style="width: 72.8125px;">Date</th>

                                        <th style="width: 84.8125px;">PO Item No</th>
                                        {{-- <th>PO Item Name</th> --}}
                                        <th>Buyer Name (Party Name)</th>
                                  

                                        <th>SO Item No</th>
                                        {{-- <th>SO Item Name</th> --}}
                                        <th>Seller Name (Party Name)</th>

                                        <th>PO Item Qty</th>
                                        <th>PO Item Rest Qty</th>
                                        <th>SO Item Qty</th>
                                        <th>SO Item Rest Qty</th>

                                        <th>Category</th>
                                        <th>Conv Item Name</th> 
                               
                                        <th>Dispatch Convt Rs</th>
                                        <th>Dispatch Total Rs</th>
                                        <th>Dispatch Qty</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                  @foreach($disaptch_data as $data)
                                  <tr>
                                   <td>{{$loop->iteration}}</td>
                                   <td>{{ date('d-m-Y', strtotime($data->dispatch_date)) }}</td>
                                   <td><a href="{{ route('purchase.edit', ['id' => $data->po_id]) }}">{{$data->po_item_no}}</a></td>
                                   {{-- <td>{{$data->category_name }}</td> --}}
                                   <td>{{$data->po_company }}</td>

                                   <td><a href="{{ route('sales.edit', ['id' => $data->so_id]) }}">{{$data->so_item_no}}</a></td>
                    
                                   {{-- <td>{{$data->so_item_no }}</td> --}}
                                   {{-- <td>{{$data->category_name }}</td> --}}
                                   <td>{{$data->so_company }}</td>

                                   <td>{{$data->po_qty }}</td>
                                   <td>{{$data->po_dispatch_rest_qty }}</td>
                                   <td>{{$data->so_qty }}</td>
                                   <td>{{$data->so_dispatch_rest_qty }}</td>

                                   <td>{{$data->category_name }}</td>
                                   <td>{{$data->sub_category_name }}</td>

                                   <td>{{$data->conv_rate }}</td>
                                   <td>{{$data->dispatch_total }}</td>
                                   <td>{{$data->dispatched_quantity }}</td>
                                   <td>
                                    <div class="filter">
                                        <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                                class="bi bi-three-dots"></i></a>
                                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                      
                                            <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('dispatch.edit', $data->dispatch_id) }}"><i
                                                    class="fa-solid fa-pencil"></i>Edit</a>
                                            
                                            </li>

                                            <li>
                                                        <form method="GET"
                                                            action="{{ route('dispatch.destroy', $data->dispatch_id) }}">
                                                            @method('DELETE')
                                                            <button type="button"
                                                                class="dropdown-item delete-button">
                                                                <i class="fa-solid fa-trash"></i> Delete
                                                            </button>
                                                        </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>

                                  </tr>
                                  @endforeach
                                </tbody>
                            </table>
                         </div>
                   
                            
                      

                        </div>
                    </div>
                </div>
            </div>
        </section>
<br><br><br>
   


    </main><!-- End #main -->





     <script>
        $(document).ready(function() {
            $('#Category_table').DataTable({
                dom: 'Bfrtip',
                // Set the default page length
                pageLength: 50,
                // Configure the drop down options
                lengthMenu: [
                    [10, 20, 50, 100, 150, -1],
                    ['10 rows', '20 rows', '50 rows', '100 rows', '150 rows', 'Show all']
                ],
                // Add to buttons the pageLength option
                buttons: [
                    'pageLength', 'csv', 'print'
                ]
            });
        });
    
</script>


@endsection
