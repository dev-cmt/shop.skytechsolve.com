<!--backend.products.partisals._variant_table.blade.php-->
@if(count($variants))
<table class="table table-sm table-bordered">
    <thead>
        <tr>
            <th>Variant Name</th>
            <th>SKU</th>
            <th>Price</th>
            <th>Purchase Price</th>
            <th>Quantity</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach($variants as $variant)
        <tr>
            <td>{{ $variant['name'] }}</td>
            <td><input type="text" name="variants[sku][]" class="form-control form-control-sm" value="{{ $variant['sku'] }}"></td>
            <td><input type="number" name="variants[price][]" class="form-control form-control-sm" value="{{ $variant['price'] }}"></td>
            <td><input type="number" name="variants[purchase_cost][]" class="form-control form-control-sm" value="{{ $variant['purchase_price'] }}"></td>
            <td><input type="number" name="variants[quantity][]" class="form-control form-control-sm" value="{{ $variant['quantity'] }}"></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger remove-variant">X</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endif
