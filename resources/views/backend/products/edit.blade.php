<x-backend-layout title="Tags Management">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Edit Product</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Product</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>
    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Left Column: Basic Info + SEO + Discounts -->
            <div class="col-md-8">

                <!-- Basic Information -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Basic Information</div>
                    </div>
                    <div class="card-body">
                        <!-- Product Name -->
                        <div class="mb-2">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name ?? '') }}" required>
                            @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-2">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control summernote" rows="4">
                                {{-- {!! old('description', $product->description ?? '') !!} --}}
                                {{ old('description', $product->description ?? '')}}
                            </textarea>
                            @error('description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>


                <!-- Others Information -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            Others Information
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-2">
                                <nav class="nav nav-tabs flex-column nav-style-4" role="tablist">
                                    <a class="nav-link active" data-bs-toggle="tab" role="tab" aria-current="page" href="#home-vertical-link" aria-selected="false">
                                        <i class="ri-home-smile-line me-2 align-middle d-inline-block"></i> Inventory
                                    </a>
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#services-vertical-link" aria-selected="true">
                                        <i class="ri-coupon-line me-2 align-middle d-inline-block"></i> Discounts
                                    </a>
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#about-vertical-link" aria-selected="false">
                                        <i class="ri-ship-line me-2 align-middle d-inline-block"></i> Shipping
                                    </a>
                                    <a class="nav-link" data-bs-toggle="tab" role="tab" aria-current="page" href="#contacts-vertical-link" aria-selected="false">
                                        <i class="ri-search-eye-line me-2 align-middle d-inline-block"></i> SEO Info.
                                    </a>
                                </nav>
                            </div>
                            <div class="col-xl-10">
                                <div class="tab-content">
                                    <div class="tab-pane show active text-muted" id="home-vertical-link" role="tabpanel">
                                        <!--Inventory-->
                                        <div class="row">
                                            <div class="col-md-6 mb-1">
                                                <label class="form-label">SKU Prefix <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" id="sku_prefix" name="sku_prefix"
                                                    value="{{ old('sku_prefix', $product->sku_prefix ?? 'SKU') }}" required>
                                                @error('sku_prefix') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="col-md-6 mb-1">
                                                <label class="form-label">Base Price <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" id="sale_price" name="sale_price"
                                                    value="{{ old('sale_price', $product->sale_price ?? '0.00') }}" min="0" step="0.01">
                                                @error('sale_price') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="col-md-6 mb-1">
                                                <label for="purchase_price" class="form-label">Purchase Price</label>
                                                <input type="number" class="form-control form-control-sm" id="purchase_price" name="purchase_price"
                                                    value="{{ old('purchase_price', $product->purchase_price ?? '0.00') }}">
                                                @error('purchase_price') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="col-md-6 mb-1">
                                                <label for="stock_status" class="form-label">Stock Management <span class="text-danger">*</span></label>
                                                <select name="stock_status" id="stock_status" class="form-select">
                                                    <option value="quantity" {{ old('stock_status', $product->stock_status ?? '') == 'quantity' ? 'selected' : '' }}>Quantity</option>
                                                    <option value="in_stock" {{ old('stock_status', $product->stock_status ?? '') == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                                    <option value="out_of_stock" {{ old('stock_status', $product->stock_status ?? '') == 'out_of_stock' ? 'selected' : '' }}>Out Of Stock</option>
                                                    <option value="upcomming" {{ old('stock_status', $product->stock_status ?? '') == 'upcomming' ? 'selected' : '' }}>Upcomming</option>
                                                </select>
                                                @error('stock_status') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="col-md-6 mb-1">
                                                <label for="total_stock" class="form-label">Total Stock</label>
                                                <input type="number" class="form-control form-control-sm" id="total_stock" name="total_stock"
                                                    value="{{ old('total_stock', $product->total_stock ?? 0) }}">
                                                @error('total_stock') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="col-md-6 mb-1">
                                                <label for="stock_out" class="form-label">Stock Out <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" id="stock_out" name="stock_out"
                                                    value="{{ old('stock_out', $product->stock_out ?? 1) }}">
                                                @error('stock_out') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="col-md-6 mb-1">
                                                <label for="alert_quantity" class="form-label">Alert Quantity</label>
                                                <input type="number" class="form-control form-control-sm" id="alert_quantity" name="alert_quantity"
                                                    value="{{ old('alert_quantity', $product->alert_quantity ?? 0) }}">
                                                @error('alert_quantity') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>

                                            <div class="col-md-6 mb-1">
                                                <label for="expire" class="form-label">Expire</label>
                                                <select name="expire" id="expire" class="form-select">
                                                    <option value="">Select</option>
                                                    @php
                                                        $expireOptions = ['7 Days','15 Days','1 Month','2 Month','3 Month','6 Month','1 Year','2 Year','3 Year','5 Year','10 Year','Life Time'];
                                                        $selectedExpire = old('expire', $product->expire ?? '');
                                                    @endphp
                                                    @foreach($expireOptions as $option)
                                                        <option value="{{ $option }}" {{ $selectedExpire == $option ? 'selected' : '' }}>{{ $option }}</option>
                                                    @endforeach
                                                </select>
                                                @error('expire') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tab-pane text-muted" id="services-vertical-link" role="tabpanel">
                                        <!-- Discount -->
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label for="discount_type" class="form-label">Discount Type</label>
                                                <select name="discount_type" id="discount_type" class="form-select">
                                                    <option value="">Select Type</option>
                                                    @foreach(['percentage', 'flat'] as $type)
                                                        <option value="{{ $type }}" {{ old('discount_type', optional($product->discount)->discount_type) === $type ? 'selected' : '' }}>
                                                            {{ ucfirst($type) }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label for="amount" class="form-label">Amount</label>
                                                <input type="number" class="form-control form-control-sm" id="amount" name="amount"
                                                    value="{{ old('amount', optional($product->discount)->amount) }}">
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label for="start_date" class="form-label">Start Date</label>
                                                <input type="date" class="form-control" id="start_date" name="start_date"
                                                    value="{{ old('start_date', optional($product->discount)->start_date?->format('Y-m-d')) }}">
                                            </div>

                                            <div class="col-md-6 mb-2">
                                                <label for="end_date" class="form-label">End Date</label>
                                                <input type="date" class="form-control" id="end_date" name="end_date"
                                                    value="{{ old('end_date', optional($product->discount)->end_date?->format('Y-m-d')) }}">
                                            </div>

                                            <!-- Discount Status -->
                                            <div class="mt-4 border-top pt-3">
                                                <div class="form-check form-switch">
                                                    <input type="hidden" name="discount_status" value="0">
                                                    <input class="form-check-input"
                                                        type="checkbox" name="discount_status" id="discountStatusToggle" value="1"
                                                        {{ old('discount_status', optional($product->discount)->status ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="discountStatusToggle">Enable Discount</label>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane text-muted" id="about-vertical-link" role="tabpanel">
                                        <!-- Weight -->
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between">
                                                <label for="weight" class="form-label">Weight (kg)</label>
                                                <div class="form-check form-switch mt-2">
                                                    <input type="hidden" name="free_shipping" value="0">
                                                    <input class="form-check-input" type="checkbox" id="freeShippingToggle" name="free_shipping" value="1"
                                                        {{ old('free_shipping', optional($product->shipping)->free_shipping ?? false) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="freeShippingToggle">Free Shipping</label>
                                                </div>


                                            </div>
                                            <input type="number" class="form-control" id="weight" name="weight" placeholder="0.00"
                                                value="{{ old('weight', optional($product->shipping)->weight) }}" step="0.01" min="0">
                                            @error('weight') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                        </div>

                                        <!-- Dimensions -->
                                        <div class="border p-2 mb-2 bg-light">
                                            <label class="form-label mb-2 d-block">Dimensions (cm)</label>
                                            <div class="row g-2">
                                                <div class="col-md-4">
                                                    <input type="number" class="form-control" id="length" name="length" placeholder="Length"
                                                        value="{{ old('length', optional($product->shipping)->length) }}" step="0.01" min="0">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="number" class="form-control" id="width" name="width" placeholder="Width"
                                                        value="{{ old('width', optional($product->shipping)->width) }}" step="0.01" min="0">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="number" class="form-control" id="height" name="height" placeholder="Height"
                                                        value="{{ old('height', optional($product->shipping)->height) }}" step="0.01" min="0">
                                                </div>
                                            </div>
                                            @if($errors->has('length') || $errors->has('width') || $errors->has('height'))
                                                <div class="text-danger mt-1">Please check all dimension fields.</div>
                                            @endif
                                        </div>

                                        <!-- Shipping Class & Rates -->
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label">Shipping Class</label>
                                                <select id="shipping_class_id" name="shipping_class_id" class="form-select">
                                                    @foreach($shippingClasses ?? [] as $class)
                                                        <option value="{{ $class->id }}"
                                                            {{ old('shipping_class_id', optional($product->shipping)->shipping_class_id) == $class->id ? 'selected' : '' }}
                                                            data-inside="{{ $class->inside_rate }}"
                                                            data-outside="{{ $class->outside_rate }}">
                                                            {{ $class->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Inside City Rate</label>
                                                <div class="input-group input-group-sm mb-3">
                                                    <span class="input-group-text">৳</span>
                                                    <input type="text" name="inside_city_rate" id="inside_rate_display" class="form-control form-control-sm" readonly
                                                        value="{{ old('inside_city_rate', optional($product->shipping)->inside_city_rate ?? '0') }}">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <label class="form-label">Outside City Rate</label>
                                                <div class="input-group input-group-sm mb-3">
                                                    <span class="input-group-text">৳</span>
                                                    <input type="text" name="outside_city_rate" id="outside_rate_display" class="form-control form-control-sm" readonly
                                                        value="{{ old('outside_city_rate', optional($product->shipping)->outside_city_rate ?? '0') }}">
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            const shippingSelect = document.getElementById('shipping_class_id');
                                            const insideRate = document.getElementById('inside_rate_display');
                                            const outsideRate = document.getElementById('outside_rate_display');

                                            function updateRates() {
                                                const selected = shippingSelect.selectedOptions[0];
                                                // Only update if old() is not set
                                                if (!insideRate.dataset.initial) insideRate.value = selected ? selected.dataset.inside : 0;
                                                if (!outsideRate.dataset.initial) outsideRate.value = selected ? selected.dataset.outside : 0;
                                            }

                                            shippingSelect.addEventListener('change', updateRates);
                                            updateRates(); // initialize on page load
                                        </script>

                                    </div>
                                    <div class="tab-pane text-muted" id="contacts-vertical-link" role="tabpanel">
                                        <div class="mb-1">
                                            <label for="meta_title" class="form-label">Meta Title</label>
                                            <input type="text" class="form-control" id="meta_title" name="meta_title"
                                                value="{{ old('meta_title', optional($product->seo)->meta_title) }}">
                                            @error('meta_title') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="mb-1">
                                            <label for="meta_description" class="form-label">Meta Description</label>
                                            <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ old('meta_description', optional($product->seo)->meta_description) }}</textarea>
                                            @error('meta_description') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="mb-1">
                                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords"
                                                value="{{ old('meta_keywords', optional($product->seo)->meta_keywords) }}"
                                                placeholder="Separate keywords with commas">
                                            @error('meta_keywords') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                        </div>

                                        <div class="row">
                                            <div class="col-md-{{optional($product->seo)->og_image ? '8': '12'}}">
                                                <label for="meta_image" class="form-label">Meta Image</label>
                                                <input type="file" class="form-control" id="meta_image" name="meta_image">
                                            </div>

                                            @if(optional($product->seo)->og_image)
                                                <div class="col-md-4 d-flex justify-content-between align-items-center">
                                                    <div class="form-check mt-4">
                                                        <input type="checkbox" class="form-check-input" id="delete_meta_image" name="delete_meta_image" value="1">
                                                        <label class="form-check-label text-danger" for="delete_meta_image">Delete Meta Image</label>
                                                    </div>
                                                    <img src="{{ asset(optional($product->seo)->og_image) }}" alt="Meta Image" style="width: 68px;">
                                                </div>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Variants -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Product Variants Preview</div>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Attributes</label>
                                <select name="attribute_id[]" id="attribute_id" class="form-select searchable" multiple>
                                    @foreach($attributes as $attribute)
                                        <option value="{{ $attribute->id }}"
                                            {{ $product->variants->pluck('variantItems.*.attribute_id')->flatten()->unique()->contains($attribute->id) ? 'selected' : '' }}>
                                            {{ $attribute->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="attribute_items_container" class="row"></div>
                        <div id="variant_combinations_container"></div>
                    </div>
                </div>

                @push('css')
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
                @endpush

                @push('js')
                <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
                <script>
                    $(function() {

                        // Initialize Choices.js for searchable selects
                        function initChoices() {
                            $('.attribute-item').each(function() {
                                if (!$(this).data('choices-initialized')) {
                                    new Choices(this, {
                                        removeItemButton: true,
                                        searchEnabled: true,
                                        placeholderValue: 'Select Items'
                                    });
                                    $(this).data('choices-initialized', true);
                                }
                            });
                        }

                        // Load attribute items partial via AJAX
                        function loadAttributeItems(loadExisting = true) {
                            let selected = $('#attribute_id').val();
                            if (!selected || !selected.length) {
                                $('#attribute_items_container').html('');
                                $('#variant_combinations_container').html('');
                                return;
                            }

                            $.get("{{ route('attributes.getItems') }}", {
                                attribute_ids: selected,
                                product_id: "{{ $product->id ?? '' }}"
                            }, function(html) {
                                $('#attribute_items_container').html(html);
                                initChoices();

                                if (loadExisting) {
                                    setTimeout(() => {
                                        syncImageUploadFields();
                                        loadVariantCombinations();
                                    }, 300);
                                }
                            });
                        }

                        // Generate variant combinations table
                        function loadVariantCombinations() {
                            let attrs = [];
                            $('.attribute-item').each(function() {
                                let id = $(this).data('id');
                                let items = $(this).val();
                                if (items && items.length) attrs.push({ id, items });
                            });
                            if (!attrs.length) return $('#variant_combinations_container').html('');

                            $.get('{{ route("products.getItemsCombo") }}', {
                                sku_prefix: $('#sku_prefix').val(),
                                sale_price: $('#sale_price').val(),
                                purchase_price: $('#purchase_price').val(),
                                attributes: attrs,
                                product_id: '{{ $product->id ?? '' }}'
                            }, function(html) {
                                $('#variant_combinations_container').html(html);
                            });
                        }

                        // Sync image upload fields with selected options
                        function syncImageUploadFields() {
                            $('.attribute-item').each(function() {
                                if (!$(this).data('has-image')) return;

                                let attrId = $(this).data('id');
                                let container = $(`.image-upload-container[data-attr-id="${attrId}"] .image-upload-fields`);
                                let selectedIds = $(this).val() || [];

                                // Handle dynamically added fields only
                                container.find('.single-upload-field').each(function() {
                                    let fieldItemId = $(this).data('item-id');
                                    // Only target fields that were dynamically added (no existing image)
                                    if (!$(this).data('existing')) {
                                        if (!selectedIds.includes(String(fieldItemId))) {
                                            $(this).remove(); // remove newly added field if unselected
                                        }
                                    }
                                });

                                // Add new fields for newly selected items that don't exist yet
                                selectedIds.forEach(function(itemId) {
                                    if (container.find(`[data-item-id="${itemId}"]`).length) return; // already exists
                                    let itemName = $(`.attribute-item[data-id="${attrId}"] option[value="${itemId}"]`).text();
                                    container.append(`
                                        <div class="d-flex align-items-center mb-2 single-upload-field" data-item-id="${itemId}">
                                            <span class="me-2 fw-semibold text-secondary" style="min-width:80px">${itemName}</span>
                                            <input type="file" name="attribute_images[${attrId}][${itemId}]" class="form-control form-control-sm" accept="image/*">
                                        </div>
                                    `);
                                });
                            });
                        }


                        // Attribute select change (main attribute selection)
                        $('#attribute_id').on('change', function() {
                            loadAttributeItems(false);
                            $('#variant_combinations_container').html('');
                        });

                        // Individual attribute items select change
                        $(document).on('change', '.attribute-item', function() {
                            syncImageUploadFields();
                            loadVariantCombinations();
                        });

                        // Update variant combinations when prices or SKU change
                        $(document).on('keyup change', '#sku_prefix, #sale_price, #purchase_price', loadVariantCombinations);

                        // Remove variant row
                        $(document).on('click', '.remove-variant', function() {
                            $(this).closest('tr').remove();
                        });

                        // Initialize Choices.js for main attributes select
                        new Choices('#attribute_id', { removeItemButton: true, searchEnabled: true });

                        // Initial load on page ready
                        loadAttributeItems(true);

                    });




                </script>
                @endpush






            </div>

            <!-- Right Column: Categories + Brands + Tags + Settings -->
            <div class="col-md-4">
                <!-- Product Categories -->
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Categories</div>
                        <a href="#" class="btn btn-primary-light btn-sm"><i class="bi bi-plus-lg"></i> Add New</a>
                    </div>
                    <div class="card-body pt-1">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select name="category_id" id="category_id" class="form-select searchable" data-placeholder="Select Category" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Product Brand -->
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Brands</div>
                        <a href="#" class="btn btn-primary-light btn-sm"><i class="bi bi-plus-lg"></i> Add New</a>
                    </div>
                    <div class="card-body pt-1">
                        <label for="brand_id" class="form-label">Brand</label>
                        <select name="brand_id" id="brand_id" class="form-select searchable" data-placeholder="Select Brand">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Product Tag -->
                <div class="card custom-card">
                    <div class="card-header justify-content-between">
                        <div class="card-title">Tags</div>
                        <a href="#" class="btn btn-primary-light btn-sm"><i class="bi bi-plus-lg"></i> Add New</a>
                    </div>
                    <div class="card-body pt-1">
                        <label for="tag_id" class="form-label">Tag</label>
                        <select name="tag_id" id="tag_id" class="form-select searchable" multiple data-placeholder="Select Tags">
                            <option value="">Select Tag</option>
                            @foreach($tags as $tag)
                                <option value="{{ $tag->id }}"
                                    {{ old('tag_id', $product->tag_id ?? '') == $tag->id ? 'selected' : '' }}>
                                    {{ $tag->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('tag_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Settings -->
                <div class="card custom-card mt-3">
                    <div class="card-header">
                        <div class="card-title">Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-1">
                            <label for="product_type" class="form-label">Product Type</label>
                            <select class="form-select" id="product_type" name="product_type">
                                @php $ptype = old('product_type', $product->product_type ?? 'sale'); @endphp
                                <option value="sale" {{ $ptype == 'sale' ? 'selected' : '' }}>Sale</option>
                                <option value="hot" {{ $ptype == 'hot' ? 'selected' : '' }}>Hot</option>
                                <option value="regular" {{ $ptype == 'regular' ? 'selected' : '' }}>Regular</option>
                                <option value="trending" {{ $ptype == 'trending' ? 'selected' : '' }}>Trending</option>
                            </select>
                        </div>

                        <div class="mb-1">
                            <label for="visibility" class="form-label">Visibility</label>
                            <select class="form-select" id="visibility" name="visibility">
                                @php $vis = old('visibility', $product->visibility ?? 'public'); @endphp
                                <option value="public" {{ $vis == 'public' ? 'selected' : '' }}>Public</option>
                                <option value="private" {{ $vis == 'private' ? 'selected' : '' }}>Private</option>
                                <option value="schedule" {{ $vis == 'schedule' ? 'selected' : '' }}>Schedule</option>
                            </select>
                        </div>

                        <div class="mb-1">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                @php $status = old('status', $product->status ?? 1); @endphp
                                <option value="1" {{ $status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>


                <!-- Actions -->
                <div class="card custom-card mt-3">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100">Update Product</button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary w-100 mt-2">Cancel</a>
                    </div>
                </div>

            </div>
        </div>
    </form>


    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('select.searchable').forEach(select => {
                new Choices(select, {
                    searchEnabled: true,
                    shouldSort: false,
                    removeItemButton: true, // allow removing selected tags
                    placeholder: true,
                    placeholderValue: select.dataset.placeholder || 'Select an option'
                });
            });
        });
    </script>
    @endpush
</x-backend-layout>
