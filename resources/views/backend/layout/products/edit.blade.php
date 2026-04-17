@extends('backend.app')

@section('title', 'Edit Product — ' . $product->name)

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0 fw-semibold">Edit Product</h4>
                <small class="text-muted">{{ $product->name }}</small>
            </div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Back to Products
            </a>
        </div>

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">

                {{--  LEFT COLUMN  --}}
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
                                <input type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $product->name) }}" placeholder="Product name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">SKU</label>
                                    <input type="text" name="sku" class="form-control"
                                        value="{{ old('sku', $product->sku) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Material</label>
                                    <input type="text" name="material" class="form-control"
                                        value="{{ old('material', $product->material) }}">
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label fw-medium">Short Description</label>
                                <textarea name="short_description" rows="2" class="form-control">{{ old('short_description', $product->short_description) }}</textarea>
                            </div>

                            <div class="mt-3">
                                <label class="form-label fw-medium">Full Description</label>
                                <textarea name="description" rows="5" class="form-control" id="description">{{ old('description', $product->description) }}</textarea>
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
                                            value="{{ old('price', $product->price) }}">
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
                                            value="{{ old('discount_price', $product->discount_price) }}">
                                    </div>
                                </div>

                                <div class="col-md-4" id="stockFieldWrapper">
                                    <label class="form-label fw-medium">Stock</label>
                                    <input type="number" name="stock" id="stockField" class="form-control"
                                        value="{{ old('stock', $product->stock) }}" min="0">
                                    <small class="text-muted">Auto-calculated when variants added</small>
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Weight (kg)</label>
                                    <input type="number" name="weight" step="0.01" class="form-control"
                                        value="{{ old('weight', $product->weight) }}">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-medium">Dimensions</label>
                                    <input type="text" name="dimensions" class="form-control"
                                        value="{{ old('dimensions', $product->dimensions) }}"
                                        placeholder="e.g. 30x20x10 cm">
                                </div>
                            </div>

                        </div>
                    </div>

                    {{--  VARIANTS SECTION  --}}
                    <div class="card border-0 shadow-sm mb-4">
                        <div
                            class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0 fw-semibold">
                                    <i class="fas fa-layer-group me-2 text-warning"></i>
                                    Product Variants
                                    <span class="badge bg-warning text-dark ms-2" id="variantCount">
                                        {{ $product->variants->count() }}
                                    </span>
                                </h6>
                                <small class="text-muted">Optional — Add size/color combinations with individual
                                    stock</small>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm" id="addVariantBtn">
                                <i class="fas fa-plus me-1"></i> Add Variant
                            </button>
                        </div>
                        <div class="card-body p-0">

                            <div id="variantEmptyState"
                                class="text-center py-5 {{ $product->variants->count() > 0 ? 'd-none' : '' }}">
                                <i class="fas fa-layer-group fa-2x text-muted opacity-50 mb-3"></i>
                                <p class="text-muted mb-1">No variants added yet</p>
                                <small class="text-muted">Click "Add Variant" to add size/color combinations</small>
                            </div>

                            <div id="variantTableWrapper"
                                class="{{ $product->variants->count() === 0 ? 'd-none' : '' }}">
                                <table class="table table-hover mb-0">
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
                                            <td><span id="totalVariantStock" class="fw-bold text-success">0</span></td>
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

                            {{-- Existing gallery --}}
                            @if ($product->gallery)
                                @php $galleryImages = json_decode($product->gallery, true) ?? []; @endphp
                                @if (count($galleryImages) > 0)
                                    <p class="text-muted small mb-2">Current gallery:</p>
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        @foreach ($galleryImages as $img)
                                            <img src="{{ asset($img) }}"
                                                style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #dee2e6;">
                                        @endforeach
                                    </div>
                                @endif
                            @endif

                            <input type="file" name="gallery[]" class="form-control" multiple accept="image/*"
                                id="galleryInput">
                            <small class="text-muted">Upload new images to replace the existing gallery.</small>
                            <div id="galleryPreview" class="d-flex flex-wrap gap-2 mt-3"></div>
                        </div>
                    </div>

                </div>{{-- end left col --}}

                {{--  RIGHT COLUMN  --}}
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
                                @if ($product->thumbnail)
                                    <img id="thumbPreview" src="{{ asset($product->thumbnail) }}"
                                        class="img-fluid rounded" style="max-height:200px;">
                                @else
                                    <div id="thumbPlaceholder">
                                        <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                                        <p class="text-muted small mb-0">Click to upload thumbnail</p>
                                    </div>
                                    <img id="thumbPreview" src="" alt=""
                                        class="img-fluid rounded d-none" style="max-height:200px;">
                                @endif
                            </div>
                            <input type="file" name="thumbnail" id="thumbnailInput" class="d-none" accept="image/*">
                            <button type="button" class="btn btn-outline-primary btn-sm w-100"
                                onclick="document.getElementById('thumbnailInput').click()">
                                <i class="fas fa-upload me-1"></i>
                                {{ $product->thumbnail ? 'Change Image' : 'Choose Image' }}
                            </button>
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
                                            {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
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
                                    <option value="">-- Loading... --</option>
                                    @foreach ($subCategories as $sub)
                                        <option value="{{ $sub->id }}"
                                            {{ old('sub_category_id', $product->sub_category_id) == $sub->id ? 'selected' : '' }}>
                                            {{ $sub->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-medium">Brand</label>
                                <select name="brand_id" class="form-select">
                                    <option value="">-- Select Brand --</option>
                                    @foreach ($brands as $brand)
                                        <option value="{{ $brand->id }}"
                                            {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                            {{ $brand->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="form-label fw-medium">Tags</label>
                                <input type="text" name="tags" class="form-control"
                                    value="{{ old('tags', $product->tags ? implode(', ', json_decode($product->tags, true)) : '') }}"
                                    placeholder="luxury, bag, leather">
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
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured"
                                    value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                <label class="form-check-label fw-medium" for="isFeatured">
                                    Featured Product
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i> Update Product
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

@push('scripts')
    <script>
        const existingVariants = @json($product->variants);

        //  VARIANT

        let variantIndex = 0;

        document.addEventListener('DOMContentLoaded', function() {
            existingVariants.forEach(function(v) {
                addVariantRow({
                    size: v.size,
                    color: v.color,
                    color_hex: v.color_hex ?? '#000000',
                    price: v.price,
                    stock: v.stock,
                });
            });
        });

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
                class="form-control form-control-sm"
                value="${data.color ?? ''}"
                placeholder="Black, Brown…">
        </td>
        <td>
            <div class="d-flex align-items-center gap-2">
                <input type="color"
                    name="variants[${i}][color_hex]"
                    class="form-control form-control-color form-control-sm variant-color-picker"
                    value="${data.color_hex ?? '#000000'}"
                    style="width:36px;height:32px;padding:2px;cursor:pointer;">
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
                    placeholder="Empty = base price"
                    step="0.01" min="0">
            </div>
        </td>
        <td>
            <input type="number"
                name="variants[${i}][stock]"
                class="form-control form-control-sm variant-stock"
                value="${data.stock ?? 0}"
                min="0" required>
        </td>
        <td class="text-center">
            <button type="button" class="btn btn-outline-danger btn-sm"
                onclick="removeVariantRow(this)">
                <i class="fas fa-times"></i>
            </button>
        </td>
    `;

            document.getElementById('variantBody').appendChild(row);
            updateVariantUI();

            const picker = row.querySelector('.variant-color-picker');
            const label = row.querySelector('.variant-hex-label');
            picker.addEventListener('input', function() {
                label.textContent = this.value;
            });

            row.querySelector('.variant-stock').addEventListener('input', updateTotalStock);
        }

        function removeVariantRow(btn) {
            btn.closest('tr').remove();
            updateVariantUI();
            updateTotalStock();
        }

        function updateVariantUI() {
            const count = document.querySelectorAll('#variantBody tr').length;
            const isEmpty = count === 0;

            document.getElementById('variantEmptyState').classList.toggle('d-none', !isEmpty);
            document.getElementById('variantTableWrapper').classList.toggle('d-none', isEmpty);
            document.getElementById('variantCount').textContent = count;

            const stockField = document.getElementById('stockField');
            const stockWrapper = document.getElementById('stockFieldWrapper');

            if (!isEmpty) {
                stockField.disabled = true;
                stockWrapper.style.opacity = '0.5';
            } else {
                stockField.disabled = false;
                stockWrapper.style.opacity = '1';
            }

            updateTotalStock();
        }

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
                const preview = document.getElementById('thumbPreview');
                const placeholder = document.getElementById('thumbPlaceholder');
                preview.src = e.target.result;
                preview.classList.remove('d-none');
                if (placeholder) placeholder.classList.add('d-none');
            };
            reader.readAsDataURL(file);
        });

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
                    wrapper.innerHTML =
                        `<img src="${e.target.result}" style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #dee2e6;">`;
                    preview.appendChild(wrapper);
                };
                reader.readAsDataURL(file);
            });
        });

        //  SUB-CATEGORY

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
                });
        });
    </script>
@endpush
