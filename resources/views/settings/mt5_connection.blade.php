@extends('layouts.master')
@section('content')
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">MT5 Connection</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">MT5 Connection</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card">
                 @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-dismissible close-2 show px-4 py-4 mx-2 my-2" role="alert" style="margin-bottom: 10px;">
                            <strong>{{ $message }}</strong>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible close-2 show px-4 py-4 mx-2 my-2" style="margin-bottom: 10px;">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="card-header">
                        <h3 class="card-title mx-4">Connection Status</h3>
                        <div class="spinner-border text-warning me-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <h4 id="con_status" class="" role="alert">
                        </h4><br>
                    </div>
                    <div class="card-header">
                        <h3 class="card-title">MT5 Backoffice Connection Details</h3>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('save_connection') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="floatingInput"
                                                placeholder="IP Address" name="mt5_ip" @if (isset($credentials)) { value="{{ $credentials->ip }}" } @endif>
                                            <label for="floatingInput">MT5 IP Address</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="floatingPort" placeholder="Port"
                                                name="mt5_port" @if (isset($credentials)) {  value="{{ $credentials->port }}" } @endif>
                                            <label for="floatingPort">Port</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-floating">
                                            <input type="text" class="form-control" id="floatingLogin"
                                                placeholder="Login" name="mt5_login" @if (isset($credentials)) { value="{{ $credentials->login }}"} @endif>
                                            <label for="floatingLogin">Login</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" id="floatingPassword"
                                                placeholder="Password" name="mt5_password" value="**********">
                                            <label for="floatingPassword">Password</label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('form').submit(function() {
                $(this).find('button[type="submit"]').prop('disabled', true);
                $(this).find('button[type="submit"]').html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                );
            });

            $.ajax({
                url: "{{ route('con') }}",
                type: 'POST',
                dataType: 'json',
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    console.log(data.message);
                    if (data.message != 0) {
                        $('#con_status').html('Disconnected');
                    } else if (data.message == 0) {
                        $('.spinner-border').hide();
                        $('#con_status').addClass('alert alert-success px-4 ml-4');
                        $('#con_status').html('Connected');
                    }
                },
                error: function(data) {
                    console.log(data);
                    if (data.message != 0) {
                        $('.spinner-border').hide();
                        $('#con_status').html('Disconnected');
                    } else if (data.message == 0) {
                        $('#con_status').html('<span class="text-green-500">Connected</span>');
                    }
                }
            });

            // hide alert after 3 seconds . fade out
            $('.alert').delay(3000).fadeOut(500);

        });
    </script>
@endsection
