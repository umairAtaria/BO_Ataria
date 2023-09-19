@extends('layouts.master')
@section('content')
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Swap Update</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Swap Update</li>
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
                        <div class="alert alert-danger" style="margin-bottom: 10px;">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="card-header">
                        <h3 class="card-title">Swap Update</h3>

                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('swap_import') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="">
                                            <input type="file" required class="form-control" name="uploaded_file"
                                                id="uploaded_file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                            <label>Select File to Upload <small
                                                    class="warning text-muted">{{ __('Please upload only Excel (.xlsx or .xls) files') }}</small></label>
                                        </div>
                                    </div>

                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>

                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
