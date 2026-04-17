@extends('backend.app')

@section('title', 'Create Product')

@section('content')

    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0 fw-semibold">Create Product</h4>
                <small class="text-muted">Add a new product to your store</small>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Products
            </a>
        </div>

        {{-- Alert --}}
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf

            <div class="row g-4">


                {{-- LEFT COLUMN  --}}

                <div class="col-lg-8">

                    {{-- Basic Info --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-box me-2 text-primary"></i>Basic Information
                            </h6>
                        </div>
                        <div class="card-body p-4">

                            <div class="mb-3">
                                <label class="form-label fw-medium">Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="productName"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="e.g. Louis Vuitton Speedy Bag">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">SKU</label>
                                    <input type="text" name="sku"
                                        class="form-control @error('sku') is-invalid @enderror" value="{{ old('sku') }}"
                                        placeholder="Auto-generated if empty">
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Material</label>
                                    <input type="text" name="material" class="form-control" value="{{ old('material') }}"
                                        placeholder="e.g. Leather, Cotton">
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label fw-medium">Short Description</label>
                                <textarea name="short_description" rows="2" class="form-control"
                                    placeholder="Brief product summary (shown in listing cards)">{{ old('short_description') }}</textarea>
                            </div>

                            <div class="mt-3">
                                <label class="form-label fw-medium">Full Description</label>
                                <textarea name="description" rows="5" class="form-control" id="description"
                                    placeholder="Detailed product description">{{ old('description') }}</textarea>
                            </div>

                        </div>
                    </div>

                    {{-- Pricing & Stock --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-tag me-2 text-success"></i>Pricing & Stock
                            </h6>
                        </div>
                        <div class="card-body p-4">

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Price <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">৳</span>
                                        <input type="number" name="price" step="0.01"
                                            class="form-control @error('price') is-invalid @enderror"
                                            value="{{ old('price') }}" placeholder="0.00">
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-medium">Discount Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">৳</span>
                                        <input type="number" name="discount_price" step="0.01" class="form-control"
                                            value="{{ old('discount_price') }}" placeholder="0.00">
                                    </div>
                                </div>

                                {{-- Stock — variant  --}}
                                <div class="col-md-4" id="stockFieldWrapper">
                                    <label class="form-label fw-medium">Stock <span class="text-danger">*</span></label>
                                    <input type="number" name="stock" id="stockField"
                                        class="form-control @error('stock') is-invalid @enderror"
                                        value="{{ old('stock', 0) }}" min="0">
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Auto-calculated when variants added</small>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Weight (kg)</label>
                                    <input type="number" name="weight" step="0.01" class="form-control"
                                        value="{{ old('weight') }}" placeholder="e.g. 0.5">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Dimensions</label>
                                    <input type="text" name="dimensions" class="form-control"
                                        value="{{ old('dimensions') }}" placeholder="e.g. 30x20x10 cm">
                                </div>
                            </div>

                        </div>
                    </div>


                    {{-- VARIANTS SECTION  --}}

                    <div class="card border-0 shadow-sm mb-4" id="variantsCard">
                        <div
                            class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fw-semibold">
                                    <i class="fas fa-layer-group me-2 text-warning"></i>
                                    Product Variants
                                    <span class="badge bg-warning text-dark ms-2 fs-xs" id="variantCount">0</span>
                                </h6>
                                <small class="text-muted">Optional — Add size/color combinations with individual
                                    stock</small>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="addVariantBtn">
                                <i class="fas fa-plus me-1"></i> Add Variant
                            </button>
                        </div>
                        <div class="card-body p-0">

                            {{-- Empty state --}}
                            <div id="variantEmptyState" class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-layer-group fa-2x text-muted opacity-50"></i>
                                </div>
                                <p class="text-muted mb-1">No variants added yet</p>
                                <small class="text-muted">Click "Add Variant" to add size/color combinations</small>
                            </div>

                            {{-- Variant Table --}}
                            <div id="variantTableWrapper" class="d-none">
                                <table class="table table-hover mb-0" id="variantTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="ps-4" style="width:20%">Size</th>
                                            <th style="width:20%">Color Name</th>
                                            <th style="width:15%">Color</th>
                                            <th style="width:18%">Price Override</th>
                                            <th style="width:15%">Stock <span class="text-danger">*</span></th>
                                            <th style="width:12%" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="variantBody">

                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="4" class="text-end fw-medium ps-4">Total Stock:</td>
                                            <td>
                                                <span id="totalVariantStock" class="fw-bold text-success">0</span>
                                            </td>
                                            <td></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>

                        </div>
                    </div>

                    {{-- Gallery --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-images me-2 text-info"></i>Product Gallery
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <input type="file" name="gallery[]" class="form-control" multiple accept="image/*"
                                id="galleryInput">
                            <small class="text-muted">Upload multiple images (JPG, PNG, WebP). First image is shown as
                                primary gallery image.</small>
                            <div id="galleryPreview" class="d-flex flex-wrap gap-2 mt-3"></div>
                        </div>
                    </div>

                </div>

                {{-- RIGHT COLUMN  --}}
                <div class="col-lg-4">

                    {{-- Thumbnail --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-image me-2 text-primary"></i>Thumbnail
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="border border-2 border-dashed rounded-3 p-3 text-center mb-3" id="thumbDropzone"
                                style="cursor:pointer; min-height:160px; display:flex; align-items:center; justify-content:center;">
                                <div id="thumbPlaceholder">
                                    <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                    <p class="text-muted small mb-0">Click to upload thumbnail</p>
                                </div>
                                <img id="thumbPreview" src="" alt="" class="img-fluid rounded d-none"
                                    style="max-height:200px;">
                            </div>
                            <input type="file" name="thumbnail" id="thumbnailInput" class="d-none" accept="image/*">
                            <button type="button" class="btn btn-outline-primary btn-sm w-100"
                                onclick="document.getElementById('thumbnailInput').click()">
                                <i class="fas fa-upload me-1"></i> Choose Image
                            </button>
                            @error('thumbnail')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Organization --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-sitemap me-2 text-secondary"></i>Organization
                            </h6>
                        </div>
                        <div class="card-body p-4">

                            <div class="mb-3">
                                <label class="form-label fw-medium">Category <span class="text-danger">*</span></label>
                                <select name="category_id" id="categorySelect"
                                    class="form-select @error('category_id') is-invalid @enderror">
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}"
                                            {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Sub Category</label>
                                <select name="sub_category_id" id="subCategorySelect" class="form-select">
                                    <option value="">-- Select Category First --</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Brand</label>
                                <select name="brand_id" class="form-select @error('brand_id') is-invalid @enderror">
                                    <option value="">-- Select Brand --</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('brand_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div>
                                <label class="form-label fw-medium">Tags</label>
                                <input type="text" name="tags" class="form-control" value="{{ old('tags') }}"
                                    placeholder="luxury, bag, leather (comma separated)">
                                <small class="text-muted">Separate tags with commas</small>
                            </div>

                        </div>
                    </div>

                    {{-- Settings --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-white border-bottom py-3">
                            <h6 class="mb-0 fw-semibold">
                                <i class="fas fa-cog me-2 text-secondary"></i>Settings
                            </h6>
                        </div>
                        <div class="card-body p-4">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured"
                                    value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium" for="isFeatured">
                                    Featured Product
                                </label>
                                <div><small class="text-muted">Show on homepage featured section</small></div>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i> Save Product
                        </button>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                            Cancel
                        </a>
                    </div>

                </div>

            </div>
        </form>

    </div>

@endsection

{{-- Scripts --}}
@push('scripts')
    <script>
        //  VARIANT TODO SYSTEM

        let variantIndex = 0;

        //  Add new variant row
        document.getElementById('addVariantBtn').addEventListener('click', function() {
            addVariantRow();
        });

        function addVariantRow(data = {}) {
            const i = variantIndex++;

            const row = document.createElement('tr');
            row.setAttribute('data-variant-index', i);
            row.innerHTML = `
        <td class="ps-4">
            <input type="text"
                name="variants[${i}][size]"
                class="form-control form-control-sm"
                value="${data.size ?? ''}"
                placeholder="S, M, L, XL, 38…">
        </td>
        <td>
            <input type="text"
                name="variants[${i}][color]"
                class="form-control form-control-sm variant-color-name"
                value="${data.color ?? ''}"
                placeholder="Black, Brown…">
        </td>
        <td>
            <div class="d-flex align-items-center gap-2">
                <input type="color"
                    name="variants[${i}][color_hex]"
                    class="form-control form-control-color form-control-sm variant-color-picker"
                    value="${data.color_hex ?? '#000000'}"
                    style="width:36px; height:32px; padding:2px; cursor:pointer;">
                <small class="text-muted variant-hex-label">${data.color_hex ?? '#000000'}</small>
            </div>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <span class="input-group-text">৳</span>
                <input type="number"
                    name="variants[${i}][price]"
                    class="form-control form-control-sm"
                    value="${data.price ?? ''}"
                    placeholder="Leave empty to use base price"
                    step="0.01" min="0">
            </div>
        </td>
        <td>
            <input type="number"
                name="variants[${i}][stock]"
                class="form-control form-control-sm variant-stock"
                value="${data.stock ?? 0}"
                min="0"
                required>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm"
                onclick="removeVariantRow(this)"
                title="Remove this variant">
                <i class="fas fa-times"></i>
            </button>
        </td>
    `;

            document.getElementById('variantBody').appendChild(row);
            updateVariantUI();

            // Color picker
            const picker = row.querySelector('.variant-color-picker');
            const label = row.querySelector('.variant-hex-label');
            picker.addEventListener('input', function() {
                label.textContent = this.value;
            });

            // Stock change
            row.querySelector('.variant-stock').addEventListener('input', updateTotalStock);
        }

        //  Remove variant row
        function removeVariantRow(btn) {
            btn.closest('tr').remove();
            updateVariantUI();
            updateTotalStock();
        }

        //  Show/hide table
        function updateVariantUI() {
            const rows = document.querySelectorAll('#variantBody tr');
            const count = rows.length;
            const isEmpty = count === 0;

            document.getElementById('variantEmptyState').classList.toggle('d-none', !isEmpty);
            document.getElementById('variantTableWrapper').classList.toggle('d-none', isEmpty);
            document.getElementById('variantCount').textContent = count;

            // Stock field — variant
            const stockField = document.getElementById('stockField');
            const stockWrapper = document.getElementById('stockFieldWrapper');
            if (!isEmpty) {
                stockField.value = '';
                stockField.disabled = true;
                stockWrapper.style.opacity = '0.5';
            } else {
                stockField.disabled = false;
                stockWrapper.style.opacity = '1';
            }

            updateTotalStock();
        }

        //  Recalculate total stock
        function updateTotalStock() {
            let total = 0;
            document.querySelectorAll('.variant-stock').forEach(function(input) {
                total += parseInt(input.value || 0);
            });
            document.getElementById('totalVariantStock').textContent = total;
        }

        //  THUMBNAIL PREVIEW
        document.getElementById('thumbnailInput').addEventListener('change', function() {
            const file = this.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('thumbPreview').src = e.target.result;
                document.getElementById('thumbPreview').classList.remove('d-none');
                document.getElementById('thumbPlaceholder').classList.add('d-none');
            };
            reader.readAsDataURL(file);
        });

        // Click on dropzone → open file input
        document.getElementById('thumbDropzone').addEventListener('click', function() {
            document.getElementById('thumbnailInput').click();
        });

        //  GALLERY PREVIEW
        document.getElementById('galleryInput').addEventListener('change', function() {
            const preview = document.getElementById('galleryPreview');
            preview.innerHTML = '';
            Array.from(this.files).forEach(function(file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const wrapper = document.createElement('div');
                    wrapper.style.cssText = 'position:relative;';
                    wrapper.innerHTML = `
                <img src="${e.target.result}"
                    style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #dee2e6;">
            `;
                    preview.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            });
        });

        //  SUB-CATEGORY AJAX LOAD

        document.getElementById('categorySelect').addEventListener('change', function() {
            const categoryId = this.value;
            const subSelect = document.getElementById('subCategorySelect');

            subSelect.innerHTML = '<option value="">Loading...</option>';

            if (!categoryId) {
                subSelect.innerHTML = '<option value="">-- Select Category First --</option>';
                return;
            }

            fetch(`{{ route('admin.products.sub-categories') }}?category_id=${categoryId}`)
                .then(res => res.json())
                .then(data => {
                    subSelect.innerHTML = '<option value="">-- Select Sub Category --</option>';
                    data.forEach(function(sub) {
                        subSelect.innerHTML += `<option value="${sub.id}">${sub.name}</option>`;
                    });
                })
                .catch(function() {
                    subSelect.innerHTML = '<option value="">Error loading sub-categories</option>';
                });
        });
    </script>
@endpush
