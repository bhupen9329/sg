@extends('layouts.main')
@section('title','Email - Saraswati Globals')
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
                        <span class="text text-2">Email Setting update UnSuccessfully</span>
                    </div>
                </div>
                <i class="fa-solid fa-xmark close"></i>
                <div class="pg active"></div>
            </div>
        @endif

        <div class="pagetitle">
            <h1>Email Setting</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">Email Setting</li>

                </ol>
            </nav>
        </div><!-- End Page Title -->
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <form class="row g-3" method="post" action="{{ route('emails.update') }}">
                            @csrf
                            <div class="card-body">
                                <h5 class="card-title">Email Setting</h5>

                                <div class="col-md-12">
                                    <label for="inputName5" class="form-label">Mailer</label><span
                                    class="required-classes">*</span>
                                    <input type="text" id="edit-input" name="mailer" class="form-control"
                                        value="{{ $data->mailer }}" required readonly>
                                        @if ($errors->has('mailer'))
                                        <p class="error">{{ $errors->first('mailer') }}</p>
                                         @endif
                                </div>

                                <div class="col-md-12 mt-3">
                                    <label for="inputName5" class="form-label">Host </label><span
                                    class="required-classes">*</span>
                                    <input type="text" id="edit-input" name="host" class="form-control"
                                        value="{{ $data->host }}" required readonly>
                                    @if ($errors->has('host'))
                                   <p class="error">{{ $errors->first('host') }}</p>
                                    @endif
                                </div>

                                <div class="col-md-12 mt-3">
                                    <label for="inputName5" class="form-label">Port </label><span
                                    class="required-classes">*</span>
                                    <input type="text" id="edit-input" name="port" class="form-control"
                                        value="{{ $data->port }}" required readonly>
                                        @if ($errors->has('port'))
                                        <p class="error">{{ $errors->first('port') }}</p>
                                         @endif
                                </div>


                                <div class="col-md-12 mt-3">
                                    <label for="inputName5" class="form-label">Username</label><span
                                    class="required-classes">*</span>
                                    <input type="text" id="edit-input" name="username" class="form-control"
                                        value="{{ $data->username }}" required readonly>
                                        @if ($errors->has('username'))
                                        <p class="error">{{ $errors->first('username') }}</p>
                                         @endif
                                </div>

                                <div class="col-md-12 mt-3">
                                    <label for="inputName5" class="form-label">Key</label><span
                                    class="required-classes">*</span>
                                    <input type="text" id="edit-input" name="key" class="form-control"
                                        value="{{ $data->key }}" required readonly>
                                        @if ($errors->has('key'))
                                        <p class="error">{{ $errors->first('key') }}</p>
                                         @endif
                                </div>

                                <div class="col-md-12 mt-3">
                                    <label for="inputName5" class="form-label">From Address</label><span
                                    class="required-classes">*</span>
                                    <input type="text" id="edit-input" name="from_address" class="form-control"
                                        value="{{ $data->from_address }}" required readonly>
                                        @if ($errors->has('from_address'))
                                        <p class="error">{{ $errors->first('from_address') }}</p>
                                         @endif
                                </div>

                                <div class="col-md-12 mt-3">
                                    <label for="inputName5" class="form-label">From Name</label><span
                                    class="required-classes">*</span>
                                    <input type="text" id="edit-input" name="from_name" class="form-control"
                                        value="{{ $data->from_name }}" required readonly>
                                        @if ($errors->has('from_name'))
                                        <p class="error">{{ $errors->first('from_name') }}</p>
                                         @endif
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label for="inputName5" class="form-label">CC</label>
                                    <input type="text" id="edit-input" name="cc" class="form-control"
                                        value="{{ $data->cc }}" required readonly>

                                </div>
                                <div class="col-md-12 mt-3">
                                    <label for="inputName5" class="form-label">BCC</label>
                                    <input type="text" id="edit-input" name="bcc" class="form-control"
                                        value="{{ $data->bcc }}" required readonly>
                                </div>
                                <div class="text-end mt-3">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <a class="btn btn-secondary" href="/email">Back</a>
                                </div>

                            </div>
                        </form>

                    </div>

                </div>
            </div>



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
