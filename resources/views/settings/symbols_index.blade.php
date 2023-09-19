@extends('layouts.master')

@section('content')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js"
        integrity="sha512-57oZ/vW8ANMjR/KQ6Be9v/+/h6bq9/l3f0Oc7vn6qMqyhvPd1cvKBRWWpzu0QoneImqr2SkmO4MSqU+RpHom3Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link href="https://unpkg.com/tabulator-tables@5.5.2/dist/css/tabulator.min.css" rel="stylesheet">
    <script type="text/javascript" src="https://unpkg.com/tabulator-tables@5.5.2/dist/js/tabulator.min.js"></script>
    <script src="https://cdn.tutorialjinni.com/notify/0.4.2/notify.min.js"></script>


    <link href="{{ asset('assets/css/tab.min.css') }}" rel="stylesheet">
    <!-- Include Tabulator JavaScript -->
    <script src="{{ asset('assets/js/tab.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/jq_wrapper.min.js') }} "></script>
    <style>
        .swal2-container {
            z-index: 11000 !important;
        }
    </style>
    <div class="main-container container-fluid">
        <!-- PAGE-HEADER -->
        <div class="page-header">
            <h1 class="page-title">Symbols</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Symbols</li>

                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mx-4">Symbols List</h3>
                        <button class="btn btn-primary sync">Import symbols</button>
                    </div>

                    <div class="card-body">
                        <input type="text" id="search_value" placeholder="Search..." class="form-control col-md-3 py-3 my-4" style="float: right">
                        <div class="table-responsive">
                            <table class="table table-bordered text-nowrap border-bottom" id="table" width="100%">
                                <thead>                                    
                                </thead>
                            </table>
                        </div>

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
                        icon: 'info', // set the icon property to 'info' or any other icon you prefer
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        onBeforeOpen: () => {
                            Swal.showLoading();
                        }
                    });


                    // Make the Ajax call
                    $.ajax({
                        url: '{{ route('commission.sync') }}',
                        type: 'GET',
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
        $(document).ready(function() {

            var t4 = new Tabulator('#table', {
                ajaxURL: '{{ route('symbols_ajax') }}',
                ajaxConfig: "GET",
                ajaxContentType: "json",
                layout: "fitColumns",
                pagination: "local",
                paginationSize: 10,
                paginationSizeSelector: [10, 25, 50, 100],
                movableColumns: true,
                paginationCounter: "rows",
                ajaxResponse: function(url, params, response) {
                    return response.data;
                },
                columns: [{
                        title: "ID",
                        field: "id",
                    },
                    {
                        title: "Symbol",
                        field: "Symbol",
                    },
                    {
                        title: "Description",
                        field: "Description",
                        editor: "input"
                    },
                    {
                        title: "Swap Mode",
                        field: "SwapMode",
                        editor: "select",
                        editorParams: {
                            values: {
                                "0": "Points",
                                "1": "Currency",
                                "2": "Profit",
                                "3": "Margin",
                                "4": "Group",
                                "5": "CurrentPrice (%)",
                                "6": "Open Price (%)",
                            }
                        }
                    },
                    {
                        title: "Swap Long",
                        field: "SwapLong",
                        editor: "input"
                    },
                    {
                        title: "Swap Short",
                        field: "SwapShort",
                        editor: "input"
                    },
                    {
                        title: "Swap 3 Day",
                        field: "Swap3Day",
                        editor: "select",
                        editorParams: {
                            values: {
                                "0": "Sunday",
                                "1": "Monday",
                                "2": "Tuesday",
                                "3": "Wednesday",
                                "4": "Thursday",
                                "5": "Friday",
                                "6": "Saturday",
                            }
                        }
                    },

                ],
            });


            t4.on('tableBuilt', function() {
                t4.setData('{{ route('symbols_ajax') }}');
            });
            t4.on("cellEdited", function(cell) {

                var data = cell.getRow().getData();

                // Send the updated data to the server
                fetch('{{ route('swap.update') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // CSRF token for Laravel
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message == 'success') {
                            notif({
                                msg: "Data Updated Successfully",
                                type: "success",
                                fade: true,
                                timeout: 2400,
                            });
                            console.log("Update successful!");
                        } else {
                            console.log("Error updating cell:", data.error);
                        }
                    })
                    .catch(error => {
                        console.error("Error sending updated cell data:", error);
                    });
            });

            $('#search_value').on('keyup', function() {
                t4.setFilter([
                    [{
                        field: "Symbol",
                        type: "like",
                        value: $(this).val()
                    }]
                ]
                );
            });
            
        });
    </script>
@endsection
