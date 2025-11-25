@extends('page-builder.admin.layouts.app')

@section('title', 'Edit Page - ' . $page->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Edit Page</h1>
    <div>
        <a href="{{ route('admin.pages.builder', $page->id) }}" class="btn btn-primary me-2">
            <i class="fas fa-edit"></i> Page Builder
        </a>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Pages
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow">
            <div class="card-body">
                <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="title" class="form-label">Page Title *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title', $page->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug *</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror"
                               id="slug" name="slug" value="{{ old('slug', $page->slug) }}" required>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            The slug is used in the URL: {{ url('/') }}/<strong>{{ $page->slug }}</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                  id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $page->meta_description) }}</textarea>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Optional meta description for SEO. Recommended length: 150-160 characters.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="layout" class="form-label">Layout *</label>
                        <select class="form-select @error('layout') is-invalid @enderror"
                                id="layout" name="layout" required>
                            <option value="default" {{ old('layout', $page->layout) == 'default' ? 'selected' : '' }}>Default</option>
                            <option value="full-width" {{ old('layout', $page->layout) == 'full-width' ? 'selected' : '' }}>Full Width</option>
                            <option value="sidebar-left" {{ old('layout', $page->layout) == 'sidebar-left' ? 'selected' : '' }}>Sidebar Left</option>
                            <option value="sidebar-right" {{ old('layout', $page->layout) == 'sidebar-right' ? 'selected' : '' }}>Sidebar Right</option>
                        </select>
                        @error('layout')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" id="is_published" name="is_published"
                               {{ old('is_published', $page->is_published) ? 'checked' : '' }} value="1">
                        <label class="form-check-label" for="is_published">
                            Publish this page
                        </label>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Page
                        </button>

                        <button type="button" class="btn btn-outline-danger"
                                onclick="if(confirm('Are you sure you want to delete this page? This action cannot be undone.')) { document.getElementById('delete-form').submit(); }">
                            <i class="fas fa-trash"></i> Delete Page
                        </button>
                    </div>
                </form>

                <!-- Delete Form -->
                <form id="delete-form" action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>

        <!-- Page Preview Card -->
        <div class="card shadow mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-eye me-2"></i>Page Preview
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Live Page</h6>
                        <p class="text-muted">View the published version of this page</p>
                        <a href="{{ route('pages.show', $page->slug) }}"
                           class="btn btn-outline-primary btn-sm" target="_blank">
                            <i class="fas fa-external-link-alt me-1"></i> View Live Page
                        </a>
                    </div>
                    <div class="col-md-6">
                        <h6>Builder Preview</h6>
                        <p class="text-muted">Edit page sections and content</p>
                        <a href="{{ route('admin.pages.builder', $page->id) }}"
                           class="btn btn-outline-success btn-sm">
                            <i class="fas fa-edit me-1"></i> Open Page Builder
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Page Information Card -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>Page Information
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Page ID</label>
                    <p class="form-control-plaintext text-muted">{{ $page->id }}</p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Current Status</label>
                    <p>
                        <span class="badge {{ $page->is_published ? 'bg-success' : 'bg-secondary' }}">
                            {{ $page->is_published ? 'Published' : 'Draft' }}
                        </span>
                    </p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Sections</label>
                    <p class="form-control-plaintext">
                        <span class="badge bg-info">
                            {{ $page->sections->count() }} section(s)
                        </span>
                    </p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Active Sections</label>
                    <p class="form-control-plaintext">
                        <span class="badge bg-success">
                            {{ $page->activeSections->count() }} active
                        </span>
                    </p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Created</label>
                    <p class="form-control-plaintext text-muted">
                        <i class="fas fa-calendar me-1"></i>
                        {{ $page->created_at->format('M d, Y') }}
                        <small class="d-block text-muted">{{ $page->created_at->format('H:i A') }}</small>
                    </p>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Last Updated</label>
                    <p class="form-control-plaintext text-muted">
                        <i class="fas fa-clock me-1"></i>
                        {{ $page->updated_at->format('M d, Y') }}
                        <small class="d-block text-muted">{{ $page->updated_at->format('H:i A') }}</small>
                    </p>
                </div>

                <!-- Quick Actions -->
                <div class="mt-4 pt-3 border-top">
                    <h6 class="fw-semibold mb-3">Quick Actions</h6>
                    <div class="d-grid gap-2">
                        @if($page->is_published)
                            <form action="{{ route('admin.pages.unpublish', $page->id) }}" method="POST" class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="fas fa-eye-slash me-1"></i> Unpublish Page
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.pages.publish', $page->id) }}" method="POST" class="d-grid">
                                @csrf
                                <button type="submit" class="btn btn-success btn-sm">
                                    <i class="fas fa-eye me-1"></i> Publish Page
                                </button>
                            </form>
                        @endif

                        <a href="{{ route('admin.pages.builder', $page->id) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-plus me-1"></i> Add Sections
                        </a>

                        <a href="{{ route('pages.show', $page->slug) }}"
                           class="btn btn-outline-info btn-sm" target="_blank">
                            <i class="fas fa-external-link-alt me-1"></i> Preview Page
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Summary Card -->
        @if($page->sections->count() > 0)
        <div class="card shadow mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-layer-group me-2"></i>Section Summary
                </h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    @foreach($page->sections->sortBy('order') as $section)
                        <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                            <div>
                                <i class="fas fa-{{ $section->getIcon() }} text-primary me-2"></i>
                                <span class="fw-medium">{{ $section->getTypeName() }}</span>
                                @if(!$section->is_active)
                                    <span class="badge bg-secondary ms-1">Inactive</span>
                                @endif
                            </div>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.sections.edit', $section->id) }}"
                                   class="btn btn-outline-primary"
                                   target="_blank"
                                   title="Edit Section">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.sections.destroy', $section->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Delete this section?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" title="Delete Section">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3 text-center">
                    <a href="{{ route('admin.pages.builder', $page->id) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-edit me-1"></i> Manage All Sections
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-control-plaintext {
        padding: 0.375rem 0;
        margin-bottom: 0;
        background-color: transparent;
        border: solid transparent;
        border-width: 1px 0;
    }

    .list-group-item {
        border: none;
        border-bottom: 1px solid #e9ecef;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }
</style>
@endpush

@push('scripts')
<script>
    // Auto-generate slug from title if empty
    document.getElementById('title').addEventListener('input', function() {
        const slugField = document.getElementById('slug');
        const currentSlug = '{{ $page->slug }}';

        // Only auto-generate if the slug field is empty or matches the old title
        if (!slugField.value || slugField.value === currentSlug) {
            slugField.value = this.value.toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }
    });

    // Character counter for meta description
    const metaDesc = document.getElementById('meta_description');
    const charCount = document.createElement('div');
    charCount.className = 'form-text text-end';
    metaDesc.parentNode.appendChild(charCount);

    function updateCharCount() {
        const length = metaDesc.value.length;
        charCount.textContent = `${length} characters`;

        if (length > 160) {
            charCount.classList.add('text-warning');
            charCount.classList.remove('text-success');
        } else if (length >= 150) {
            charCount.classList.add('text-success');
            charCount.classList.remove('text-warning');
        } else {
            charCount.classList.remove('text-success', 'text-warning');
        }
    }

    metaDesc.addEventListener('input', updateCharCount);
    updateCharCount(); // Initialize count

    // Confirm before leaving if there are unsaved changes
    let formChanged = false;
    const form = document.querySelector('form');
    const initialFormData = new FormData(form);

    form.addEventListener('change', () => {
        formChanged = true;
    });

    form.addEventListener('submit', () => {
        formChanged = false;
    });

    window.addEventListener('beforeunload', (e) => {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = 'You have unsaved changes. Are you sure you want to leave?';
        }
    });
</script>
@endpush
