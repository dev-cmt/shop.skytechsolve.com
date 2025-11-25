{{-- resources/views/components/product-card.blade.php --}}
<div class="product-card">
    <div class="product-image">
        <img src="{{ $product->images[0] ?? '/images/placeholder.jpg' }}" alt="{{ $product->name }}">
        @if($product->sale_price)
            <span class="sale-badge">Sale</span>
        @endif
    </div>

    <div class="product-info">
        <h3>{{ $product->name }}</h3>
        <div class="price">
            @if($product->sale_price)
                <span class="current-price">${{ $product->sale_price }}</span>
                <span class="original-price">${{ $product->price }}</span>
            @else
                <span class="current-price">${{ $product->price }}</span>
            @endif
        </div>

        <form action="{{ route('cart.add') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <button type="submit" class="btn btn-primary">Add to Cart</button>
        </form>
    </div>
</div>
