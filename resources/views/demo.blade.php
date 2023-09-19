{{-- @extends('layouts.master')

@section('content')

    <div class="main-container container-fluid">
        <div class="page-header">
            <h1 class="page-title">Dashboard</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
            </div>
        </div>



        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title mx-4">Connection Status</h3>
                        <button class="btn btn-primary sync">Import symbols</button>
                    </div>

                    <div class="card-body">

                        <table class="table table-bordered border-bottom" id="table">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Symbol</th>
                                    <th>Description</th>
                                    <th>SwapMode</th>
                                    <th>SwapLong</th>
                                    <th>SwapShort</th>
                                    <th>Swap3Day</th>
                                </tr>
                            </thead>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).on('click', '.sync', function() {

            Swal.fire({
                title: 'Are you sure?',
                text: 'This will replace all existing symbols!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Replace!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Loading...',
                        text: 'Please wait while syncing data...',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        onBeforeOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    // Make the Ajax call
                    $.ajax({
                        url: '{{ route('commission.sync') }}',
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                        },
                        success: function(response) {
                            if (response.message == 'success') {
                                // Close the loader when the response is received
                                Swal.fire(
                                    'Data Synced!',
                                    'Your data has been synced.',
                                    'success'
                                );

                                // Reload the DataTable after successful sync
                                $('#table').DataTable().ajax.reload();
                            }
                        },
                        error: function() {
                            // Handle errors and close the loader
                            Swal.fire(
                                'Error!',
                                'An error occurred while syncing data.',
                                'error'
                            );
                        },

                    });
                }
            });


        })
    </script>

    <script>
        $(function() {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ url('show/ajax') }}',
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'Symbol',
                        name: 'Symbol'
                    },
                    {
                        data: 'Description',
                        name: 'Description'
                    },
                    {
                        data: 'SwapMode',
                        name: 'SwapMode'
                    },
                    {
                        data: 'SwapLong',
                        name: 'SwapLong'
                    },
                    {
                        data: 'SwapShort',
                        name: 'SwapShort'
                    },
                    {
                        data: 'Swap3Day',
                        name: 'Swap3Day'
                    }
                ]
            });
        });
    </script>

@endsection --}}
