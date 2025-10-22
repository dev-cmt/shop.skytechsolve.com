@extends('backEnd.admin.layout.master')
@section('title')
    Clients Management
@endsection

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Clients Management</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Clients</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Clients List
                    </div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createClientModal">
                        <i class="ri-add-line me-1 fw-semibold align-middle"></i>Add New Client
                    </button>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table text-nowrap table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">SL</th>
                                    <th scope="col">Logo</th>
                                    <th scope="col">Client Name</th>
                                    <th scope="col">URL</th>
                                    <th scope="col">Order</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($clients as $key => $client)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>
                                        @if($client->logo)
                                            <img src="{{ asset($client->logo) }}" alt="{{ $client->name }}" style="max-height: 40px; max-width: 80px;">
                                        @else
                                            <span class="text-muted">No Logo</span>
                                        @endif
                                    </td>
                                    <td>{{ $client->name }}</td>
                                    <td>
                                        @if($client->url)
                                            <a href="{{ $client->url }}" target="_blank">{{ Str::limit($client->url, 20) }}</a>
                                        @else
                                            <span class="text-muted">No URL</span>
                                        @endif
                                    </td>
                                    <td>{{ $client->sort_order }}</td>
                                    <td>
                                        <span class="badge bg-{{ $client->is_active ? 'success' : 'danger' }}-transparent">
                                            {{ $client->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-list">
                                            <button type="button" class="btn btn-sm btn-warning-light btn-icon edit-client"
                                                data-id="{{ $client->id }}"
                                                data-name="{{ $client->name }}"
                                                data-url="{{ $client->url }}"
                                                data-sort_order="{{ $client->sort_order }}"
                                                data-is_active="{{ $client->is_active }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editClientModal">
                                                <i class="ri-pencil-line"></i>
                                            </button>
                                            <form action="{{ route('admin.clients.destroy', $client->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger-light btn-icon" onclick="return confirm('Are you sure you want to delete this client?')">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No clients found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $clients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Client Modal -->
    <div class="modal fade" id="createClientModal" tabindex="-1" aria-labelledby="createClientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="createClientModalLabel">Create New Client</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.clients.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Client Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="url" class="form-label">Website URL</label>
                            <input type="url" class="form-control" id="url" name="url" placeholder="https://example.com">
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo</label>
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*" required>
                            <small class="form-text text-muted">Recommended size: 200x150 pixels</small>
                        </div>
                        <div class="mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order" value="0">
                        </div>
                        <div class="mb-3">
                            <label for="is_active" class="form-label">Status</label>
                            <select class="form-select" id="is_active" name="is_active" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Client</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Client Modal -->
    <div class="modal fade" id="editClientModal" tabindex="-1" aria-labelledby="editClientModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="editClientModalLabel">Edit Client</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.clients.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" name="id">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Client Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_url" class="form-label">Website URL</label>
                            <input type="url" class="form-control" id="edit_url" name="url" placeholder="https://example.com">
                        </div>
                        <div class="mb-3">
                            <label for="edit_logo" class="form-label">Logo</label>
                            <input type="file" class="form-control" id="edit_logo" name="logo" accept="image/*">
                            <small class="form-text text-muted">Recommended size: 200x150 pixels</small>
                            <div id="current-logo" class="mt-2"></div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="edit_sort_order" name="sort_order">
                        </div>
                        <div class="mb-3">
                            <label for="edit_is_active" class="form-label">Status</label>
                            <select class="form-select" id="edit_is_active" name="is_active" required>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Client</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    // Handle edit button click
    $(document).on('click', '.edit-client', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const url = $(this).data('url');
        const sort_order = $(this).data('sort_order');
        const is_active = $(this).data('is_active');
        const logo = $(this).data('logo');

        $('#edit_id').val(id);
        $('#edit_name').val(name);
        $('#edit_url').val(url);
        $('#edit_sort_order').val(sort_order);
        $('#edit_is_active').val(is_active);

        // Display current logo if available
        if (logo) {
            $('#current-logo').html(`<img src="{{ asset('/') }}${logo}" alt="Current Logo" style="max-height: 50px;">`);
        } else {
            $('#current-logo').html('<span class="text-muted">No logo uploaded</span>');
        }
    });

    // Clear form when create modal is closed
    $('#createClientModal').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
    });
</script>
@endpush
