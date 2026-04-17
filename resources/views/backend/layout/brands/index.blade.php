@extends('backend.app')

@section('title', 'Brands')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3 class="mb-0">Brands</h3>

            <div>
                <button class="btn btn-danger btn-sm d-none" id="bulkDeleteBtn">
                    Delete Selected
                </button>

                <a href="{{ route('admin.brands.create') }}" class="btn btn-success btn-sm">
                    Add Brand
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped align-middle" id="brandsTable" width="100%">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th><input type="checkbox" id="select_all"></th>
                        <th>Logo</th>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let selectedIds = [];

        $(function() {

            let table = $('#brandsTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,

                ajax: "{{ route('admin.brands.index') }}",

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
                        data: 'logo',
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
                    },
                ]
            });

            // SELECT ALL
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
        function deleteBrand(id) {

            Swal.fire({
                title: 'Are you sure?',
                text: "This brand will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: `/admin/brands/${id}`,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },

                        success: function(res) {
                            Swal.fire('Deleted!', res.message, 'success');
                            $('#brandsTable').DataTable().ajax.reload(null, false);
                        }
                    });

                }
            });
        }

        // STATUS CHANGE
        function changeStatus(id) {

            Swal.fire({
                title: 'Change status?',
                text: "You are updating brand status!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, change it'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.post(`/admin/brands/status/${id}`, {
                        _token: '{{ csrf_token() }}'
                    }, function(res) {

                        Swal.fire({
                            icon: 'success',
                            title: res.message,
                            timer: 1200,
                            showConfirmButton: false
                        });

                        $('#brandsTable').DataTable().ajax.reload(null, false);
                    });

                }
            });
        }

        // BULK DELETE
        $('#bulkDeleteBtn').on('click', function() {

            if (selectedIds.length === 0) return;

            Swal.fire({
                title: 'Delete selected brands?',
                text: "This action cannot be undone!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ route('admin.brands.bulk-delete') }}",
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
                            $('#brandsTable').DataTable().ajax.reload();
                        }
                    });

                }
            });
        });
    </script>
@endpush
