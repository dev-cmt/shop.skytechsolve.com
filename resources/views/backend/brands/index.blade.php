<x-backend-layout title="Brands Management">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Brands Management</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Brands</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Brands List</div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createBrandModal">
                        <i class="ri-add-line me-1 fw-semibold align-middle"></i>Add New Brand
                    </button>
                </div>
                <div class="card-body">

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Logo</th>
                                    <th>Brand Name</th>
                                    <th>Order</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($brands as $key => $brand)
                                    <tr>
                                        <td>{{ $brands->firstItem() + $key }}</td>
                                        <td>
                                            @if($brand->logo)
                                                <img src="{{ asset($brand->logo) }}" alt="{{ $brand->name }}" style="max-height: 40px; max-width: 80px;">
                                            @else
                                                <span class="text-muted">No Logo</span>
                                            @endif
                                        </td>
                                        <td>{{ $brand->name }}</td>
                                        <td>{{ $brand->sort_order }}</td>
                                        <td>
                                            <span class="badge bg-{{ $brand->status ? 'success' : 'danger' }}-transparent">
                                                {{ $brand->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-list">
                                                <button type="button" class="btn btn-sm btn-warning-light btn-icon edit-brand"
                                                    data-id="{{ $brand->id }}"
                                                    data-name="{{ $brand->name }}"
                                                    data-sort_order="{{ $brand->sort_order }}"
                                                    data-status="{{ $brand->status }}"
                                                    data-logo="{{ $brand->logo }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editBrandModal">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                                <form action="{{ route('brands.destroy', $brand->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger-light btn-icon" onclick="return confirm('Are you sure you want to delete this brand?')">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">No brands found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $brands->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Brand Modal -->
    <div class="modal fade" id="createBrandModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Create New Brand</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('brands.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Brand Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Logo</label>
                            <input type="file" class="form-control" name="logo" accept="image/*" required>
                            <small class="text-muted">Recommended size: 200x150 pixels</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" class="form-control" name="sort_order" value="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Brand</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Brand Modal -->
    <div class="modal fade" id="editBrandModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Edit Brand</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('brands.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Brand Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Logo</label>
                            <input type="file" class="form-control" id="edit_logo" name="logo" accept="image/*">
                            <small class="text-muted">Recommended size: 200x150 pixels</small>
                            <div id="current-logo" class="mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="edit_sort_order" name="sort_order">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Brand</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        $(document).on('click', '.edit-brand', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const sort_order = $(this).data('sort_order');
            const status = $(this).data('status');
            const logo = $(this).data('logo');

            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_sort_order').val(sort_order);
            $('#edit_status').val(status);

            if (logo) {
                $('#current-logo').html(`<img src="{{ asset('/') }}${logo}" style="max-height:50px;">`);
            } else {
                $('#current-logo').html('<span class="text-muted">No logo uploaded</span>');
            }
        });

        $('#createBrandModal').on('hidden.bs.modal', function () {
            $(this).find('form').trigger('reset');
        });
    </script>
    @endpush
</x-backend-layout>
