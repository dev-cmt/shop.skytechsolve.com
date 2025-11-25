{{-- resources/views/page-builder/sections/testimonials.blade.php --}}
<section class="testimonials-section py-5 bg-light">
    <div class="container">
        @if(isset($content['title']))
            <h2 class="section-title text-center mb-5">{{ $content['title'] }}</h2>
        @endif

        <div class="row">
            @if(isset($content['testimonials']) && is_array($content['testimonials']))
                @foreach($content['testimonials'] as $testimonial)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="testimonial-card card h-100">
                            <div class="card-body">
                                <div class="testimonial-text mb-3">
                                    "{{ $testimonial['quote'] ?? 'Great service and amazing support!' }}"
                                </div>
                                <div class="testimonial-author d-flex align-items-center">
                                    @if(isset($testimonial['avatar']))
                                        <img src="{{ $testimonial['avatar'] }}"
                                             class="rounded-circle me-3"
                                             width="50" height="50"
                                             alt="{{ $testimonial['name'] ?? 'Customer' }}">
                                    @endif
                                    <div>
                                        <strong>{{ $testimonial['name'] ?? 'John Doe' }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $testimonial['position'] ?? 'Customer' }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</section>

<style>
.testimonial-card {
    border: none;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.testimonial-card:hover {
    transform: translateY(-5px);
}

.testimonial-text {
    font-style: italic;
    color: #555;
    line-height: 1.6;
}
</style>
