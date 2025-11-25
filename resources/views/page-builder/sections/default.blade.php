{{-- resources/views/page-builder/sections/default.blade.php --}}
<section class="default-section py-5">
    <div class="container">
        <div class="alert alert-info">
            <h4>Section Type: {{ $section->type }}</h4>
            <p>This section type doesn't have a specific template yet.</p>
            <pre class="mt-3"><code>{{ json_encode($content, JSON_PRETTY_PRINT) }}</code></pre>
        </div>
    </div>
</section>
