{{-- resources/views/page-builder/sections/features.blade.php --}}
<section class="features-section py-5">
    <div class="container">
        @if(isset($content['title']) || isset($content['description']))
            <div class="text-center mb-5">
                @if(isset($content['title']))
                    <h2 class="section-title">{{ $content['title'] }}</h2>
                @endif
                @if(isset($content['description']))
                    <p class="section-description text-muted">{{ $content['description'] }}</p>
                @endif
            </div>
        @endif

        @if(isset($content['features']) && is_array($content['features']))
            <div class="row">
                @foreach($content['features'] as $feature)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="feature-card text-center p-4 h-100">
                            @if(isset($feature['icon']))
                                <div class="feature-icon mb-3">
                                    <i class="{{ $feature['icon'] }} fa-2x text-primary"></i>
                                </div>
                            @endif
                            <h4 class="feature-title">{{ $feature['title'] ?? 'Feature Title' }}</h4>
                            <p class="feature-description text-muted">
                                {{ $feature['description'] ?? 'Feature description goes here.' }}
                            </p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card text-center p-4 h-100">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-rocket fa-2x text-primary"></i>
                        </div>
                        <h4 class="feature-title">Fast & Reliable</h4>
                        <p class="feature-description text-muted">
                            Lightning fast performance with reliable infrastructure.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card text-center p-4 h-100">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-shield-alt fa-2x text-primary"></i>
                        </div>
                        <h4 class="feature-title">Secure</h4>
                        <p class="feature-description text-muted">
                            Enterprise-grade security for your peace of mind.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="feature-card text-center p-4 h-100">
                        <div class="feature-icon mb-3">
                            <i class="fas fa-cogs fa-2x text-primary"></i>
                        </div>
                        <h4 class="feature-title">Customizable</h4>
                        <p class="feature-description text-muted">
                            Fully customizable to fit your specific needs.
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<style>
.feature-card {
    border: 1px solid #e9ecef;
    border-radius: 10px;
    transition: all 0.3s ease;
    background: white;
}

.feature-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    border-color: #007bff;
}

.feature-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.section-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.section-description {
    font-size: 1.1rem;
    max-width: 600px;
    margin: 0 auto;
}
</style>
