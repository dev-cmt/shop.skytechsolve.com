@extends('page-builder.admin.layouts.app')

@section('title', 'Edit Section - ' . ($sectionTypes[$section->type]['name'] ?? $section->type))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">
        Edit {{ $sectionTypes[$section->type]['name'] ?? $section->type }}
    </h1>
    <div>
        <a href="{{ route('admin.pages.builder', $section->page_id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Builder
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Section Content</h5>
            </div>
            <div class="card-body">
                <form id="sectionForm" method="POST"
                      action="{{ route('admin.sections.update', $section->id) }}">
                    @csrf
                    @method('PUT')

                    <div id="sectionFields">
                        <!-- Dynamic fields will be loaded here -->
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Section
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="previewSection()">
                            <i class="fas fa-eye"></i> Preview
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Section Settings -->
        <div class="card shadow">
            <div class="card-header">
                <h5 class="mb-0">Section Settings</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Section Type</label>
                    <p class="form-control-plaintext">
                        <i class="fas fa-{{ $pageBuilder->getSectionIcon($section->type) }}"></i>
                        {{ $sectionTypes[$section->type]['name'] ?? $section->type }}
                    </p>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                               {{ $section->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active Section
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Section ID</label>
                    <p class="form-control-plaintext text-muted">
                        {{ $section->id }}
                    </p>
                </div>

                <div class="mb-3">
                    <label class="form-label">Created</label>
                    <p class="form-control-plaintext text-muted">
                        {{ $section->created_at->format('M d, Y H:i') }}
                    </p>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-warning" onclick="duplicateSection()">
                        <i class="fas fa-copy"></i> Duplicate Section
                    </button>
                    <button type="button" class="btn btn-outline-danger" onclick="deleteSection()">
                        <i class="fas fa-trash"></i> Delete Section
                    </button>
                </div>
            </div>
        </div>

        <!-- Live Preview -->
        <div class="card shadow mt-4">
            <div class="card-header">
                <h5 class="mb-0">Live Preview</h5>
            </div>
            <div class="card-body">
                <div id="sectionPreview" style="min-height: 200px; border: 1px dashed #dee2e6; padding: 15px;">
                    <p class="text-muted text-center mb-0">Preview will appear here</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Section Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalPreviewContent">
                <!-- Preview content will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<style>
    .field-group {
        margin-bottom: 1.5rem;
        padding: 1rem;
        border: 1px solid #e9ecef;
        border-radius: 0.375rem;
    }

    .field-group h6 {
        color: #495057;
        margin-bottom: 1rem;
    }

    .repeater-item {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-bottom: 1rem;
        background: #f8f9fa;
    }

    .repeater-item .item-header {
        display: flex;
        justify-content: between;
        align-items: center;
        margin-bottom: 1rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    const sectionData = @json($section->content);
    const sectionType = @json($section->type);
    const sectionId = @json($section->id);

    // Field templates
    const fieldTemplates = {
        text: (key, value = '', label = '') => `
            <div class="mb-3">
                <label class="form-label">${label || key}</label>
                <input type="text" class="form-control" name="content[${key}]" value="${value}">
            </div>
        `,

        textarea: (key, value = '', label = '') => `
            <div class="mb-3">
                <label class="form-label">${label || key}</label>
                <textarea class="form-control" name="content[${key}]" rows="3">${value}</textarea>
            </div>
        `,

        wysiwyg: (key, value = '', label = '') => `
            <div class="mb-3">
                <label class="form-label">${label || key}</label>
                <textarea class="form-control summernote" name="content[${key}]">${value}</textarea>
            </div>
        `,

        number: (key, value = '', label = '') => `
            <div class="mb-3">
                <label class="form-label">${label || key}</label>
                <input type="number" class="form-control" name="content[${key}]" value="${value}">
            </div>
        `,

        checkbox: (key, value = false, label = '') => `
            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="content[${key}]"
                           ${value ? 'checked' : ''}>
                    <label class="form-check-label">${label || key}</label>
                </div>
            </div>
        `,

        select: (key, value = '', options = '', label = '') => {
            const optionList = options.split(',').map(opt => {
                const [optValue, optLabel] = opt.split(':');
                const selected = optValue === value ? 'selected' : '';
                return `<option value="${optValue}" ${selected}>${optLabel || optValue}</option>`;
            }).join('');

            return `
                <div class="mb-3">
                    <label class="form-label">${label || key}</label>
                    <select class="form-select" name="content[${key}]">
                        ${optionList}
                    </select>
                </div>
            `;
        },

        image: (key, value = '', label = '') => `
            <div class="mb-3">
                <label class="form-label">${label || key}</label>
                <div class="d-flex gap-2">
                    <input type="text" class="form-control" name="content[${key}]"
                           value="${value}" placeholder="Image URL">
                    <button type="button" class="btn btn-outline-secondary" onclick="browseImage('${key}')">
                        <i class="fas fa-folder-open"></i>
                    </button>
                </div>
                ${value ? `<img src="${value}" class="img-thumbnail mt-2" style="max-height: 100px;">` : ''}
            </div>
        `,

        color: (key, value = '', label = '') => `
            <div class="mb-3">
                <label class="form-label">${label || key}</label>
                <input type="color" class="form-control form-control-color"
                       name="content[${key}]" value="${value || '#007bff'}">
            </div>
        `
    };

    // Initialize form
    document.addEventListener('DOMContentLoaded', function() {
        loadSectionFields();
        initializeSummernote();
        updatePreview();
    });

    function loadSectionFields() {
        const container = document.getElementById('sectionFields');
        const fieldConfig = @json($pageBuilder->getFieldConfig($section->type));

        Object.entries(fieldConfig).forEach(([fieldKey, fieldType]) => {
            const fieldValue = sectionData[fieldKey] ?? '';
            const [type, ...options] = fieldType.split(':');

            if (type === 'repeater') {
                renderRepeaterField(fieldKey, fieldValue);
            } else {
                const template = fieldTemplates[type];
                if (template) {
                    container.innerHTML += template(fieldKey, fieldValue, formatLabel(fieldKey));
                }
            }
        });

        // Re-initialize summernote for dynamically added fields
        initializeSummernote();
    }

    function formatLabel(key) {
        return key.split('_').map(word =>
            word.charAt(0).toUpperCase() + word.slice(1)
        ).join(' ');
    }

    function renderRepeaterField(fieldKey, items) {
        const container = document.getElementById('sectionFields');
        const itemContainerId = `repeater-${fieldKey}`;

        container.innerHTML += `
            <div class="field-group">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">${formatLabel(fieldKey)}</h6>
                    <button type="button" class="btn btn-sm btn-primary"
                            onclick="addRepeaterItem('${fieldKey}')">
                        <i class="fas fa-plus"></i> Add Item
                    </button>
                </div>
                <div id="${itemContainerId}">
                    ${(items || []).map((item, index) => renderRepeaterItem(fieldKey, item, index)).join('')}
                </div>
            </div>
        `;
    }

    function renderRepeaterItem(fieldKey, item = {}, index) {
        const itemId = `item-${fieldKey}-${index}`;
        return `
            <div class="repeater-item" id="${itemId}">
                <div class="item-header">
                    <strong>Item ${index + 1}</strong>
                    <button type="button" class="btn btn-sm btn-danger"
                            onclick="removeRepeaterItem('${itemId}')">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="item-fields">
                    ${Object.entries(item).map(([key, value]) => `
                        <div class="mb-2">
                            <label class="form-label small">${formatLabel(key)}</label>
                            <input type="text" class="form-control form-control-sm"
                                   name="content[${fieldKey}][${index}][${key}]"
                                   value="${value}">
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }

    function addRepeaterItem(fieldKey) {
        const container = document.getElementById(`repeater-${fieldKey}`);
        const index = container.children.length;
        container.innerHTML += renderRepeaterItem(fieldKey, {}, index);
    }

    function removeRepeaterItem(itemId) {
        document.getElementById(itemId).remove();
    }

    function initializeSummernote() {
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['fontsize', ['fontsize']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['insert', ['link', 'picture', 'video']],
            ]
        });
    }

    function updatePreview() {
        const formData = new FormData(document.getElementById('sectionForm'));
        const content = {};

        for (let [key, value] of formData.entries()) {
            if (key.startsWith('content[')) {
                const fieldKey = key.match(/content\[(.*?)\]/)[1];
                content[fieldKey] = value;
            }
        }

        // Simulate preview update (in real app, this would make an AJAX call)
        const preview = document.getElementById('sectionPreview');
        preview.innerHTML = `
            <div class="text-center">
                <i class="fas fa-sync fa-spin"></i>
                <p class="mt-2">Updating preview...</p>
            </div>
        `;
    }

    function previewSection() {
        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        const previewContent = document.getElementById('modalPreviewContent');

        previewContent.innerHTML = `
            <div class="text-center py-4">
                <i class="fas fa-sync fa-spin fa-2x"></i>
                <p class="mt-2">Loading preview...</p>
            </div>
        `;

        modal.show();

        // Simulate AJAX call for preview
        setTimeout(() => {
            previewContent.innerHTML = `
                <div class="alert alert-info">
                    <p>Full preview would be implemented with actual section rendering.</p>
                    <p>In a real application, this would make an AJAX call to render the section with current data.</p>
                </div>
            `;
        }, 1000);
    }

    function duplicateSection() {
        if (confirm('Duplicate this section?')) {
            fetch(`/admin/sections/${sectionId}/duplicate`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '/admin/pages/{{ $section->page_id }}/builder';
                }
            });
        }
    }

    function deleteSection() {
        if (confirm('Are you sure you want to delete this section?')) {
            fetch(`/admin/sections/${sectionId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = '/admin/pages/{{ $section->page_id }}/builder';
                }
            });
        }
    }

    // Auto-update preview on input change
    document.addEventListener('input', function(e) {
        if (e.target.name && e.target.name.startsWith('content[')) {
            setTimeout(updatePreview, 500);
        }
    });
</script>
@endpush
