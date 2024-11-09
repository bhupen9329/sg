@extends('layouts.main')
@section('title', 'Freight & Insurance - Saraswati Globals')
@section('content')
    <main id="main" class="main">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                {{ $message }}
            </div>
        @endif

        @if (session('msg'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: "Oops!",
                    text: "{{ session('msg') }}",
                    icon: "error"
                });
            });
        </script>
    @endif
        <div class="dashboard-header pagetitle">
            <h1>Edit Freight & Insurance Rate</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Edit Freight & Insurance Rate</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <form class="row g-3" method="post" action="{{ route('freight_rate.update', $data->id) }}">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Edit Freight & Insurance Rate</h5>
                                  <div class="row">
                                    <?php
                                    $currentDate = date('Y-m-d');
                                     ?>
                                    <div class="col-md-8 mt-4">
                                        <label for="date-input" class="form-label">Select Date</label><span
                                            class="required-classes">*</span>
                                        <input type="date" id="date-input" name="date" value="{{ $data->freight_rate_date }}" class="form-control"
                                            required>
                                    </div>

                                    <div class="col-md-8 mt-4">
                                        <label for="inputName5" class="form-label">Freight</label>
                                        <input type="number" id="price-input" name="freight_rate" value="{{ $data->freight_rate }}" min="1" class="form-control"
                                            >
                                    </div>

                                    <div class="col-md-8 mt-4">
                                        <label for="inputName5" class="form-label">Insurance</label>
                                        <input type="number" id="price-input" name="insurance_rate" value="{{ $data->insurance_rate }}"  min="1" class="form-control">
                                    </div>

                                    <div class="col-md-8 mt-4">
                                        <label for="remarks" class="form-label">Remarks</label>
                                        <textarea class="form-control" id="remarks" name="remarks" rows="3" value="{{ $data->remarks }}" placeholder="Enter remarks here...">{{ $data->remarks }}</textarea>
                                    </div>

                                    <div class="text-end mt-3">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a class="btn btn-secondary" href="{{ route('freight_rate.index') }}">Back</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            </div>
        </section>
    </main><!-- End #main -->
@endsection



