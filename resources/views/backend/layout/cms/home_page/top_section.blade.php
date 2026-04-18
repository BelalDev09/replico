@extends('backend.app')

@push('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Dropify/0.2.2/css/dropify.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
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

@section('title', 'Home Page - Top Section')

@section('content')
    <div class="app-content content">
        <div class="row">
            <div class="col-lg-7 mx-auto">
                <div class="card">
                    <div class="card-body">

                        <h4 class="card-title mb-4">Top Section</h4>

                        <form method="POST" action="{{ route('admin.cms.home_page.top_section.update') }}"
                            enctype="multipart/form-data" class="form">
                            @csrf
                            @method('PATCH')

                            <!-- Title -->
                            <div class="row mb-3">
                                <label for="title" class="col-3 col-form-label">Title</label>
                                <div class="col-9">
                                    <input type="text" name="title" id="title" class="form-control"
                                        value="{{ old('title', $data?->title ?? '') }}" placeholder="Main heading text...">
                                    @error('title')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Sub Title -->
                            <div class="row mb-3">
                                <label for="sub_title" class="col-3 col-form-label"><i>Sub Title</i></label>
                                <div class="col-9">
                                    <input type="text" name="sub_title" id="sub_title" class="form-control"
                                        value="{{ old('sub_title', $data?->sub_title ?? '') }}"
                                        placeholder="Sub Title text...">
                                    @error('sub_title')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Image Upload Row -->
                            <div class="row mb-3" id="image-row">
                                <label class="col-3 col-form-label">Image</label>
                                <div class="col-9">
                                    <input type="file" name="image" id="image" class="dropify" accept="image/*"
                                        data-allowed-file-extensions="jpg jpeg png gif svg webp" data-max-file-size="5M"
                                        data-default-file="{{ $data?->image ? asset($data->image) : '' }}">
                                    @error('image')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <!-- Button Text -->
                            <div class="row mb-3">
                                <label for="button_text" class="col-3 col-form-label"><i>Button Text</i></label>
                                <div class="col-9">
                                    <input type="text" name="button_text" id="button_text" class="form-control"
                                        value="{{ old('button_text', $data?->button_text) }}"
                                        placeholder="Enter button text..." required>
                                    @error('button_text')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <!-- Link URL -->
                            {{-- <div class="row mb-3">
                                <label for="link_url" class="col-3 col-form-label"><i>Link URL</i></label>
                                <div class="col-9">
                                    <input type="url" name="link_url" id="link_url" class="form-control" accept="url"
                                        value="{{ old('link_url', $data?->link_url) }}" placeholder="Enter link url..."
                                        required>
                                    @error('link_url')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div> --}}

                            <!-- Save Button & Cancel Button -->
                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="fas fa-save me-1"></i> Save Changes
                                </button>
                                <a href="{{ route('admin.cms.home_page.top_section') }}"
                                    class="btn btn-outline-secondary ms-2">
                                    <i class="fas fa-times me-1"></i> Cancel
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
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize Summernote
            $('.summernote').summernote({
                height: 250,
                placeholder: 'Enter text here...',
            });

            // Initialize Dropify
            $('.dropify').dropify({
                messages: {
                    'default': 'Drag & drop file here or click to browse',
                    'replace': 'Drag & drop or click to replace',
                    'remove': 'Remove',
                    'error': 'Ooops, something wrong happened.'
                }
            });

            // Toggle image/video fields + disable inactive one
            function toggleMediaFields(type) {
                if (type === 'video') {
                    $('#video-row').show();
                    $('#image-row').hide();
                    $('#video').prop('disabled', false).closest('.dropify-wrapper').css('opacity', 1);
                    $('#image').prop('disabled', true).closest('.dropify-wrapper').css('opacity', 0.5);
                } else {
                    $('#image-row').show();
                    $('#video-row').hide();
                    $('#image').prop('disabled', false).closest('.dropify-wrapper').css('opacity', 1);
                    $('#video').prop('disabled', true).closest('.dropify-wrapper').css('opacity', 0.5);
                }
            }

            // Initial setup
            const initialType = $('#type').val() || 'image';
            toggleMediaFields(initialType);

            // On type change
            $('#type').on('change', function() {
                toggleMediaFields($(this).val());
            });

        });
    </script>
@endpush
