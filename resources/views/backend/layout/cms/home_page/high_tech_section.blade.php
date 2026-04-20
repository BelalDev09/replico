@extends('backend.app')

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
@endpush

@section('title', 'Home Page - High Tech Section')

@section('content')

    <div class="app-content content">
        <div class="row">
            <div class="col-lg-7 mx-auto">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title mb-4"> High Tech Section</h4>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{ route('admin.cms.home_page.high_tech.update') }}" method="POST"
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

                        <a href="{{ route('admin.cms.home_page.high_tech_section') }}" class="btn btn-outline-secondary">
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
