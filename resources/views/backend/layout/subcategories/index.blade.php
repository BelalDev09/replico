@extends('backend.app')

@section('title', 'Sub Categories')

@section('content')
    <div class="container-fluid">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
            <h3 class="mb-0">Sub Categories</h3>

            <div class="d-flex gap-2">
                <button class="btn btn-danger btn-sm d-none" id="bulkDeleteBtn">
                    Delete Selected
                </button>

                <a href="{{ route('admin.sub-categories.create') }}" class="btn btn-success btn-sm">
                    Add Sub Category
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped align-middle" id="subcategories-table">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>
                            <input type="checkbox" id="select_all">
                        </th>
                        <th>Category</th>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let selectedIds = [];

        $(function() {

            let table = $('#subcategories-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,

                ajax: "{{ route('admin.sub-categories.index') }}",

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
                        data: 'category',
                        name: 'category'
                    },

                    {
                        data: 'image',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'name',
                        name: 'name'
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

        // SINGLE SELECT
        function select_single_item(id) {

            if (selectedIds.includes(id)) {
                selectedIds = selectedIds.filter(i => i != id);
            } else {
                selectedIds.push(id);
            }

            toggleBulkButton();
        }

        // SHOW/HIDE BULK BUTTON
        function toggleBulkButton() {

            if (selectedIds.length > 0) {
                $('#bulkDeleteBtn').removeClass('d-none');
            } else {
                $('#bulkDeleteBtn').addClass('d-none');
            }
        }

        // DELETE SINGLE
        function showDeleteAlert(id) {

            Swal.fire({
                title: 'Delete this subcategory?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: `/admin/sub-categories/${id}`,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {

                            Swal.fire('Deleted!', res.message, 'success');

                            $('#subcategories-table').DataTable().ajax.reload();

                            selectedIds = [];
                            toggleBulkButton();
                        }
                    });
                }
            });
        }

        // STATUS CHANGE
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

                    $.post(`/admin/sub-categories/status/${id}`, {
                        _token: '{{ csrf_token() }}'
                    }, function(res) {

                        Swal.fire({
                            title: 'Updated!',
                            text: res.message,
                            icon: 'success',
                            timer: 1200,
                            showConfirmButton: false
                        });

                        $('#subcategories-table').DataTable().ajax.reload(null, false);
                    });

                }
            });
        }

        // BULK DELETE
        $('#bulkDeleteBtn').on('click', function() {

            if (selectedIds.length === 0) return;

            Swal.fire({
                title: 'Delete selected items?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.post(`/admin/sub-categories/bulk-delete`, {
                        _token: '{{ csrf_token() }}',
                        ids: selectedIds
                    }, function(res) {

                        Swal.fire('Deleted!', res.message, 'success');

                        selectedIds = [];
                        toggleBulkButton();

                        $('#select_all').prop('checked', false);

                        $('#subcategories-table').DataTable().ajax.reload();
                    });
                }
            });
        });
    </script>
@endpush
