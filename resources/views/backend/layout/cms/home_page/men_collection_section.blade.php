@extends('backend.app')

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
@endpush

@section('title', 'Home Page - Men Collection Section')

@section('content')

    <div class="app-content content">
        <div class="row">
            <div class="col-lg-7 mx-auto">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title mb-4">Men Collection Section</h4>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('admin.cms.home_page.men_collection.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')
                            {{-- Main Text --}}
                            <div class="mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ old('title', $data->title) }}">
                            </div>

                            {{-- Sub Text --}}
                            <div class="mb-3">
                                <label class="form-label">Sub Title</label>
                                <input type="text" name="sub_title" class="form-control"
                                    value="{{ old('sub_title', $data->sub_title) }}">
                            </div>

                            {{-- Button Text --}}
                            <div class="mb-3">
                                <label class="form-label">Button Text</label>
                                <input type="text" name="button_text" class="form-control"
                                    value="{{ old('button_text', $data->button_text) }}">
                            </div>

                            {{-- Button Link --}}
                            {{-- <div class="mb-3">
                                <label class="form-label">Button Link</label>
                                <input type="url" name="button_link" class="form-control"
                                    value="{{ old('button_link', $data->button_link) }}">
                            </div> --}}



                    </div>

                    {{-- Submit --}}
                    <div class="text-end">
                        <button type="submit" class="btn btn-success px-4">
                            Save Changes
                        </button>

                        <a href="{{ route('admin.cms.home_page.men_collection_section') }}"
                            class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>

    <script>
        $(document).ready(function() {

            // init only once
            $('.dropify').dropify();

            // ADD ITEM
            $(document).on('click', '#add-item', function() {

                let html = `
        <div class="border rounded p-3 mb-3 item">
            <h6 class="mb-3">New Product</h6>

            <div class="mb-2">
                <label>Brand Name</label>
                <input type="text" name="brand_name[]" class="form-control" placeholder="Brand Name">
            </div>

            <div class="mb-2">
                <label>Title</label>
                <input type="text" name="title[]" class="form-control" placeholder="Title">
            </div>

            <div class="mb-2">
                <label>Price</label>
                <input type="number" name="price[]" class="form-control" placeholder="Price">
            </div>

            <div class="mb-2">
                <label>Image</label>
                <input type="file"
                       name="image[]"
                       class="dropify new-dropify"
                       data-allowed-file-extensions="jpg jpeg png webp"
                       data-height="120">
            </div>

            <button type="button" class="btn btn-danger btn-sm remove-item">
                Remove
            </button>
        </div>
        `;

                let newItem = $(html);
                $('#items-wrapper').append(newItem);

                // ONLY init new dropify (not all)
                newItem.find('.new-dropify').dropify();
            });

            // REMOVE ITEM
            $(document).on('click', '.remove-item', function() {
                $(this).closest('.item').remove();
            });

        });
    </script>
@endpush
