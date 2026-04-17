@extends('backend.app')
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

        <h3>Edit Sub Category</h3>

        <form action="{{ route('admin.sub-categories.update', $data->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Category --}}
            <select name="category_id" class="form-control mb-2">
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $data->category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>

            {{-- Name --}}
            <input type="text" name="name" value="{{ $data->name }}" class="form-control mb-2">

            {{-- Image --}}
            <div class="mb-3">
                <label class="form-label">Sub Category Image</label>

                <input type="file" name="image" id="image" class="dropify"
                    data-allowed-file-extensions="jpg jpeg png gif" data-max-file-size="5M"
                    data-default-file="{{ $data->image ? asset($data->image) : '' }}">
            </div>
            <button class="btn btn-primary mt-3">Update</button>

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
