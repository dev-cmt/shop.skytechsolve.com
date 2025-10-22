<x-backend-layout title="Tags Management">
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Tags Management</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tags</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">Tags List</div>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createTagModal">
                        <i class="ri-add-line me-1 fw-semibold align-middle"></i>Add New Tag
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

                    <div class="table-responsive">
                        <table class="table text-nowrap table-hover">
                            <thead>
                                <tr>
                                    <th scope="col">SL</th>
                                    <th scope="col">Tag Name</th>
                                    <th scope="col">Slug</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tags as $key => $tag)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $tag->name }}</td>
                                    <td>{{ $tag->slug }}</td>
                                    <td>
                                        <div class="btn-list">
                                            <button type="button" class="btn btn-sm btn-warning-light btn-icon edit-tag"
                                                data-id="{{ $tag->id }}"
                                                data-name="{{ $tag->name }}"
                                                data-slug="{{ $tag->slug }}"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editTagModal">
                                                <i class="ri-pencil-line"></i>
                                            </button>
                                            <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger-light btn-icon" onclick="return confirm('Are you sure you want to delete this tag?')">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">No tags found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $tags->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Tag Modal -->
    <div class="modal fade" id="createTagModal" tabindex="-1" aria-labelledby="createTagModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="createTagModalLabel">Create New Tag</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('tags.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tag_name" class="form-label">Tag Name</label>
                            <input type="text" class="form-control" id="tag_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="tag_slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="tag_slug" name="slug" readonly>
                            <small class="form-text text-muted">Auto-generated from Tag Name.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Create Tag</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Tag Modal -->
    <div class="modal fade" id="editTagModal" tabindex="-1" aria-labelledby="editTagModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="editTagModalLabel">Edit Tag</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('tags.update') }}" method="POST">
                    @csrf
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_tag_name" class="form-label">Tag Name</label>
                            <input type="text" class="form-control" id="edit_tag_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_tag_slug" class="form-label">Slug</label>
                            <input type="text" class="form-control" id="edit_tag_slug" name="slug" readonly>
                            <small class="form-text text-muted">Auto-generated from Tag Name.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Tag</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@push('js')
    <script>
        function slugify(text) {
            return text.toString().toLowerCase()
                .replace(/\s+/g, '-')           // Replace spaces with -
                .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
                .replace(/\-\-+/g, '-')         // Replace multiple - with single -
                .replace(/^-+/, '')             // Trim - from start
                .replace(/-+$/, '');            // Trim - from end
        }

        // Create Tag - auto-generate slug
        $('#tag_name').on('input', function() {
            $('#tag_slug').val(slugify($(this).val()));
        });

        // Edit Tag - auto-generate slug
        $('#edit_tag_name').on('input', function() {
            $('#edit_tag_slug').val(slugify($(this).val()));
        });

        // Populate edit modal
        $(document).on('click', '.edit-tag', function() {
            $('#edit_id').val($(this).data('id'));
            $('#edit_tag_name').val($(this).data('name'));
            $('#edit_tag_slug').val($(this).data('slug'));
        });
    </script>
@endpush
</x-backend-layout>
