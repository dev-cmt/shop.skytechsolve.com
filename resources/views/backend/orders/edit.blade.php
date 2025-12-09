<x-backend-layout title="Edit Order {{ $order->invoice_no ?? '' }}">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Edit Order: {{ $order->invoice_no ?? ''}}</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <form action="{{ route('orders.update', $order->id ) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-xl-9">
                        <div class="card custom-card">
                            <div class="card-header"><div class="card-title">Order Details</div></div>
                            <div class="card-body">
                                @if($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                                    </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Invoice No <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="invoice_no" value="{{ old('invoice_no', $order->invoice_no) }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Store <span class="text-danger">*</span></label>
                                        <select class="form-select" name="store_id" required>
                                            <option value="">Select Store</option>
                                            @foreach($stores as $store)
                                                <option value="{{ $store->id }}" {{ old('store_id', $order->store_id) == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Source</label>
                                        <input type="text" class="form-control" name="source" value="{{ old('source', $order->source) }}" placeholder="e.g., Facebook, Website, Walk-in">
                                    </div>
                                </div>
                                
                                <h6 class="mt-3 mb-3">Customer Information</h6>
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="customer_name" value="{{ old('customer_name', $order->customer_name) }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Customer Phone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="customer_phone" value="{{ old('customer_phone', $order->customer_phone) }}" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">Existing Customer</label>
                                        <select class="form-select" name="customer_id" id="customer_select">
                                            <option value="">Search/Select Customer</option>
                                            @foreach($customers as $customer)
                                                <option value="{{ $customer->id }}" data-name="{{ $customer->name }}" data-phone="{{ $customer->phone }}" {{ old('customer_id', $order->customer_id) == $customer->id ? 'selected' : '' }}>{{ $customer->name }} ({{ $customer->phone }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Address</label>
                                        <textarea class="form-control" name="customer_address" rows="2">{{ old('customer_address', $order->customer_address) }}</textarea>
                                    </div>
                                </div>

                                <h6 class="mt-3 mb-3">Products</h6>
                                <div class="table-responsive">
                                    <table class="table table-bordered text-nowrap" id="order-items-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 40%;">Product</th>
                                                <th style="width: 15%;">SKU</th>
                                                <th style="width: 10%;">Qty</th>
                                                <th style="width: 15%;">Sale Price</th>
                                                <th style="width: 15%;">Subtotal</th>
                                                <th style="width: 5%;">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="order-items-body">
                                            @foreach($order->items as $item)
                                                <tr data-index="existing-{{ $item->id }}">
                                                    <input type="hidden" name="items[existing-{{ $item->id }}][id]" value="{{ $item->id }}">
                                                    <input type="hidden" name="items[existing-{{ $item->id }}][purchase_price]" value="{{ old('items.existing-'.$item->id.'.purchase_price', $item->purchase_price) }}" class="item-purchase-price">
                                                    <input type="hidden" name="items[existing-{{ $item->id }}][attributes]" value='{{ json_encode(old('items.existing-'.$item->id.'.attributes', $item->attributes)) }}'>
                                                    
                                                    <td>
                                                        <select class="form-select product-select" name="items[existing-{{ $item->id }}][product_id]" required data-index="existing-{{ $item->id }}">
                                                            <option value="">Select Product</option>
                                                            @foreach($products as $product)
                                                                <option 
                                                                    value="{{ $product->id }}" 
                                                                    data-price="{{ $product->sale_price }}" 
                                                                    data-sku="{{ $product->sku }}"
                                                                    {{ old('items.existing-'.$item->id.'.product_id', $item->product_id) == $product->id ? 'selected' : '' }}
                                                                >
                                                                    {{ $product->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" class="form-control form-control-sm item-sku" name="items[existing-{{ $item->id }}][sku]" value="{{ old('items.existing-'.$item->id.'.sku', $item->sku) }}" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control form-control-sm item-qty" name="items[existing-{{ $item->id }}][quantity]" value="{{ old('items.existing-'.$item->id.'.quantity', $item->quantity) }}" min="1" required data-index="existing-{{ $item->id }}">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control form-control-sm item-price" name="items[existing-{{ $item->id }}][sale_price]" value="{{ old('items.existing-'.$item->id.'.sale_price', $item->sale_price) }}" step="0.01" min="0" required data-index="existing-{{ $item->id }}">
                                                    </td>
                                                    <td class="item-subtotal-display">{{ number_format($item->quantity * $item->sale_price, 2) }}</td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-danger-light remove-item-btn"><i class="ri-delete-bin-line"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            {{-- If validation fails, old items need to be handled by the controller/view logic to maintain integrity. 
                                            The above loop assumes successful loads or relies on the JS to re-render if needed. --}}
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" class="text-end">
                                                    <button type="button" class="btn btn-sm btn-info" id="add-item-btn">Add Product</button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <div class="d-none">
                                    {{-- Hidden inputs for purchase price and attributes. --}}
                                    <input type="hidden" name="items[new_item_placeholder][purchase_price]" value="0">
                                    <input type="hidden" name="items[new_item_placeholder][attributes]" value="[]">
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-3">
                        <div class="card custom-card">
                            <div class="card-header"><div class="card-title">Summary & Status</div></div>
                            <div class="card-body">
                                <h6 class="mb-3">Financial Summary</h6>
                                <div class="mb-3">
                                    <label class="form-label">Sub Total</label>
                                    <input type="number" class="form-control" name="sub_total" id="sub_total" value="{{ old('sub_total', $order->sub_total) }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Shipping Cost</label>
                                    <input type="number" class="form-control" name="shipping_cost" id="shipping_cost" value="{{ old('shipping_cost', $order->shipping_cost) }}" step="0.01" min="0">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Discount</label>
                                    <input type="number" class="form-control" name="discount" id="discount" value="{{ old('discount', $order->discount) }}" step="0.01" min="0">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Total Amount</label>
                                    <input type="number" class="form-control" name="total" id="total" value="{{ old('total', $order->total) }}" readonly>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Paid Amount <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" name="paid" id="paid" value="{{ old('paid', $order->paid) }}" step="0.01" min="0" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Due Amount</label>
                                    <input type="number" class="form-control" name="due" id="due" value="{{ old('due', $order->due) }}" readonly>
                                </div>
                                
                                <hr>

                                <div class="mb-3">
                                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-select" name="payment_method" required>
                                        @foreach($paymentMethods as $method)
                                            <option value="{{ $method }}" {{ old('payment_method', $order->payment_method) == $method ? 'selected' : '' }}>{{ $method }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Payment Status <span class="text-danger">*</span></label>
                                    <select class="form-select" name="payment_status" required>
                                        @foreach($paymentStatuses as $status)
                                            <option value="{{ $status }}" {{ old('payment_status', $order->payment_status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Order Status <span class="text-danger">*</span></label>
                                    <select class="form-select" name="status" required>
                                        @foreach($orderStatuses as $status)
                                            <option value="{{ $status }}" {{ old('status', $order->status) == $status ? 'selected' : '' }}>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Assigned To</label>
                                    <select class="form-select" name="assigned_to">
                                        <option value="">Select Staff</option>
                                        @foreach($staff as $user)
                                            <option value="{{ $user->id }}" {{ old('assigned_to', $order->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Notes</label>
                                    <textarea class="form-control" name="notes" rows="2">{{ old('notes', $order->notes) }}</textarea>
                                </div>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">Update Order</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('js')
    <script>
        // Note: The JavaScript logic from create.blade.php is reused here.
        // It needs a minor adjustment to initialize the itemIndex correctly if new items are added
        // alongside existing ones. We will set itemIndex to a high value.
        const productsData = @json($products->keyBy('id'));
        let itemIndex = 1000; // Start new item indexes high to avoid conflicts with existing item IDs

        // --- Core Functions (Copied from create.blade.php for simplicity) ---

        // Function to create an item row HTML (Used for adding *new* items)
        function createItemRow(item = {}) {
            const index = itemIndex++;
            const productId = item.product_id || '';
            const productName = item.product ? item.product.name : 'Select Product';
            const sku = item.sku || '';
            const quantity = item.quantity || 1;
            const salePrice = item.sale_price || 0;
            const subtotal = (quantity * salePrice).toFixed(2);
            
            const purchasePrice = item.purchase_price || 0; 
            const attributes = item.attributes ? JSON.stringify(item.attributes) : '[]';

            return `
                <tr data-index="${index}">
                    <input type="hidden" name="items[${index}][purchase_price]" value="${purchasePrice}" class="item-purchase-price">
                    <input type="hidden" name="items[${index}][attributes]" value='${attributes}'>
                    
                    <td>
                        <select class="form-select product-select" name="items[${index}][product_id]" required data-index="${index}">
                            <option value="">${productName}</option>
                            @foreach($products as $product)
                                <option 
                                    value="{{ $product->id }}" 
                                    data-price="{{ $product->sale_price }}" 
                                    data-sku="{{ $product->sku }}"
                                >
                                    {{ $product->name }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control form-control-sm item-sku" name="items[${index}][sku]" value="${sku}" readonly>
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm item-qty" name="items[${index}][quantity]" value="${quantity}" min="1" required data-index="${index}">
                    </td>
                    <td>
                        <input type="number" class="form-control form-control-sm item-price" name="items[${index}][sale_price]" value="${salePrice}" step="0.01" min="0" required data-index="${index}">
                    </td>
                    <td class="item-subtotal-display">${subtotal}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger-light remove-item-btn"><i class="ri-delete-bin-line"></i></button>
                    </td>
                    
                </tr>
            `;
        }


        // Function to update the subtotal for an item row
        function updateItemSubtotal(row) {
            const qty = parseFloat(row.find('.item-qty').val()) || 0;
            const price = parseFloat(row.find('.item-price').val()) || 0;
            const subtotal = (qty * price).toFixed(2);
            row.find('.item-subtotal-display').text(subtotal);
            calculateOrderSummary();
        }

        // Function to calculate and update the order summary
        function calculateOrderSummary() {
            let subTotal = 0;
            $('#order-items-body tr').each(function() {
                const row = $(this);
                const qty = parseFloat(row.find('.item-qty').val()) || 0;
                const price = parseFloat(row.find('.item-price').val()) || 0;
                subTotal += (qty * price);
            });

            const shippingCost = parseFloat($('#shipping_cost').val()) || 0;
            const discount = parseFloat($('#discount').val()) || 0;
            const paid = parseFloat($('#paid').val()) || 0;

            const total = (subTotal + shippingCost - discount);
            const due = (total - paid);

            $('#sub_total').val(subTotal.toFixed(2));
            $('#total').val(Math.max(0, total).toFixed(2));
            $('#due').val(due.toFixed(2));
            
             // Update payment status based on due amount
            if (total <= 0) {
                $('select[name="payment_status"]').val('Paid');
            } else if (due <= 0) {
                $('select[name="payment_status"]').val('Paid');
            } else if (due > 0 && paid > 0) {
                 $('select[name="payment_status"]').val('Partial');
            } else {
                 $('select[name="payment_status"]').val('Pending');
            }
        }


        // --- Event Listeners ---
        $(document).ready(function() {
            // Add Item Button (uses the new index function)
            $('#add-item-btn').on('click', function() {
                $('#order-items-body').append(createItemRow());
            });

            // Remove Item Button
            $('#order-items-body').on('click', '.remove-item-btn', function() {
                $(this).closest('tr').remove();
                calculateOrderSummary();
            });

            // Product Selection Change
            $('#order-items-body').on('change', '.product-select', function() {
                const select = $(this);
                const row = select.closest('tr');
                const selectedOption = select.find('option:selected');
                const price = parseFloat(selectedOption.data('price')) || 0;
                const sku = selectedOption.data('sku') || '';

                row.find('.item-price').val(price.toFixed(2));
                row.find('.item-sku').val(sku);
                row.find('.item-qty').val(1); 
                
                updateItemSubtotal(row);
            });

            // Quantity or Price Change
            $('#order-items-body').on('input', '.item-qty, .item-price', function() {
                updateItemSubtotal($(this).closest('tr'));
            });

            // Summary Field Change
            $('#shipping_cost, #discount, #paid').on('input', function() {
                calculateOrderSummary();
            });
            
            // Customer Select Change (Pre-fill name and phone)
            $('#customer_select').on('change', function() {
                const selectedOption = $(this).find('option:selected');
                const name = selectedOption.data('name');
                const phone = selectedOption.data('phone');
                
                if (name) {
                    $('input[name="customer_name"]').val(name);
                    $('input[name="customer_phone"]').val(phone);
                } else {
                    // Clear fields if 'Select Customer' is chosen
                    $('input[name="customer_name"]').val('');
                    $('input[name="customer_phone"]').val('');
                }
            });

            // Initial calculation on page load for the existing items
            calculateOrderSummary();
        });
    </script>
    @endpush
</x-backend-layout>