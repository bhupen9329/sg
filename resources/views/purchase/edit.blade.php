@extends('layouts.main')
@section('title', 'Purchase order - Saraswati Globals')
@section('content')
    <main id="main" class="main">

        <div class="dashboard-header pagetitle">
            <h1>Update Purchase Order</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Purchase Order</li>

                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <form method="POST" action="{{ route('purchase.update') }}">
                        @csrf
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Company Details</h5>

                                <!-- Horizontal Form -->

                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <div class="row  ">
                                            <label for="inputEmail3" class="col-sm-3  col-form-label"><strong>Company
                                                    Name : </strong></label>
                                            <div class="col-sm-4 ms-5">
                                                <label for="inputEmail3"
                                                    class="  col-form-label">{{ $company->company_name }}</label>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-6 text-end pe-5">
                                        <label for="inputEmail3" class="col-sm-6 col-form-label"><strong>PO Number :
                                            </strong>{{ $po_data->document_number }}</label>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label"><strong>Address :
                                        </strong></label>
                                    <div class="col-sm-4">

                                        <label for="inputEmail3" class="  col-form-label"> {{ $company->address }}</label>
                                    </div>

                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Date</strong><span
                                            class="required-classes">*</span></label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" name="date" id="inputPassword"
                                            value="{{ date('Y-m-d', strtotime($po_data->date)) }}" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label"><strong>Date</strong><span
                                            class="required-classes">*</span></label>
                                    <div class="col-sm-4">
                                        <input type="date" class="form-control" name="due_date" id="inputPassword"
                                            value="{{ date('Y-m-d', strtotime($po_data->due_date)) }}" required>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Select Item</h5>

                                        <div class="row">
                                            <div class="col-lg-12">

                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Category <span
                                                                    class="required-classes">*</span></th>
                                                            <th scope="col">Sub Category <span
                                                                    class="required-classes">*</span></th>
                                                            <th scope="col">Quantity (Q)</strong><span
                                                                    class="required-classes">*</span></th>
                                                            @can('price')
                                                                <th scope="col">Rate</strong> </th>
                                                            @endcan

                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <th scope="row">
                                                                <select id="inputState"
                                                                    class="form-select custom-select"onchange="get_sub_category(this)"
                                                                    name="category_id" required readonly>
                                                                    <option value="{{ $selected_category->id }}">
                                                                        {{ $selected_category->name }}</option>
                                                                    @foreach ($category as $item)
                                                                        @if ($selected_category->id != $item->id)
                                                                            <option value="{{ $item->id }}">
                                                                                {{ $item->name }}</option>
                                                                        @endif
                                                                    @endforeach
                                                                </select>
                                                            </th>
                                                            <th scope="row">
                                                                <select
                                                                    class="form-select sub_category_select subcategory-select"
                                                                    name="sub_category_id" required>
                                                                    <option value="{{ $selected_sub_category->id }}"
                                                                        selected>{{ $selected_sub_category->sub_category }}
                                                                    </option>
                                                                    {{-- @foreach ($sub_category as $c_item)
                                                                        <option value="{{ $c_item->id }}">
                                                                            {{ $c_item->sub_category }}</option>
                                                                    @endforeach --}}
                                                                </select>
                                                            </th>
                                                            <td>
                                                                <input type="number" name="quantity" class="form-control"
                                                                    value="{{ $po_data->quantity }}" id="inputDesignation"
                                                                    min="0" required>
                                                            </td>
                                                            @can('price')
                                                                <td>
                                                                    <input type="text" name="price" class="form-control"
                                                                        id="inputName5" value="{{ $po_data->price }}"  >
                                                                </td>
                                                            @endcan

                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>

                                        </div><br><br>


                                        <div class="row">
                                            <div class="col-sm-4">
                                                <label for="inputEmail3"
                                                    class="col-sm-2 col-form-label"><strong>Remarks</strong></label>
                                                <textarea class="form-control" name="remark" placeholder="Remark" value="{{ $po_data->remark }}" id="floatingTextarea"
                                                    style="height: 100px;">{{ $po_data->remark }}</textarea>
                                            </div>


                                        </div>

                                        <input type="hidden" name="company_id"
                                            class="form-control"value="{{ $company->id }}" required>
                                        <input type="hidden" name="po_id"
                                            class="form-control"value="{{ $po_data->document_number }}" required>
                                        {{-- ..........................................................  --}}

                                        <div class="text-end mt-3">
                                            @can('Purchase-edit')
                                                @can('price')
                                                    @if ($po_data->status == 'Open')
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    @endif
                                                @endcan
                                            @endcan

                                            {{-- <a class="btn btn-secondary"  href="{{ route('purchase.index') }}">Back</a> --}}
                                            <a class="btn btn-secondary" id="backButton">Back</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>



            </div>

        </section>

    </main><!-- End #main -->
    <script>
        function get_sub_category(selectElement) {
            let item_id = selectElement.value;
            let row = selectElement.parentNode.parentNode; // Get the parent row of the select element
            let subcategorySelect = row.querySelector(
                '.subcategory-select'); // Find the subcategory select element in the same row

            $.ajax({
                url: "{{ url('get_subcategory_list') }}",
                method: "POST",
                data: {
                    item_id: item_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    let data = JSON.parse(res)
                    if (data) {
                        let htmldata = '<option value="">Select Subcategory</option>';
                        for (let item of data) {
                            htmldata += `
                    <option value="${item.id}">${item.sub_category}</option>
                `;
                        }
                        subcategorySelect.innerHTML =
                            htmldata; // Populate the subcategory select element in the same row with dynamic options
                    }
                }
            });
        }
        $(document).ready(function() {
            $('.custom-select').select2();
            $('.sub_category_select').select2();

            // Focus the search box when the subcategory dropdown is opened
            $('.custom-select').on('select2:open', function() {
                document.querySelector('.select2-search__field').focus();
            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const backButton = document.getElementById('backButton');

            backButton.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default link behavior
                window.history.back(); // Go one step back in the browser history
            });
        });
    </script>
@endsection
