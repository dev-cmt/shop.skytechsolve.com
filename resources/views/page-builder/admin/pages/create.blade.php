@extends('page-builder.admin.layouts.app')

@section('title', 'Create New Page')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Create New Page</h1>
    <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Back to Pages
    </a>
</div>

<div class="card shadow">
    <div class="card-body">
        <form action="{{ route('admin.pages.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label for="title" class="form-label">Page Title *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror"
                               id="slug" name="slug" value="{{ old('slug') }}" placeholder="Will be auto-generated if empty">
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror"
                                  id="meta_description" name="meta_description" rows="3">{{ old('meta_description') }}</textarea>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Page Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="layout" class="form-label">Layout *</label>
                                <select class="form-select @error('layout') is-invalid @enderror"
                                        id="layout" name="layout" required>
                                    <option value="default" {{ old('layout') == 'default' ? 'selected' : '' }}>Default</option>
                                    <option value="full-width" {{ old('layout') == 'full-width' ? 'selected' : '' }}>Full Width</option>
                                    <option value="sidebar-left" {{ old('layout') == 'sidebar-left' ? 'selected' : '' }}>Sidebar Left</option>
                                    <option value="sidebar-right" {{ old('layout') == 'sidebar-right' ? 'selected' : '' }}>Sidebar Right</option>
                                </select>
                                @error('layout')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="is_published" name="is_published"
                                       {{ old('is_published') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_published">
                                    Publish immediately
                                </label>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Create Page
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // Auto-generate slug from title
    document.getElementById('title').addEventListener('input', function() {
        const slugField = document.getElementById('slug');
        if (!slugField.value) {
            slugField.value = this.value.toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
        }
    });
</script>
@endpush
@endsection
