<x-backend-layout title="Category">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Categories</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Categories</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Categories Table -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Categories List</div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                        <i class="ri-add-line me-1"></i>Add Category
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table text-nowrap align-middle">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Image</th>
                                    <th>Category Name</th>
                                    <th>Parent</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 1;
                                    $renderCategory = function($category, $level = 0) use (&$i, &$renderCategory, $data) {
                                        $padding = $level * 20;
                                        $parentName = $category->parent ? $category->parent->name : '-';
                                @endphp
                                        <tr>
                                            <td>{{ $i++ }}</td>
                                            <td>
                                                @if($category->image)
                                                    <img src="{{ asset($category->image) }}" width="50" class="img-thumbnail">
                                                @else
                                                    <span class="badge bg-secondary">No Image</span>
                                                @endif
                                            </td>
                                            <td style="padding-left: {{ $padding }}px;">
                                                {{ $level > 0 ? '↳ ' : '' }}{{ $category->name }}
                                            </td>
                                            <td>{{ $parentName }}</td>
                                            <td>
                                                <span class="badge bg-{{ $category->status ? 'success' : 'danger' }}">
                                                    {{ $category->status ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-light edit_cat_btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editCategoryModal"
                                                        data-id="{{ $category->id }}"
                                                        data-name="{{ $category->name }}"
                                                        data-status="{{ $category->status }}"
                                                        data-parent="{{ $category->parent_id ?? '' }}"
                                                        data-image="{{ $category->image ?? '' }}">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger-light" onclick="return confirm('Are you sure?')">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                @php
                                        foreach($data->where('parent_id', $category->id) as $child) {
                                            $renderCategory($child, $level + 1);
                                        }
                                    };
                                @endphp

                                @foreach($data->where('parent_id', null) as $category)
                                    @php $renderCategory($category); @endphp
                                @endforeach
                                
                                @if($data->where('parent_id', null)->isEmpty())
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">No categories found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Category Modal -->
    <div class="modal fade" id="createCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Create Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" id="is_subcategory_add" class="form-check-input">
                            <label class="form-check-label">Is Subcategory?</label>
                        </div>

                        <div class="mb-3" id="parent_cat_group_add" style="display:none;">
                            <label class="form-label">Parent Category</label>
                            <select name="parent_id" class="form-control">
                                <option value="">-- Select --</option>
                                @foreach($data->where('parent_id', null) as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('categories.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="edit_id">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Category</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Category Name</label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <div id="currentImage" class="mt-2"></div>
                        </div>

                        <div class="form-check mb-3">
                            <input type="checkbox" id="is_subcategory_edit" class="form-check-input">
                            <label class="form-check-label">Is Subcategory?</label>
                        </div>

                        <div class="mb-3" id="parent_cat_group_edit" style="display: none;">
                            <label class="form-label">Parent Category</label>
                            <select name="parent_id" id="parent_id_edit" class="form-control">
                                <option value="">-- Select --</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" id="edit_status" class="form-control">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        $(document).ready(function() {
            const categories = @json($data);

            // Toggle parent category dropdowns
            $('#is_subcategory_add').change(function() {
                $('#parent_cat_group_add').toggle(this.checked);
            });

            $('#is_subcategory_edit').change(function() {
                $('#parent_cat_group_edit').toggle(this.checked);
            });

            // Edit category handler
            $('.edit_cat_btn').on('click', function() {
                const $this = $(this);
                const data = $this.data();
                const currentId = data.id;
                
                $('#edit_id').val(currentId);
                $('#edit_name').val(data.name);
                $('#edit_status').val(data.status);

                const hasParent = data.parent && data.parent !== 'false';
                $('#is_subcategory_edit').prop('checked', hasParent);
                if(hasParent){
                    $('#parent_cat_group_edit').toggle(hasParent);
                }
                
                // Populate parent dropdown excluding current category and preventing circular relationships
                let options = '<option value="">-- Select --</option>';
                
                // Get all main categories (parent_id = null)
                const mainCategories = categories.filter(cat => cat.parent_id === null);
                
                mainCategories.forEach(cat => {
                    // Exclude current category and check if it's not a child of current category
                    if (cat.id != currentId && !isChildCategory(cat.id, currentId, categories)) {
                        const selected = cat.id == data.parent ? 'selected' : '';
                        options += `<option value="${cat.id}" ${selected}>${cat.name}</option>`;
                    }
                });
                
                $('#parent_id_edit').html(options);

                // Show current image
                const currentImage = $('#currentImage');
                if (data.image) {
                    currentImage.html(`
                        <small>Current Image:</small><br>
                        <img src="{{ asset('') }}${data.image}" width="60" class="img-thumbnail mt-1">
                    `);
                } else {
                    currentImage.html('<span class="badge bg-secondary">No Image</span>');
                }
            });

            // Function to check if a category is a child of the current editing category
            function isChildCategory(categoryId, currentEditingId, allCategories) {
                // This prevents circular relationships - if current editing category becomes parent of its own parent
                let category = allCategories.find(cat => cat.id == categoryId);
                while (category && category.parent_id) {
                    if (category.parent_id == currentEditingId) {
                        return true; // This category is a child of the current editing category
                    }
                    category = allCategories.find(cat => cat.id == category.parent_id);
                }
                return false;
            }

            // Reset modals when closed
            $('#createCategoryModal, #editCategoryModal').on('hidden.bs.modal', function() {
                $(this).find('form')[0].reset();
                $('#parent_cat_group_add, #parent_cat_group_edit').hide();
                $('#currentImage').empty();
                $('#parent_id_edit').html('<option value="">-- Select --</option>');
            });
        });
    </script>
    @endpush
</x-backend-layout>