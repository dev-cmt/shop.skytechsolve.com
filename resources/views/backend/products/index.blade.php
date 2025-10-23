<x-backend-layout title="Tags Management">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Add New Product</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add New</li>
                </ol>
            </nav>
        </div>
    </div>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Left Column: Basic Info + SEO + Discounts -->
            <div class="col-md-8">

                <!-- Basic Information -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Basic Information</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="slug" class="form-label">Slug <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="slug" name="slug" value="{{ old('slug') }}" disabled>
                            @error('slug') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control summernote" rows="5">{!! old('description') !!}</textarea>
                            @error('description') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="specification" class="form-label">Specification</label>
                            <textarea name="specification" id="specification" class="form-control summernote" rows="5">{!! old('specification') !!}</textarea>
                            @error('specification') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id')==$category->id ? 'selected' : '' }}>{{ $category->category_name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="brand_id" class="form-label">Brand</label>
                            <select name="brand_id" id="brand_id" class="form-select">
                                <option value="">Select Brand</option>
                                @foreach($brands as $brand)
                                    <option value="{{ $brand->id }}" {{ old('brand_id')==$brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="base_price" class="form-label">Base Price <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="base_price" name="base_price" value="{{ old('base_price') }}" required>
                                @error('base_price') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="total_stock" class="form-label">Total Stock</label>
                                <input type="number" class="form-control" id="total_stock" name="total_stock" value="{{ old('total_stock',0) }}">
                                @error('total_stock') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Information -->
                <div class="card custom-card mt-3">
                    <div class="card-header">
                        <div class="card-title">SEO Information</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title') }}">
                            @error('meta_title') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                            @error('meta_description') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}" placeholder="Separate keywords with commas">
                            @error('meta_keywords') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <!-- Discounts -->
                <div class="card custom-card mt-3">
                    <div class="card-header">
                        <div class="card-title">Discount</div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="discount_type" class="form-label">Discount Type</label>
                                <select name="discount_type" id="discount_type" class="form-select">
                                    <option value="">Select Type</option>
                                    <option value="percentage" {{ old('discount_type')=='percentage'?'selected':'' }}>Percentage</option>
                                    <option value="flat" {{ old('discount_type')=='flat'?'selected':'' }}>Flat</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="discount_amount" class="form-label">Amount</label>
                                <input type="number" class="form-control" id="discount_amount" name="discount_amount" value="{{ old('discount_amount') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="discount_start" class="form-label">Start Date</label>
                                <input type="date" class="form-control" id="discount_start" name="discount_start" value="{{ old('discount_start') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="discount_end" class="form-label">End Date</label>
                                <input type="date" class="form-control" id="discount_end" name="discount_end" value="{{ old('discount_end') }}">
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Media + Variants + Settings -->
            <div class="col-md-4">
                <!-- Product Images -->
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">Media</div>
                    </div>
                    <div class="card-body">
                        <label for="images" class="form-label">Product Images</label>
                        <div class="row mt-2" id="images-container">
                            <div class="col-md-6 col-12 mb-3">
                                <label class="image-container d-flex flex-column align-items-center justify-content-center position-relative border border-dashed rounded" style="cursor:pointer; min-height:100px;">
                                    <i class="ri-add-line text-secondary fs-3"></i>
                                    <span class="text-secondary fs-6 p-2">Add Image</span>
                                    <input type="file" id="images" name="images[]" multiple accept="image/*" class="d-none">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Variants -->
                <div class="card custom-card mt-3">
                    <div class="card-header">
                        <div class="card-title">Variants</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <button type="button" class="btn btn-sm btn-primary" id="add-variant"><i class="ri-add-line me-1"></i> Add Variant</button>
                        </div>
                        <div id="variants-container"></div>

                        <template id="variant-template">
                            <div class="variant-item border p-2 mb-2">
                                <div class="row g-2 align-items-end">
                                    <div class="col-12 mb-2">
                                        <label class="form-label">SKU</label>
                                        <input type="text" name="variant_sku[]" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Price</label>
                                        <input type="number" name="variant_price[]" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Stock</label>
                                        <input type="number" name="variant_stock[]" class="form-control">
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Attribute</label>
                                        <select name="variant_attribute_id[]" class="form-select">
                                            <option value="">Select Attribute</option>
                                            @foreach($attributes as $attr)
                                                <option value="{{ $attr->id }}">{{ $attr->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="form-label">Attribute Item</label>
                                        <select name="variant_attribute_item_id[]" class="form-select">
                                            <option value="">Select Item</option>
                                            @foreach($attribute_items as $item)
                                                <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <button type="button" class="btn btn-sm btn-danger remove-variant"><i class="ri-delete-bin-line"></i> Remove Variant</button>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Settings -->
                <div class="card custom-card mt-3">
                    <div class="card-header">
                        <div class="card-title">Settings</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="visibility" class="form-label">Visibility</label>
                            <select class="form-select" id="visibility" name="visibility">
                                <option value="1" {{ old('visibility',1)==1?'selected':'' }}>Visible</option>
                                <option value="0" {{ old('visibility',1)==0?'selected':'' }}>Hidden</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="1" {{ old('status',1)==1?'selected':'' }}>Active</option>
                                <option value="0" {{ old('status',1)==0?'selected':'' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card custom-card mt-3">
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary w-100">Save Product</button>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary w-100 mt-2">Cancel</a>
                    </div>
                </div>

            </div>
        </div>
    </form>

    <!-- JS for variants -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addVariantBtn = document.getElementById('add-variant');
            const variantsContainer = document.getElementById('variants-container');
            const variantTemplate = document.getElementById('variant-template').content;

            addVariantBtn.addEventListener('click', () => {
                const clone = variantTemplate.cloneNode(true);
                variantsContainer.appendChild(clone);
            });

            variantsContainer.addEventListener('click', (e) => {
                if (e.target.classList.contains('remove-variant') || e.target.closest('.remove-variant')) {
                    e.target.closest('.variant-item').remove();
                }
            });
        });
    </script>
</x-backend-layout>
