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
            <h1 class="page-title">Translations</h1>
            <div>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Translations</li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-xl-12 col-lg-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mx-4">Translations List</h3>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">Add
                            Translation</button>
                    </div>

                    <div class="card-body">
                        <input type="text" id="search_value" placeholder="Search..."
                            class="form-control col-md-3 py-3 my-4" style="float: right">
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
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Translation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST">
                            <div class="row mb-4">
                                <label class="col-md-3 form-label" for="t_name">Translation Name</label>
                                <div class="col-md-9">
                                    <input type="text" id="t_name" name="t_name" class="form-control">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <label class="col-md-3 form-label" for="s_name">Symbol Name ( in Manager )</label>
                                <div class="col-md-9">
                                    <input type="email" id="s_name" name="s_name" class="form-control">
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary add_trans">Save changes</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {

            var t4 = new Tabulator('#table', {
                ajaxURL: '{{ route('translations_ajax') }}',
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
                        title: "Translation",
                        field: "Translation",
                    },
                    {
                        title: "Symbol",
                        field: "Symbol",
                    },
                    {
                        title: "Action",
                        field: "Action",
                        formatter: "html",
                    },



                ],
            });

            t4.on('tableBuilt', function() {
                t4.setData('{{ route('translations_ajax') }}');
            });

            t4.on("cellEdited", function(cell) {

                var data = cell.getRow().getData();

                // Send the updated data to the server
                fetch('{{ route('group_update_index') }}', {
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
                t4.setFilter([{
                        field: "Translation",
                        type: "like",
                        value: $(this).val()
                    },

                ]);
            });

        });

        $(document).on('click', '.add_trans', function() {

            var t_name = $('#t_name').val();
            var s_name = $('#s_name').val();

            $.ajax({
                url: "{{ route('translations_save') }}",
                type: "POST",
                data: {
                    t_name: t_name,
                    s_name: s_name,
                    _token: "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (response.message == 'success') {
                        notif({
                            msg: "Data Added Successfully",
                            type: "success",
                            fade: true,
                            timeout: 2400,
                        });
                        $('#exampleModal').modal('hide');
                        $('#t_name').val('');
                        $('#s_name').val('');
                        var t2 = new Tabulator('#table', {
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
                                    title: "Translation",
                                    field: "Translation",
                                },
                                {
                                    title: "Symbol",
                                    field: "Symbol",
                                },
                                {
                                    title: "Action",
                                    field: "Action",
                                    formatter: "html",
                                },



                            ],
                        });
                        t2.on('tableBuilt', function() {
                            t2.setData('{{ route('translations_ajax') }}');
                        });
                    } else {
                        notif({
                            msg: "Data Added Failed",
                            type: "error",
                            fade: true,
                            timeout: 2400,
                        });
                    }
                }
            });

        })

        $(document).on('click', '.delbtn', function() {
            Swal.fire({
                title: 'Are you sure you want to delete this Transtaltion?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, Delete!',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('translations_delete') }}",
                        method: 'post',
                        data: {
                            id: $(this).attr('data-id'),
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(res) {
                            if (res.message == 'success') {
                                Swal.fire(
                                    'Deleted!',
                                    'Translation has been deleted.',
                                    'success'
                                )
                                var t2 = new Tabulator('#table', {
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
                                    title: "Translation",
                                    field: "Translation",
                                },
                                {
                                    title: "Symbol",
                                    field: "Symbol",
                                },
                                {
                                    title: "Action",
                                    field: "Action",
                                    formatter: "html",
                                },



                            ],
                        });
                        t2.on('tableBuilt', function() {
                            t2.setData('{{ route('translations_ajax') }}');
                        });
                            }
                        }
                    })
                }
            });

        });
    </script>
@endsection
