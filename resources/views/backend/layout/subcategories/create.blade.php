@extends('backend.app')

@push('styles')
    <style>
        .dropify-wrapper .dropify-preview .dropify-render img,
        .dropify-wrapper .dropify-preview .dropify-render video {
            width: 100%;
            height: auto;
            max-height: 220px;
            object-fit: contain;
        }
    </style>
@endpush

@section('content')
    <div class="container">

        <h3 class="mb-3">Create Sub Category</h3>

        <form action="{{ route('admin.sub-categories.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Category --}}
            <div class="mb-3">
                <label class="form-label">Category Name</label>
                <select name="category_id" class="form-control">
                    <option value="">-- Select Category --</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Name --}}
            <div class="mb-3">
                <label class="form-label">Sub Category Name</label>
                <input type="text" name="name" value="{{ old('name') }}" class="form-control"
                    placeholder="Sub Category Name">

                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- Image --}}
            <div class="mb-3">
                <label class="form-label">Sub Category Image</label>

                <input type="file" name="image" class="dropify" accept="image/*"
                    data-allowed-file-extensions="jpg jpeg png gif" data-max-file-size="5M">

                @error('image')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

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
                    error: 'Invalid file'
                }
            });
        });
    </script>
@endpush
