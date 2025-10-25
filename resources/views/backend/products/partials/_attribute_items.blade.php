@foreach($attributes as $attribute)
<div class="col-md-4 mb-2 attribute-item-wrapper">
    <label class="form-label">{{ $attribute->name }} Items</label>
    <select class="form-select attribute-item"
            data-id="{{ $attribute->id }}"
            data-name="{{ $attribute->name }}"
            data-has-image="{{ $attribute->has_image ? 1 : 0 }}"
            multiple
            name="attribute_items[{{ $attribute->id }}][]">
        @foreach($attribute->items as $item)
            <option value="{{ $item->id }}">{{ $item->name }}</option>
        @endforeach
    </select>

    @if($attribute->has_image)
    <div class="image-upload-container" data-attr-id="{{ $attribute->id }}">
        <label class="form-label fw-semibold">Upload Images for {{ $attribute->name }}</label>
        <div class="image-upload-fields border rounded p-2 bg-light"></div>
    </div>
    @endif
</div>
@endforeach
