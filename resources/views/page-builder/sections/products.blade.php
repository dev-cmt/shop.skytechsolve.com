{{-- resources/views/page-builder/sections/products.blade.php --}}
<section class="products-section">
    <div class="container">
        @if(isset($content['title']))
            <h2 class="section-title">{{ $content['title'] }}</h2>
        @endif

        <div class="products-grid {{ $content['layout'] ?? 'grid-4' }}">
            @php
                $products = App\Models\Product::active()
                    ->when(isset($content['product_ids']), function($query) use ($content) {
                        return $query->whereIn('id', $content['product_ids']);
                    })
                    ->when(isset($content['limit']), function($query) use ($content) {
                        return $query->limit($content['limit']);
                    })
                    ->get();
            @endphp

            @foreach($products as $product)
                @include('components.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
