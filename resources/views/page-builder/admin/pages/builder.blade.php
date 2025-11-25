@extends('page-builder.admin.layouts.app')

@section('title', 'Page Builder - ' . $page->title)

@section('content')
<div class="page-builder-container">
    <!-- Components Panel -->
    <div class="components-panel">
        <h5 class="p-3 border-bottom mb-0">Page Builder</h5>
        <div class="p-3 border-bottom">
            <h6 class="text-muted mb-2">Page: {{ $page->title }}</h6>
            <div class="d-flex gap-2 mb-3">
                <a href="{{ route('pages.show', $page->slug) }}"
                   class="btn btn-sm btn-outline-primary w-100" target="_blank">
                    <i class="fas fa-eye"></i> Preview
                </a>
                <a href="{{ route('admin.pages.edit', $page->id) }}"
                   class="btn btn-sm btn-outline-secondary w-100">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </div>
        </div>

        <h6 class="p-3 border-bottom mb-0">Add Sections</h6>
        <div class="p-3">
            @foreach($sectionTypes as $type => $info)
                <div class="component-item" data-type="{{ $type }}" draggable="true">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-{{ $pageBuilder->getSectionIcon($type) }} me-3 text-primary"></i>
                        <div>
                            <div class="fw-medium">{{ $info['name'] }}</div>
                            <small class="text-muted">{{ Str::limit($info['description'] ?? 'Add this section to your page', 50) }}</small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Builder Toolbar -->
    <div class="builder-toolbar">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">{{ $page->title }}</h4>
                <small class="text-muted">
                    <span class="badge {{ $page->is_published ? 'bg-success' : 'bg-secondary' }}">
                        {{ $page->is_published ? 'Published' : 'Draft' }}
                    </span>
                    • Last updated: {{ $page->updated_at->format('M d, Y H:i') }}
                </small>
            </div>
            <div>
                <button class="btn btn-success me-2" id="savePage">
                    <i class="fas fa-save"></i> Save Page
                </button>
                @if($page->is_published)
                    <form action="{{ route('admin.pages.unpublish', $page->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning me-2">
                            <i class="fas fa-eye-slash"></i> Unpublish
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.pages.publish', $page->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-eye"></i> Publish
                        </button>
                    </form>
                @endif
                <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-dark">
                    <i class="fas fa-times"></i> Exit
                </a>
            </div>
        </div>
    </div>

    <!-- Preview Area -->
    <div class="preview-area">
        <div id="sections-container" class="sections-wrapper">
            @foreach($page->sections()->orderBy('order')->get() as $section)
                <div class="builder-section" data-section-id="{{ $section->id }}">
                    <div class="section-header">
                        <div class="section-handle">
                            <i class="fas fa-bars"></i>
                        </div>
                        <div class="section-title">
                            <i class="fas fa-{{ $pageBuilder->getSectionIcon($section->type) }} me-2"></i>
                            {{ $sectionTypes[$section->type]['name'] ?? $section->type }}
                            @if(!$section->is_active)
                                <span class="badge bg-secondary ms-2">Inactive</span>
                            @endif
                        </div>
                        <div class="section-actions">
                            <button class="btn btn-sm btn-outline-primary edit-section"
                                    data-section-id="{{ $section->id }}"
                                    title="Edit Section">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-success duplicate-section"
                                    data-section-id="{{ $section->id }}"
                                    title="Duplicate Section">
                                <i class="fas fa-copy"></i>
                            </button>
                            <form class="d-inline toggle-section-form"
                                  action="{{ route('admin.sections.toggle-active', $section->id) }}"
                                  method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $section->is_active ? 'btn-warning' : 'btn-outline-warning' }}"
                                        title="{{ $section->is_active ? 'Deactivate' : 'Activate' }}">
                                    <i class="fas fa-power-off"></i>
                                </button>
                            </form>
                            <form class="d-inline delete-section-form"
                                  action="{{ route('admin.sections.destroy', $section->id) }}"
                                  method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Are you sure?')"
                                        title="Delete Section">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="section-content">
                        {!! $pageBuilder->renderSection($section) !!}
                    </div>
                </div>
            @endforeach

            <!-- Empty State -->
            @if($page->sections->isEmpty())
                <div class="empty-state text-center py-5">
                    <div class="empty-icon mb-4">
                        <i class="fas fa-plus-circle fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted">No Sections Added Yet</h4>
                    <p class="text-muted mb-4">Drag components from the left panel or click the button below to start building your page.</p>
                    <button class="btn btn-primary btn-lg" id="addFirstSection">
                        <i class="fas fa-plus"></i> Add Your First Section
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Section Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row" id="section-types-grid">
                    @foreach($sectionTypes as $type => $info)
                        <div class="col-md-6 mb-3">
                            <div class="card section-type-card h-100" data-type="{{ $type }}">
                                <div class="card-body text-center">
                                    <div class="section-icon mb-3">
                                        <i class="fas fa-{{ $pageBuilder->getSectionIcon($type) }} fa-3x text-primary"></i>
                                    </div>
                                    <h5 class="card-title">{{ $info['name'] }}</h5>
                                    <p class="card-text text-muted small">
                                        {{ $info['description'] ?? 'Add this section to your page' }}
                                    </p>
                                </div>
                                <div class="card-footer bg-transparent">
                                    <button type="button" class="btn btn-primary w-100 add-section-btn"
                                            data-type="{{ $type }}">
                                        <i class="fas fa-plus"></i> Add Section
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Spinner -->
<div class="modal fade" id="loadingModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mb-0">Processing...</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.page-builder-container {
    min-height: 100vh;
    background: #f8f9fa;
    display: flex;
}

.components-panel {
    background: white;
    border-right: 1px solid #dee2e6;
    height: 100vh;
    overflow-y: auto;
    position: fixed;
    width: 320px;
    left: 0;
    top: 0;
    padding-top: 60px;
    z-index: 1000;
}

.builder-toolbar {
    position: fixed;
    top: 0;
    left: 320px;
    right: 0;
    background: white;
    border-bottom: 1px solid #dee2e6;
    z-index: 999;
    padding: 15px 25px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.preview-area {
    margin-left: 320px;
    padding: 30px;
    padding-top: 90px;
    width: calc(100% - 320px);
}

.component-item {
    padding: 12px;
    border: 1px solid #e9ecef;
    margin-bottom: 10px;
    border-radius: 8px;
    cursor: grab;
    background: white;
    transition: all 0.3s ease;
}

.component-item:hover {
    background: #f8f9fa;
    border-color: #007bff;
    transform: translateY(-2px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.component-item:active {
    cursor: grabbing;
}

.builder-section {
    border: 2px dashed transparent;
    border-radius: 12px;
    margin-bottom: 25px;
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    position: relative;
}

.builder-section:hover {
    border-color: #007bff;
    box-shadow: 0 4px 15px rgba(0,0,0,0.15);
}

.builder-section.sortable-ghost {
    opacity: 0.4;
    background: #f8f9fa;
}

.builder-section.sortable-chosen {
    transform: rotate(2deg);
}

.section-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 15px 20px;
    border-bottom: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-radius: 12px 12px 0 0;
}

.section-handle {
    cursor: move;
    padding: 8px 12px;
    color: #6c757d;
    background: white;
    border-radius: 6px;
    border: 1px solid #dee2e6;
}

.section-handle:hover {
    background: #f8f9fa;
}

.section-title {
    font-weight: 600;
    color: #495057;
    flex-grow: 1;
    margin: 0 15px;
}

.section-actions .btn {
    margin-left: 5px;
    border-radius: 6px;
}

.section-content {
    padding: 25px;
    min-height: 100px;
}

.empty-state {
    background: white;
    border-radius: 12px;
    padding: 60px 40px;
    border: 2px dashed #dee2e6;
}

.empty-icon {
    opacity: 0.7;
}

.section-type-card {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.section-type-card:hover {
    transform: translateY(-5px);
    border-color: #007bff;
    box-shadow: 0 5px 20px rgba(0,0,0,0.1);
}

.section-icon {
    opacity: 0.8;
}

.sections-wrapper {
    max-width: 1200px;
    margin: 0 auto;
}

/* Drag and drop styles */
.sections-wrapper.sortable-drag {
    opacity: 0.8;
    transform: rotate(5deg);
}

/* Responsive design */
@media (max-width: 768px) {
    .components-panel {
        width: 280px;
    }

    .preview-area {
        margin-left: 280px;
        width: calc(100% - 280px);
        padding: 15px;
        padding-top: 80px;
    }

    .builder-toolbar {
        left: 280px;
        padding: 10px 15px;
    }

    .section-header {
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }

    .section-title {
        margin: 10px 0;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
class PageBuilderEditor {
    constructor() {
        this.pageId = {{ $page->id }};
        this.csrfToken = '{{ csrf_token() }}';
        this.init();
    }

    init() {
        this.initSortable();
        this.initComponentDrag();
        this.initSectionActions();
        this.initAddSectionModal();
        this.initSaveButton();
        this.initFirstSectionButton();
    }

    initSortable() {
        this.sortable = new Sortable(document.getElementById('sections-container'), {
            handle: '.section-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onEnd: (evt) => {
                this.updateSectionOrder();
            }
        });
    }

    initComponentDrag() {
        // Drag from components panel
        document.querySelectorAll('.component-item').forEach(item => {
            item.addEventListener('dragstart', (e) => {
                e.dataTransfer.setData('text/plain', e.target.dataset.type);
                e.target.classList.add('dragging');
            });

            item.addEventListener('dragend', (e) => {
                e.target.classList.remove('dragging');
            });
        });

        // Drop zone
        const sectionsContainer = document.getElementById('sections-container');

        sectionsContainer.addEventListener('dragover', (e) => {
            e.preventDefault();
            sectionsContainer.classList.add('drag-over');
        });

        sectionsContainer.addEventListener('dragleave', (e) => {
            if (!sectionsContainer.contains(e.relatedTarget)) {
                sectionsContainer.classList.remove('drag-over');
            }
        });

        sectionsContainer.addEventListener('drop', (e) => {
            e.preventDefault();
            sectionsContainer.classList.remove('drag-over');
            const sectionType = e.dataTransfer.getData('text/plain');
            if (sectionType) {
                this.addNewSection(sectionType);
            }
        });
    }

    async addNewSection(type) {
        this.showLoading();

        try {
            const response = await fetch('{{ route("admin.sections.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    page_id: this.pageId,
                    type: type
                })
            });

            const data = await response.json();

            if (data.success) {
                // Reload the page to show the new section
                window.location.reload();
            } else {
                throw new Error('Failed to add section');
            }
        } catch (error) {
            console.error('Error adding section:', error);
            this.showError('Failed to add section. Please try again.');
        } finally {
            this.hideLoading();
        }
    }

    initSectionActions() {
        // Edit section
        document.addEventListener('click', (e) => {
            if (e.target.closest('.edit-section')) {
                const sectionId = e.target.closest('.edit-section').dataset.sectionId;
                this.editSection(sectionId);
            }

            if (e.target.closest('.duplicate-section')) {
                const sectionId = e.target.closest('.duplicate-section').dataset.sectionId;
                this.duplicateSection(sectionId);
            }
        });

        // Toggle section active status
        document.querySelectorAll('.toggle-section-form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                this.toggleSectionActive(form);
            });
        });

        // Delete section with confirmation
        document.querySelectorAll('.delete-section-form').forEach(form => {
            form.addEventListener('submit', (e) => {
                if (!confirm('Are you sure you want to delete this section? This action cannot be undone.')) {
                    e.preventDefault();
                }
            });
        });
    }

    editSection(sectionId) {
        window.open(`/admin/sections/${sectionId}/edit`, 'Section Editor', 'width=1000,height=800');
    }

    async duplicateSection(sectionId) {
        this.showLoading();

        try {
            const response = await fetch(`/admin/sections/${sectionId}/duplicate`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                window.location.reload();
            } else {
                throw new Error('Failed to duplicate section');
            }
        } catch (error) {
            console.error('Error duplicating section:', error);
            this.showError('Failed to duplicate section. Please try again.');
        } finally {
            this.hideLoading();
        }
    }

    async toggleSectionActive(form) {
        this.showLoading();

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.csrfToken
                }
            });

            const data = await response.json();

            if (data.success) {
                window.location.reload();
            } else {
                throw new Error('Failed to toggle section');
            }
        } catch (error) {
            console.error('Error toggling section:', error);
            this.showError('Failed to update section. Please try again.');
        } finally {
            this.hideLoading();
        }
    }

    initAddSectionModal() {
        const modal = new bootstrap.Modal(document.getElementById('addSectionModal'));

        // Show modal when clicking "Add First Section"
        document.getElementById('addFirstSection')?.addEventListener('click', () => {
            modal.show();
        });

        // Add section from modal
        document.querySelectorAll('.add-section-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const type = e.target.dataset.type;
                modal.hide();
                this.addNewSection(type);
            });
        });

        // Also allow clicking the entire card
        document.querySelectorAll('.section-type-card').forEach(card => {
            card.addEventListener('click', (e) => {
                if (!e.target.closest('.add-section-btn')) {
                    const type = card.dataset.type;
                    modal.hide();
                    this.addNewSection(type);
                }
            });
        });
    }

    initSaveButton() {
        document.getElementById('savePage')?.addEventListener('click', () => {
            this.savePage();
        });
    }

    initFirstSectionButton() {
        document.getElementById('addFirstSection')?.addEventListener('click', () => {
            const modal = new bootstrap.Modal(document.getElementById('addSectionModal'));
            modal.show();
        });
    }

    async updateSectionOrder() {
        const sections = Array.from(document.querySelectorAll('.builder-section'));
        const orderData = sections.map((section, index) => ({
            id: section.dataset.sectionId,
            order: index
        }));

        try {
            await fetch('{{ route("admin.sections.reorder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.csrfToken
                },
                body: JSON.stringify({
                    page_id: this.pageId,
                    sections: orderData.map(item => item.id)
                })
            });
        } catch (error) {
            console.error('Error updating section order:', error);
            this.showError('Failed to update section order. Please try again.');
        }
    }

    async savePage() {
        this.showLoading();

        try {
            // Update section order first
            await this.updateSectionOrder();

            // Show success message
            this.showSuccess('Page saved successfully!');
        } catch (error) {
            console.error('Error saving page:', error);
            this.showError('Failed to save page. Please try again.');
        } finally {
            this.hideLoading();
        }
    }

    showLoading() {
        const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
        loadingModal.show();
    }

    hideLoading() {
        const loadingModal = bootstrap.Modal.getInstance(document.getElementById('loadingModal'));
        if (loadingModal) {
            loadingModal.hide();
        }
    }

    showSuccess(message) {
        this.showAlert(message, 'success');
    }

    showError(message) {
        this.showAlert(message, 'danger');
    }

    showAlert(message, type) {
        const alert = document.createElement('div');
        alert.className = `alert alert-${type} alert-dismissible fade show`;
        alert.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        document.querySelector('.admin-content').prepend(alert);

        setTimeout(() => {
            alert.remove();
        }, 5000);
    }
}

// Initialize editor when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new PageBuilderEditor();
});
</script>
@endpush
