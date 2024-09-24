@extends('layouts.main')
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



        @if ($errors->any())
            <div class="tt active">
                <div class="tt-content">
                    <i class="fas fa-solid fa-xmark-circle error"></i>
                    <div class="message">
                        <span class="text text-1">Error</span>
                        <span class="text text-2">Shortage Update UnSuccessfully</span>
                    </div>
                </div>
                <i class="fa-solid fa-xmark close"></i>
                <div class="pg active"></div>
            </div>
        @endif
        <div class="dashboard-header  pagetitle">
            <h1>Shortage Details</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Shortage Details</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <form method="post" action="{{ route('setting.shortage_update') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card mt-3">
                            <div class="card-body ">
                                <h5 class="card-title">Shortage Details</h5>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="inputName5" class="form-label">Shortage Value</label><span
                                            class="required-classes">*</span>
                                        <input type="number" id="edit-input" name="name" class="form-control"
                                            value="{{ $shortage->name }}" required readonly>
                                        @if ($errors->has('shortage'))
                                            <p class="error">{{ $errors->first('shortage') }}</p>
                                        @endif
                                    </div><br>


                                    <div class="text-end mt-3">
                                        <button type="submit" class="btn btn-primary">Submit</button>
                                        <a class="btn btn-secondary" href="{{ route('dashboard') }}">Back</a>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('#edit-input').forEach(function(input) {
            input.style.backgroundColor = '#e9ecef';
            input.addEventListener('click', function() {
                this.removeAttribute('readonly');
                this.style.backgroundColor = 'white';
            });

            input.addEventListener('blur', function() {
                this.setAttribute('readonly', '');
                this.style.backgroundColor = '#e9ecef';


            });
        });
    });
</script>

