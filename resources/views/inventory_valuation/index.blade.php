@extends('layouts.main')
@section('title', 'Inward - Saraswati Globals')
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
            <h1>Inventory Details</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Inventory</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">


            <div class="row">

                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="row ">
                                <div class="col-md-6 col-sm-12">
                                    <div class="pd-20">
                                        <h4 class="text-blue h4">Inventory Details</h4>

                                    </div>
                                </div>
                          
                                <div class="col-md-6 col-sm-12 d-flex justify-content-end ">
                                    <div class="btn-group">
                                        @can('Inward-create')
                                            <a href="#" class="btn btn-primary mb-4 mr-3" data-bs-toggle="modal"
                                                data-bs-target="#PurchaseinwardModal"
                                                >
                                                Add Inventory</a>
                                        @endcan
                                    </div>
                                    <div class="btn-group ps-3">
                                        @can('Inward-create')
                                            <a href="{{ route('inventory.lifo')}}" class="btn btn-primary mb-4 mr-3" 
                                                >
                                                LIFO</a>
                                        @endcan
                                    </div>
                                    {{-- <div class="btn-group">
                                        @can('Inward-create')
                                            <a class="btn btn-primary mb-4 mr-4" data-bs-toggle="modal"
                                                data-bs-target="#PurchaseinwardModal">
                                                FIFO</a>
                                        @endcan
                                    </div> --}}
                                    {{-- <div class="btn-group">
                                        @can('Inward-create')
                                            <a class="btn btn-primary mb-4 mr-3" data-bs-toggle="modal"
                                                data-bs-target="#PurchaseinwardModal">
                                                AVERAGE</a>
                                        @endcan
                                    </div> --}}
                                </div>

                                
                            </div>
                            <!-- Table with stripped rows -->
                            <table class="table" id="Category_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        {{-- <th>Inward No.​</th> --}}
                                        <th>Transaction Date​</th>
                                        <th>Item Name​</th>
                                        <th>Type </th>
                                        <th>Unit Price </th>
                                        <th>Quantity (Q)</th>
                                        {{-- <th>Status</th>
                                        <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inventory as $data) 
                                        <tr>
                                          
                                          
                                             <td>{{ $loop->iteration }}</td>
                                             <td>{{ date('d-m-Y', strtotime($data->transaction_date)) }}</td>
                                            <td>{{ $data->item_name }}</td>                                           
                                            <td>{{ $data->transaction_type }}</td>
                                            <td>{{ $data->unit_price }}</td>
                                            <td>{{ $data->quantity }}</td> 
                                         
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <!-- End Table with stripped rows -->

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </main><!-- End #main -->


    {{-- .................................. Modal for Credit Note.............................  --}}
    <div class="modal fade" id="ModalforCredit_Note" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal3Label">Update Credit Note Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"style="width:50px"></button>
                </div>
                <form action="{{ route('change_credit_note.status') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Content goes here -->
                        <div class="col-lg-12">
                            <div class="form-group">
                                <select name="credit_note_status" id="item_id${lastItemId}" style="height: 34px; "
                                    class="form-control item-select-${lastItemId}" required>
                                    <option value="" disabled selected>Select Status</option>
                                    <option value="Credit Note Generated">Credit Note Generated</option>
                                    <option value="Credit Note Pending">Credit Note Pending</option>
                                </select>

                                <input type="hidden" name="inward_id" id="set_po_id">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="PurchaseinwardModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal3Label">Add Inventory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"style="width:50px"></button>
            </div>
            <form action="{{ route('store_inventory') }}" method="post">
                @csrf
                <div class="modal-body">
                    <!-- Dropdown for selecting Purchase or Sell -->
                    <div class="form-group">
                        <label for="type">Select Type:</label>
                        <select name="type" id="selected_type_purchase" class="form-control" required>
                            <option value="">Select Type</option>
                            <option value="purchase">Purchase</option>
                            <option value="sell">Sell</option>
                        </select>
                    </div>
            
                    <!-- Form fields for data entry -->
                    <div class="form-group">
                        <label for="item_name">Item Name:</label>
                        <input type="text" name="item_name" class="form-control" required>
                    </div>
            
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" name="quantity" class="form-control" required>
                    </div>
            
                    <div class="form-group">
                        <label for="price">Unit Price:</label>
                        <input type="number" step="0.01" name="price" class="form-control" required>
                    </div>
            
                    <!-- Additional fields can be added here -->
                </div>
            
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
            
        </div>
    </div>
  </div>


    <!-- Modal -->
    {{-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="{{ route('inward.create') }}" method="post">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Select Company</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <label for="" class="mb-2">Select Company <span
                                    class="required-classes">*</span></label>
                            <div class="col-lg-12">
                                @livewire('purchase')
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </div>
            </div>
        </form>
    </div> --}}

   

   

    <script>
        function get_po_id(po_id) {
            po_id = po_id;
            // console.log(po_id);
            $('#set_po_id').val(po_id);

        }
        document.getElementById("typeSelect").addEventListener("change", function() {
            var type = this.value;

            // $('#get_selected_type').val(type);
            // $('#selected_type_purchase').val(type);

            // // console.log(type);
            // if (type == 'Sales Return') {
            //     $('#warehouseModal').modal('show');
            // } else {
            //     $('#PurchaseinwardModal').modal('show');
            // }
        });
    </script>

  



    <script>
        function get_supplier_id(value) {
            let id = value;
            let SelectOPOption = document.querySelector('.select_po_number');
            // console.log(SelectOPOption);
            $.ajax({
                url: "{{ url('get_po_number_for_supplier') }}",
                method: "POST",
                data: {
                    supplier_id: id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    // console.log(res);

                    let data = res;
                    if (data) {
                        let htmldata = '<option value="">Select PO Number</option>';
                        for (let item of data) {
                            htmldata += `
                    <option value="${item.document_number}">${item.document_number}</option>
                `;
                        }
                        SelectOPOption.innerHTML =
                            htmldata; // Populate the subcategory select element in the same row with dynamic options
                    }
                }
            })
        }
        $(document).ready(function() {
            $('.table.dataTable').removeClass('no-footer');
        });
    </script>
@endsection
