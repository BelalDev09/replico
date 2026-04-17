@extends('backend.app')

@section('title', 'Create Brand')
@push('styles')
    <style>
        .dropify-wrapper .dropify-preview .dropify-render video {
            width: 100%;
            height: auto;
            max-height: 220px;
            object-fit: contain;
        }

        .dropify-wrapper .dropify-preview .dropify-render img {
            width: 100%;
            height: auto;
            max-height: 220px;
            object-fit: contain;
        }
    </style>
@endpush
@section('content')

    <div class="container">

        <form action="{{ route('admin.brands.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- Brand Name --}}
            <label class="form-label">Brand Name</label>
            <input type="text" name="name" class="form-control mb-2" placeholder="Name">
            {{-- Brand Logo --}}
            <div class="mb-3">
                <label class="form-label">Brand logo</label>

                <input type="file" name="logo" id="logo" class="dropify"
                    data-allowed-file-extensions="jpg jpeg png gif" data-max-file-size="5M">
            </div>

            <input type="file" name="image" class="form-control mb-2">
            {{-- Brand Banner --}}
            <div class="mb-3">
                <label class="form-label">Brand Banner</label>

                <input type="file" name="banner" id="banner" class="dropify"
                    data-allowed-file-extensions="jpg jpeg png gif" data-max-file-size="5M">
            </div>
            {{-- Description --}}
            <label for="description" class="col-3 col-form-label"><i>Description</i></label>
            <div class="row mb-3">
                <div class="col-9">
                    <textarea name="description" id="description"
                        class="summernote form-control @error('description') is-invalid @enderror"></textarea>
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <label class="form-label">Country</label>
            <input type="text" name="country" class="form-control mb-2" placeholder="enter your country">
            <label class="form-label">Website</label>
            <input type="text" name="website" class="form-control mb-2" placeholder="http://example.com">

            <button class="btn btn-primary">Save</button>

        </form>

    </div>

@endsection
@push('scripts')
    <script>
        $(document).ready(function() {

            $('.dropify').dropify({
                messages: {
                    default: 'Drag and drop or click',
                    replace: 'Drag and drop or click to replace',
                    remove: 'Remove',
                    error: 'Ooops, something wrong happened.'
                }
            });

        });
    </script>
@endpush
