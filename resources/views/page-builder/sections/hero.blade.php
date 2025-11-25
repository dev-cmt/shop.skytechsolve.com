{{-- resources/views/page-builder/sections/hero.blade.php --}}
<section class="hero-section"
    @if(isset($content['background_image']) && $content['background_image'])
        style="background-image: url('{{ asset($content['background_image']) }}')"
    @else
        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)"
    @endif>
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">{{ $content['title'] ?? 'Welcome to Our Website' }}</h1>
            <p class="hero-subtitle">{{ $content['subtitle'] ?? 'Create amazing experiences with our page builder' }}</p>

            @if(isset($content['buttons']) && is_array($content['buttons']))
                <div class="hero-buttons">
                    @foreach($content['buttons'] as $button)
                        <a href="{{ $button['url'] ?? '#' }}"
                           class="btn {{ $button['style'] ?? 'btn-primary' }} btn-lg">
                            {{ $button['text'] ?? 'Learn More' }}
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>

<style>
.hero-section {
    padding: 120px 0;
    color: white;
    text-align: center;
    position: relative;
}

.hero-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.4);
}

.hero-content {
    position: relative;
    z-index: 2;
}

.hero-title {
    font-size: 3.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.hero-subtitle {
    font-size: 1.3rem;
    margin-bottom: 2rem;
    opacity: 0.9;
}

.hero-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }

    .hero-subtitle {
        font-size: 1.1rem;
    }
}
</style>
