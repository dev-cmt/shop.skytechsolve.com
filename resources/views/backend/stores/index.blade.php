<x-backend-layout title="Stores Management">
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Stores Management</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Stores</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Stores List</div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createStoreModal">
                        <i class="ri-add-line me-1 fw-semibold align-middle"></i>Add New Store
                    </button>
                </div>
                <div class="card-body">

                    {{-- Success/Error Alerts --}}
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
                                    <th>Store Name</th>
                                    <th>Code</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($stores as $key => $store)
                                    <tr>
                                        <td>{{ $stores->firstItem() + $key }}</td>
                                        <td>
                                            @if($store->logo)
                                                <img src="{{ asset($store->logo) }}" alt="{{ $store->name }}" style="max-height: 40px; max-width: 80px;">
                                            @else
                                                <span class="text-muted">No Logo</span>
                                            @endif
                                        </td>
                                        <td>{{ $store->name }}</td>
                                        <td><span class="badge bg-info-transparent">{{ $store->code }}</span></td>
                                        <td>{{ $store->phone ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $store->status ? 'success' : 'danger' }}-transparent">
                                                {{ $store->status ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-list">
                                                <button type="button" class="btn btn-sm btn-warning-light btn-icon edit-store"
                                                    data-id="{{ $store->id }}"
                                                    data-name="{{ $store->name }}"
                                                    data-code="{{ $store->code }}"
                                                    data-phone="{{ $store->phone }}"
                                                    data-email="{{ $store->email }}"
                                                    data-address="{{ $store->address }}"
                                                    data-status="{{ $store->status }}"
                                                    data-logo="{{ $store->logo }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editStoreModal">
                                                    <i class="ri-pencil-line"></i>
                                                </button>
                                                <form action="{{ route('stores.destroy', $store->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger-light btn-icon" onclick="return confirm('Are you sure you want to delete this store?')">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">No stores found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $stores->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createStoreModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Create New Store</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('stores.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Store Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Store Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="code" value="{{ old('code') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" name="phone" value="{{ old('phone') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="3">{{ old('address') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Logo</label>
                            <input type="file" class="form-control" name="logo" accept="image/*">
                            <small class="text-muted">Recommended size: 200x150 pixels</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" name="status" required>
                                <option value="1" {{ old('status') === '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status') === '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Store</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editStoreModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Edit Store</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('stores.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Store Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Store Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_code" name="code" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="text" class="form-control" id="edit_phone" name="phone">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="edit_email" name="email">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" id="edit_address" name="address" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Logo</label>
                            <input type="file" class="form-control" id="edit_logo" name="logo" accept="image/*">
                            <small class="text-muted">Recommended size: 200x150 pixels</small>
                            <div id="current-logo" class="mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_status" name="status" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Store</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('js')
    <script>
        $(document).on('click', '.edit-store', function() {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const code = $(this).data('code');
            const phone = $(this).data('phone');
            const email = $(this).data('email');
            const address = $(this).data('address');
            const status = $(this).data('status');
            const logo = $(this).data('logo');

            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_code').val(code);
            $('#edit_phone').val(phone);
            $('#edit_email').val(email);
            $('#edit_address').val(address);
            $('#edit_status').val(status);

            if (logo) {
                // Assuming logo path is correctly generated for asset()
                $('#current-logo').html(`<img src="{{ asset('/') }}${logo}" style="max-height:50px;">`);
            } else {
                $('#current-logo').html('<span class="text-muted">No logo uploaded</span>');
            }
            // Clear the file input when opening the modal for editing
            $('#edit_logo').val(''); 
        });

        $('#createStoreModal').on('hidden.bs.modal', function () {
            // Reset the form in the create modal on close
            $(this).find('form').trigger('reset');
        });
    </script>
    @endpush
</x-backend-layout>