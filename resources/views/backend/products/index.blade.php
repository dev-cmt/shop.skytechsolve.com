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
                        <div class="mb-2">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                            @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-2">
                            <label for="description" class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control summernote" rows="4">{!! old('description') !!}</textarea>
                            @error('description') <div class="text-danger mt-1">{{ $message }}</div> @enderror
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
                                        <div class="row">
                                            <div class="col-md-6 mb-1">
                                                <label for="total_stock" class="form-label">Sku Prefix <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control form-control-sm" id="total_stock" name="total_stock" value="{{ old('total_stock') }}">
                                                @error('total_stock') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label for="base_price" class="form-label">Stock Management <span class="text-danger">*</span></label>
                                                <select name="discount_type" id="discount_type" class="form-select">
                                                    <option value="7 Day" {{ old('discount_type')=='percentage'?'selected':'' }}>Quantity</option>
                                                    <option value="flat" {{ old('discount_type')=='flat'?'selected':'' }}>In Stock</option>
                                                    <option value="flat" {{ old('discount_type')=='flat'?'selected':'' }}>Out Of Stock</option>
                                                </select>
                                                @error('base_price') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label for="total_stock" class="form-label">Price <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" id="total_stock" name="total_stock" value="{{ old('total_stock', 0) }}">
                                                @error('total_stock') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label for="base_price" class="form-label">Purchase Price</label>
                                                <input type="number" class="form-control form-control-sm" id="base_price" name="base_price" value="{{ old('base_price') }}" required>
                                                @error('base_price') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label for="total_stock" class="form-label">Total Stock</label>
                                                <input type="number" class="form-control form-control-sm" id="total_stock" name="total_stock" value="{{ old('total_stock', 0) }}">
                                                @error('total_stock') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label for="stock_out" class="form-label">Stock Out <span class="text-danger">*</span></label>
                                                <input type="number" class="form-control form-control-sm" id="stock_out" name="stock_out" value="{{ old('stock_out', 1) }}" required>
                                                @error('base_price') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label for="base_price" class="form-label">Alert Quantity</label>
                                                <input type="number" class="form-control form-control-sm" id="base_price" name="base_price" value="{{ old('base_price') }}" required>
                                                @error('base_price') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>
                                            <div class="col-md-6 mb-1">
                                                <label for="base_price" class="form-label">Expire</label>
                                                <select name="discount_type" id="discount_type" class="form-select">
                                                    <option value="">Select</option>
                                                    <option value="7 Day" {{ old('discount_type')=='percentage'?'selected':'' }}>7 Days</option>
                                                    <option value="flat" {{ old('discount_type')=='flat'?'selected':'' }}>15 Days</option>
                                                    <option value="flat" {{ old('discount_type')=='flat'?'selected':'' }}>1 Month</option>
                                                    <option value="flat" {{ old('discount_type')=='flat'?'selected':'' }}>2 Month</option>
                                                    <option value="flat" {{ old('discount_type')=='flat'?'selected':'' }}>3 Month</option>
                                                    <option value="flat" {{ old('discount_type')=='flat'?'selected':'' }}>6 Month</option>
                                                    <option value="flat" {{ old('discount_type')=='flat'?'selected':'' }}>1 Year</option>
                                                    <option value="flat" {{ old('discount_type')=='flat'?'selected':'' }}>2 Year</option>
                                                    <option value="flat" {{ old('discount_type')=='flat'?'selected':'' }}>3 Year</option>
                                                    <option value="flat" {{ old('discount_type')=='flat'?'selected':'' }}>5 Year</option>
                                                    <option value="flat" {{ old('discount_type')=='flat'?'selected':'' }}>10 Year</option>
                                                    <option value="flat" {{ old('discount_type')=='flat'?'selected':'' }}>Life Time</option>
                                                </select>
                                                @error('base_price') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                            </div>
                                        </div>

                                    </div>
                                    <div class="tab-pane text-muted" id="services-vertical-link" role="tabpanel">
                                        <div class="row">
                                            <div class="col-md-6 mb-2">
                                                <label for="discount_type" class="form-label">Discount Type</label>
                                                <select name="discount_type" id="discount_type" class="form-select">
                                                    <option value="">Select Type</option>
                                                    <option value="percentage" {{ old('discount_type')=='percentage'?'selected':'' }}>Percentage</option>
                                                    <option value="flat" {{ old('discount_type')=='flat'?'selected':'' }}>Flat</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label for="discount_amount" class="form-label">Amount</label>
                                                <input type="number" class="form-control form-control-sm" id="discount_amount" name="discount_amount" value="{{ old('discount_amount') }}">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label for="discount_start" class="form-label">Start Date</label>
                                                <input type="date" class="form-control" id="discount_start" name="discount_start" value="{{ old('discount_start') }}">
                                            </div>
                                            <div class="col-md-6 mb-2">
                                                <label for="discount_end" class="form-label">End Date</label>
                                                <input type="date" class="form-control" id="discount_end" name="discount_end" value="{{ old('discount_end') }}">
                                            </div>
                                            <!-- Discount Status -->
                                            <div class="mt-4 border-top pt-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" name="discount_status" type="checkbox" id="discountStatusToggle">
                                                    <label class="form-check-label" for="discountStatusToggle">Enable Discount</label>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="tab-pane text-muted" id="about-vertical-link" role="tabpanel">
                                        <!-- Weight -->
                                        <div class="mb-2">
                                            <label for="weight" class="form-label">Weight (kg)</label>
                                            <input type="number" class="form-control" id="weight" name="weight" value="{{ old('weight', 0) }}" step="0.01" min="0">
                                            @error('weight') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="border p-1 mb-2 bg-light">
                                            <label class="form-label mb-2 d-block">Dimensions (cm)</label>
                                            <div class="row g-2">
                                                <div class="col-md-4">
                                                    <input type="number" class="form-control" id="length" name="length" placeholder="Length" value="{{ old('length') }}" step="0.01" min="0">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="number" class="form-control" id="width" name="width" placeholder="Width" value="{{ old('width') }}" step="0.01" min="0">
                                                </div>
                                                <div class="col-md-4">
                                                    <input type="number" class="form-control" id="height" name="height" placeholder="Height" value="{{ old('height') }}" step="0.01" min="0">
                                                </div>
                                            </div>
                                            @if($errors->has('length') || $errors->has('width') || $errors->has('height'))
                                                <div class="text-danger mt-1">Please check all dimension fields.</div>
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label">Shipping Class</label>
                                                <select id="shipping_class" class="form-select">
                                                    <option value="">Select Shipping Class</option>
                                                    @foreach($shippingClasses ?? [] as $class)
                                                        <option value="{{ $class->id }}" data-inside="{{ $class->inside_rate }}" data-outside="{{ $class->outside_rate }}"
                                                            {{ old('shipping_class') == $class->id ? 'selected' : '' }}>
                                                            {{ $class->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label">Inside City Rate</label>
                                                <input type="text" id="inside_rate_display" class="form-control form-control-sm" readonly>
                                            </div>
                                            <div class="col-3">
                                                <label class="form-label">Outside City Rate</label>
                                                <input type="text" id="outside_rate_display" class="form-control form-control-sm" readonly>
                                            </div>
                                        </div>

                                        <script>
                                            const s=document.getElementById('shipping_class'),i=document.getElementById('inside_rate_display'),o=document.getElementById('outside_rate_display');
                                            function u(){const e=s.selectedOptions[0];i.value=`৳ ${e?.dataset.inside||0}`,o.value=`৳ ${e?.dataset.outside||0}`}
                                            s.addEventListener('change',u),u();
                                        </script>
                                    </div>
                                    <div class="tab-pane text-muted" id="contacts-vertical-link" role="tabpanel">
                                        <div class="mb-1">
                                            <label for="meta_title" class="form-label">Meta Title</label>
                                            <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title') }}">
                                            @error('meta_title') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="mb-1">
                                            <label for="meta_description" class="form-label">Meta Description</label>
                                            <textarea class="form-control" id="meta_description" name="meta_description" rows="2">{{ old('meta_description') }}</textarea>
                                            @error('meta_description') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="mb-1">
                                            <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                            <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}" placeholder="Separate keywords with commas">
                                            @error('meta_keywords') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Product Variants -->
                <div class="card custom-card">
                    <div class="card-header"><div class="card-title">Product Variants Preview</div></div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">SKU Prefix <span class="text-danger">*</span></label>
                                <input type="text" class="form-control form-control-sm" id="sku_prefix" name="sku_prefix" value="{{ old('sku_prefix','PROD') }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Base Price <span class="text-danger">*</span></label>
                                <input type="number" class="form-control form-control-sm" id="price" name="price" value="{{ old('price','0.00') }}" min="0" step="0.01">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Attributes</label>
                                <select name="attribute_id[]" id="attribute_id" class="form-select" multiple>
                                    @foreach($attributes as $attribute)
                                        <option data-name="{{ $attribute->name }}" value="{{ $attribute->id }}"
                                        @if(isset($productAttributes) && in_array($attribute->id, $productAttributes)) selected @endif
                                        >{{ $attribute->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row" id="attribute_items_container"></div>

                        <div class="mt-3" id="variant_combinations_container">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th class="w-1/4">Variant Name</th>
                                        <th class="w-1/4">SKU</th>
                                        <th class="w-1/5">Price</th>
                                        <th class="w-1/5">Quantity</th>
                                        <th class="w-1/12 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>

                        <div id="error_msg" class="text-danger mt-2"></div>
                    </div>
                </div>

                <!-- Hidden templates -->
                <div id="attribute-item-template" style="display: none;">
                    <div class="col-md-4 mb-3 attribute-item-wrapper">
                        <label class="attribute-name-label"></label>
                        <select class="form-select attribute-item" multiple></select>
                    </div>
                </div>

                <table style="display:none;">
                    <tbody id="variant-row-template">
                        <tr>
                            <td class="variant-name"></td>
                            <td><input type="text" name="variants[sku][]" class="form-control form-control-sm sku-input" value=""></td>
                            <td><input type="number" name="variants[price][]" class="form-control form-control-sm price-input" value="0"></td>
                            <td><input type="number" name="variants[quantity][]" class="form-control form-control-sm quantity-input" value="0"></td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm btn-outline-danger remove-variant">X</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

                @push('js')
                <script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

                <script>
                $(function(){

                    const escapeHtml = s => s.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#039;");
                    const cartesianProduct = arr => arr.length ? arr.reduce((a,b)=>a.flatMap(d=>b.map(e=>[].concat(d,e))),[[]]) : [];

                    let attributeSelect, itemChoicesMap = new Map();

                    // Initialize main attribute select
                    function initAttributeSelect(){
                        if(attributeSelect) attributeSelect.destroy();
                        attributeSelect = new Choices('#attribute_id', { removeItemButton: true, searchEnabled: true });
                    }

                    // Initialize dynamically added attribute item selects
                    function initItemChoices(){
                        $('.attribute-item').each(function(){
                            let $select = $(this);
                            let attrId = $select.data('id');

                            if(!itemChoicesMap.has(attrId)){
                                let choice = new Choices(this, { removeItemButton: true, searchEnabled: true });
                                itemChoicesMap.set(attrId, choice);
                                
                                // *** FIX: Add direct event listener for reliable variant generation ***
                                this.addEventListener('change', generateVariants); 

                                // Preselect old values if editing
                                if(window.oldAttributeItems && window.oldAttributeItems[attrId]){
                                    // Use setChoiceByValue for Choices.js
                                    choice.setChoiceByValue(window.oldAttributeItems[attrId]);
                                    // The direct listener added above will handle triggering generateVariants after setting value.
                                }
                            }
                        });
                    }

                    // Render attribute items using template
                    function renderAttributeItems(attributes){
                        // Clear old Choices instances before clearing container
                        itemChoicesMap.forEach(choice => choice.destroy());
                        itemChoicesMap.clear(); 
                        
                        $('#attribute_items_container').empty();
                        
                        attributes.forEach(attr => {
                            let $template = $('#attribute-item-template .attribute-item-wrapper').clone();
                            $template.find('.attribute-name-label').text(attr.name);
                            let options = attr.items.map(i=>`<option value="${i.id}" data-name="${escapeHtml(i.name)}">${escapeHtml(i.name)}</option>`).join('');
                            $template.find('.attribute-item').html(options);
                            $template.find('.attribute-item').attr('data-id', attr.id).attr('data-name', attr.name);
                            $('#attribute_items_container').append($template);
                        });
                        
                        initItemChoices();
                        generateVariants();
                    }

                    // Load attribute items via AJAX
                    function loadItems(){
                        let selectedAttrs = $('#attribute_id').val();
                        if(!selectedAttrs?.length){
                            // Destroy Choices for attribute items if no attributes are selected
                            itemChoicesMap.forEach(choice => choice.destroy());
                            itemChoicesMap.clear();
                            
                            $('#attribute_items_container,#variant_combinations_container tbody').empty();
                            return;
                        }
                        // NOTE: Keeping the Blade route helper as is
                        $.get('{{ route("attributes.getItems") }}', { attribute_ids: selectedAttrs }, function(res){
                            renderAttributeItems(res);
                        });
                    }

                    // Add variant row
                    function addVariantRow(names, sku, price, qty=0){
                        let $row = $('#variant-row-template tr').clone();
                        $row.find('.variant-name').text(names.join(' | '));
                        $row.find('.sku-input').val(sku);
                        $row.find('.price-input').val(price);
                        $row.find('.quantity-input').val(qty);
                        $('#variant_combinations_container tbody').append($row);
                    }

                    // Generate variant combinations
                    function generateVariants(){
                        let skuPrefix = $('#sku_prefix').val().trim() || 'SKU';
                        let price = $('#price').val().trim() || '0.00';

                        let attrs = $('.attribute-item').map(function(){
                            // Use val() to get selected IDs from the actual select element
                            let items = $(this).val(); 
                            if(!items?.length) return null;
                            
                            // Collect the corresponding names for the selected IDs
                            let names = $(this).find('option:selected').filter(function() {
                                // Ensure we only map options that are actually selected/available
                                return items.includes($(this).val());
                            }).map((_,o)=>$(o).data('name')).get();

                            return {id: $(this).data('id'), items, names};
                        }).get().filter(a => a !== null); // Filter out nulls

                        $('#variant_combinations_container tbody').empty();
                        if(!attrs.length) return;

                        // Map items array to cartesian product input
                        let combos = cartesianProduct(attrs.map(a=>a.items));
                        
                        combos.forEach(c=>{
                            // c is an array of IDs from the cartesian product, e.g., [101, 201]
                            
                            let names = c.map((id, j) => {
                                // Find the name corresponding to the current item ID (id) 
                                // within the list of selected item IDs (attrs[j].items) for attribute j.
                                let index = attrs[j].items.indexOf(id);
                                return attrs[j].names[index];
                            });
                            
                            let sku = skuPrefix + '-' + names.map(n=>n.toLowerCase().replace(/[^a-z0-9]+/g,'-')).join('-');

                            // Existing variant values
                            let priceValue = price, qtyValue = 0;
                            if(window.oldVariants){
                                let existing = window.oldVariants.find(v=>v.sku==sku);
                                if(existing){ priceValue = existing.price; qtyValue = existing.quantity; }
                            }

                            addVariantRow(names, sku, priceValue, qtyValue);
                        });
                    }

                    // Remove variant row
                    $(document).on('click', '.remove-variant', function(){ $(this).closest('tr').remove(); });

                    // SKU uniqueness check (Mocked/untested functionality as route is external)
                    function checkSKU(sku){
                        // Only run check if the SKU input field has focus/key-up happened.
                        if (sku.length === 0) {
                            $('#form_add_btn').prop('disabled', false);
                            $('#error_msg').text('');
                            return;
                        }
                        
                        // This relies on Laravel setup being present for token and route
                        // $.post('https://stock.prodevsltd.xyz/admin-product/sku_check', {
                        //     _token: $('meta[name="csrf-token"]').attr('content'),
                        //     sku: sku
                        // }, function(data){
                        //     if(data=='found'){
                        //         $('#form_add_btn').prop('disabled', true);
                        //         $('#error_msg').text('SKU Already Exists!');
                        //     } else {
                        //         $('#form_add_btn').prop('disabled', false);
                        //         $('#error_msg').text('');
                        //     }
                        // });
                        
                        // Mocking the check for environment without backend
                        if (sku.toLowerCase().includes('mock-error')) {
                            $('#form_add_btn').prop('disabled', true);
                            $('#error_msg').text('SKU Already Exists! (Mocked)');
                        } else {
                            $('#form_add_btn').prop('disabled', false);
                            $('#error_msg').text('');
                        }
                    }

                    // Events
                    $('#attribute_id').on('change', loadItems);
                    
                    // Kept the delegated handler for static inputs and as a safety net, 
                    // but the primary fix for .attribute-item is the direct listener in initItemChoices.
                    $(document).on('change keyup', '#sku_prefix,#price', generateVariants); 
                    
                    $(document).on('keyup', '.sku-input', function(){ checkSKU($(this).val()); });

                    // Initialize Choices
                    initAttributeSelect();

                    // Preload old product data
                    @if(isset($product) && $product)
                        window.oldAttributeItems = {!! json_encode($productAttributeItems ?? []) !!};
                        window.oldVariants = {!! json_encode($productVariants ?? []) !!};
                    @endif

                    loadItems();
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
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id')==$category->id ? 'selected' : '' }}>{{ $category->name }}</option>
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
                        <select name="brand_id" id="brand_id" class="form-select">
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id')==$brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
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
                        <label for="brand_id" class="form-label">Tag</label>
                        <select name="brand_id" id="brand_id" class="form-select">
                            <option value="">Select Tag</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ old('brand_id')==$brand->id ? 'selected' : '' }}>{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('brand_id') <div class="text-danger mt-1">{{ $message }}</div> @enderror
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
                                <option value="sale" {{ old('product_type',1)==1?'selected':'' }}>Sale</option>
                                <option value="hot" {{ old('product_type',1)==0?'selected':'' }}>Hot</option>
                                <option value="regular" {{ old('product_type',1)==0?'selected':'' }}>Regular</option>
                                <option value="trending" {{ old('product_type',1)==0?'selected':'' }}>Trending</option>
                            </select>
                        </div>
                        <div class="mb-1">
                            <label for="visibility" class="form-label">Visibility</label>
                            <select class="form-select" id="visibility" name="visibility">
                                <option value="public" {{ old('visibility',1)==1?'selected':'' }}>Public</option>
                                <option value="private" {{ old('visibility',1)==0?'selected':'' }}>Private</option>
                                <option value="schedule" {{ old('visibility',1)==0?'selected':'' }}>Schedule</option>
                            </select>
                        </div>
                        <div class="mb-1">
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
