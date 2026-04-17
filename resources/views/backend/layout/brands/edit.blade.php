@extends('backend.app')

@section('title', 'Edit Brand')
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

        <form action="{{ route('admin.brands.update', $brand->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <label class="form-label">Brand Name</label>
            <input type="text" name="name" class="form-control mb-2" placeholder="Name" value="{{ $brand->name }}">

            <div class="mb-3">
                <label class="form-label">Brand logo</label>

                <input type="file" name="logo" id="logo" class="dropify"
                    data-allowed-file-extensions="jpg jpeg png gif" data-max-file-size="5M"
                    data-default-file="{{ $brand->logo ? asset($brand->logo) : '' }}">
            </div>

            <input type="file" name="image" class="form-control mb-2">
            <div class="mb-3">
                <label class="form-label">Brand Banner</label>

                <input type="file" name="banner" id="banner" class="dropify"
                    data-allowed-file-extensions="jpg jpeg png gif" data-max-file-size="5M"
                    data-default-file="{{ $brand->banner ? asset($brand->banner) : '' }}">
            </div>
            <img src="{{ asset($brand->logo) }}" width="80">
            <input type="file" name="logo" onchange="previewLogo(event)">
            <br>
            {{-- Description --}}
            <label for="description" class="col-3 col-form-label"><i>Description</i></label>
            <div class="row mb-3">
                <div class="col-9">
                    <textarea name="description" id="description"
                        class="summernote form-control @error('description') is-invalid @enderror">{!! old('description', $brand?->description) !!}</textarea>
                    @error('description')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <label class="form-label">Country</label>
            <input type="text" name="country" class="form-control mb-2" value="{{ $brand->country }}">
            <label class="form-label">Website</label>
            <input type="text" name="website" class="form-control mb-2" value="{{ $brand->website }}">

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
