@extends('backend.app')

@section('title', 'Products List')

@section('content')
    <div class="container-fluid">

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3 gap-2">
            <h3 class="mb-0">Products</h3>

            <div>
                <button class="btn btn-danger btn-sm d-none" id="bulkDeleteBtn">
                    Delete Selected
                </button>

                <a href="{{ route('admin.products.create') }}" class="btn btn-success btn-sm">
                    Add Product
                </a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped align-middle" id="products-table" width="100%">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th><input type="checkbox" id="select_all"></th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Brand</th>
                        <th>Price</th>
                        <th>Stock</th>
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

            let table = $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                autoWidth: false,

                ajax: "{{ route('admin.products.index') }}",

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
                        data: 'category'
                    },
                    {
                        data: 'brand'
                    },
                    {
                        data: 'price'
                    },
                    {
                        data: 'stock',
                        orderable: false,
                        searchable: false
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

        /* ================= DELETE ================= */
        function deleteProduct(id) {

            Swal.fire({
                title: 'Are you sure?',
                text: "This product will be deleted!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: `/admin/products/${id}`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'DELETE'
                        },
                        success: function(res) {
                            Swal.fire('Deleted!', res.message, 'success');
                            $('#products-table').DataTable().ajax.reload();
                        }
                    });

                }
            });
        }

        /* ================= STATUS ================= */
        function changeStatus(id) {

            Swal.fire({
                title: 'Are you sure?',
                text: "Change product status!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, change'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.post(`/admin/products/status/${id}`, {
                        _token: '{{ csrf_token() }}'
                    }, function(res) {

                        Swal.fire({
                            title: 'Updated!',
                            text: res.message,
                            icon: 'success',
                            timer: 1200,
                            showConfirmButton: false
                        });

                        $('#products-table').DataTable().ajax.reload(null, false);
                    });

                }
            });
        }

        /* ================= BULK DELETE ================= */
        $('#bulkDeleteBtn').on('click', function() {

            if (selectedIds.length === 0) return;

            Swal.fire({
                title: 'Delete selected products?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete'
            }).then((result) => {

                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ route('admin.products.bulk-delete') }}",
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
                            $('#products-table').DataTable().ajax.reload();
                        }
                    });

                }
            });
        });
    </script>
@endpush
