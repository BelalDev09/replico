@extends('backend.app')

@section('title', 'Categories List')

@section('content')
    <div class="container-fluid">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
            <h3 class="mb-0">Categories</h3>

            <div>
                <button class="btn btn-danger btn-sm d-none" id="bulkDeleteBtn">
                    Delete Selected
                </button>

                <a href="{{ route('admin.categories.create') }}" class="btn btn-success btn-sm">
                    Add Category
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped align-middle" id="categories-table" width="100%">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th><input type="checkbox" id="select_all"></th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>

    </div>
@endsection

@push('scripts')
    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DataTables Responsive -->
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

    <script>
        let selectedIds = [];

        $(function() {

            let table = $('#categories-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,

                ajax: "{{ route('admin.categories.index') }}",

                columns: [{
                        data: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'bulk_check',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'status',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });

            // select all
            $('#select_all').on('change', function() {
                $('.select_data').prop('checked', this.checked);

                selectedIds = this.checked ?
                    $('.select_data').map(function() {
                        return $(this).val();
                    }).get() : [];

                toggleBulkButton();
            });

        });

        function select_single_item(id) {
            if (selectedIds.includes(id)) {
                selectedIds = selectedIds.filter(i => i != id);
            } else {
                selectedIds.push(id);
            }
            toggleBulkButton();
        }

        function toggleBulkButton() {
            selectedIds.length > 0 ?
                $('#bulkDeleteBtn').removeClass('d-none') :
                $('#bulkDeleteBtn').addClass('d-none');
        }

        function showDeleteAlert(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "This category will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: `/admin/categories/delete/${id}`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },

                        success: function(res) {
                            Swal.fire('Deleted!', res.message, 'success');
                            $('#categories-table').DataTable().ajax.reload();
                        }
                    });

                }
            });
        }

        function changeStatus(id) {

            Swal.fire({
                title: 'Are you sure?',
                text: "You want to change status!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, change it',
                cancelButtonText: 'Cancel'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.post(`/admin/categories/status/${id}`, {
                        _token: '{{ csrf_token() }}'
                    }, function(res) {

                        Swal.fire({
                            title: 'Updated!',
                            text: res.message,
                            icon: 'success',
                            timer: 1200,
                            showConfirmButton: false
                        });

                        $('#categories-table').DataTable().ajax.reload(null, false);
                    });

                }
            });
        }

        $('#bulkDeleteBtn').on('click', function() {

            if (selectedIds.length === 0) return;

            Swal.fire({
                title: 'Delete selected categories?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ route('admin.categories.bulk-delete') }}",
                        type: "POST",
                        data: {
                            _token: '{{ csrf_token() }}',
                            ids: selectedIds
                        },

                        success: function(res) {

                            Swal.fire('Deleted!', res.message, 'success');

                            selectedIds = [];
                            toggleBulkButton();

                            $('#select_all').prop('checked', false);
                            $('#categories-table').DataTable().ajax.reload();
                        }
                    });

                }
            });
        });
    </script>
@endpush
