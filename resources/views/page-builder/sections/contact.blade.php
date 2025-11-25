{{-- resources/views/page-builder/sections/contact.blade.php --}}
<section class="contact-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                @if(isset($content['title']))
                    <h2 class="section-title">{{ $content['title'] }}</h2>
                @endif
                @if(isset($content['description']))
                    <p class="text-muted mb-4">{{ $content['description'] }}</p>
                @endif

                @if(isset($content['contact_info']) && is_array($content['contact_info']))
                    <div class="contact-info">
                        @foreach($content['contact_info'] as $info)
                            <div class="contact-item d-flex align-items-center mb-3">
                                @if(isset($info['icon']))
                                    <i class="{{ $info['icon'] }} fa-lg text-primary me-3"></i>
                                @endif
                                <div>
                                    <strong>{{ $info['label'] ?? '' }}</strong>
                                    <div>{{ $info['value'] ?? '' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="col-lg-6">
                <div class="contact-form">
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
