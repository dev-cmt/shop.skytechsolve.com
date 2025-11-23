{{-- backend/products/partials/_attribute_items.blade.php --}}
@foreach ($attributes as $attribute)

    @php
        $itemsSelected = $selectedItems[$attribute->id] ?? [];
        $attributeImages = $existingImages[$attribute->id] ?? [];
    @endphp

    <div class="col-md-4 mb-3 attribute-item-wrapper">

        <label class="form-label">{{ $attribute->name }} Items</label>

        <select class="form-select attribute-item searchable"
                data-id="{{ $attribute->id }}"
                data-name="{{ $attribute->name }}"
                data-has-image="{{ $attribute->has_image ? 1 : 0 }}"
                name="attribute_items[{{ $attribute->id }}][]"
                multiple>
            @foreach ($attribute->items as $item)
                <option value="{{ $item->id }}"
                    {{ in_array($item->id, $itemsSelected) ? 'selected' : '' }}>
                    {{ $item->name }}
                </option>
            @endforeach
        </select>

        @if ($attribute->has_image)
            <div class="image-upload-container mt-2" data-attr-id="{{ $attribute->id }}">
                <label class="form-label fw-semibold">Upload Images for {{ $attribute->name }}</label>
                <div class="image-upload-fields border rounded p-2 bg-light">

                    {{-- Render upload fields for all selected items --}}
                    @foreach ($itemsSelected as $itemId)
                        @php
                            $itemName = optional($attribute->items->firstWhere('id', $itemId))->name ?? 'N/A';
                            $imagePath = $attributeImages[$itemId] ?? null;
                        @endphp
                        <div class="d-flex align-items-center mb-2 single-upload-field" data-item-id="{{ $itemId }}">
                            <span class="me-2 fw-semibold text-secondary" style="min-width:80px;">{{ $itemName }}</span>
                            <input type="file" name="attribute_images[{{ $attribute->id }}][{{ $itemId }}]" class="form-control form-control-sm" accept="image/*">
                            @if ($imagePath)
                                <img src="{{ asset($imagePath) }}" alt="{{ $itemName }}" class="ms-2" style="width:40px;height:40px;object-fit:cover;border-radius:6px;">
                            @endif
                        </div>
                    @endforeach

                </div>
            </div>
        @endif

    </div>

@endforeach
