@extends('backend.app')

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
@endpush

@section('title', 'Home Page - Category Section')

@section('content')

    <div class="app-content content">
        <div class="row">
            <div class="col-lg-7 mx-auto">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title mb-4">Category Section</h4>

                        <form method="POST" action="{{ route('admin.cms.home_page.category_section.update') }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PATCH')

                            {{-- Main text --}}
                            <div class="mb-3">
                                <label class="form-label">Main Text</label>
                                <input type="text" name="main_text" class="form-control"
                                    value="{{ old('main_text', $data?->main_text) }}">
                            </div>

                            {{-- 3 BOXES --}}
                            @for ($i = 0; $i < 3; $i++)
                                @php
                                    $v = match ($i) {
                                        0 => $data->v1 ?? [],
                                        1 => $data->v2 ?? [],
                                        2 => $data->v3 ?? [],
                                    };
                                @endphp

                                <div class="border rounded p-3 mb-3">

                                    <h6 class="mb-3">Box {{ $i + 1 }}</h6>

                                    {{-- Title --}}
                                    <div class="mb-2">
                                        <input type="text" name="title[{{ $i }}]" class="form-control"
                                            placeholder="Box title..." value="{{ old('title.' . $i, $v['title'] ?? '') }}">
                                    </div>

                                    {{-- Sub Title --}}
                                    <div class="mb-2">
                                        <input type="text" name="sub_title[{{ $i }}]" class="form-control"
                                            placeholder="Sub title..."
                                            value="{{ old('sub_title.' . $i, $v['sub_title'] ?? '') }}">
                                    </div>

                                    {{-- Image --}}
                                    <div>
                                        <input type="file" name="image[{{ $i }}]" class="dropify"
                                            data-allowed-file-extensions="jpg jpeg png"
                                            data-default-file="{{ !empty($v['image']) ? asset($v['image']) : '' }}">
                                    </div>
                                    {{-- button text --}}
                                    <div class="mb-2">
                                        <input type="text" name="button_text[{{ $i }}]" class="form-control"
                                            placeholder="Button text..."
                                            value="{{ old('button_text.' . $i, $v['button_text'] ?? '') }}">
                                    </div>
                                    {{-- button link --}}
                                    <div class="mb-2">
                                        <input type="url" name="button_link[{{ $i }}]" class="form-control"
                                            placeholder="Button link..."
                                            value="{{ old('button_link.' . $i, $v['button_link'] ?? '') }}">
                                    </div>

                                </div>
                            @endfor

                            {{-- Buttons --}}
                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-success px-4">
                                    Save Changes
                                </button>

                                <a href="{{ route('admin.cms.home_page.category_section') }}"
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

@push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/js/dropify.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.dropify').dropify();
        });
    </script>
@endpush
