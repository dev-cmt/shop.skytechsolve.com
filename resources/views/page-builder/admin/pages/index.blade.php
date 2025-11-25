@extends('page-builder.admin.layouts.app')

@section('title', 'Manage Pages')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3">Manage Pages</h1>
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Create New Page
    </a>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Sections</th>
                        <th>Last Updated</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pages as $page)
                    <tr>
                        <td>{{ $page->title }}</td>
                        <td>/{{ $page->slug }}</td>
                        <td>
                            <span class="badge {{ $page->is_published ? 'bg-success' : 'bg-secondary' }}">
                                {{ $page->is_published ? 'Published' : 'Draft' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $page->sections_count ?? 0 }} sections</span>
                        </td>
                        <td>{{ $page->updated_at->format('M d, Y H:i') }}</td>
                        <td class="table-actions">
                            <a href="{{ route('admin.pages.builder', $page->id) }}"
                               class="btn btn-sm btn-outline-primary" title="Build Page">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('pages.show', $page->slug) }}"
                               class="btn btn-sm btn-outline-info" target="_blank" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.pages.edit', $page->id) }}"
                               class="btn btn-sm btn-outline-secondary" title="Edit Settings">
                                <i class="fas fa-cog"></i>
                            </a>
                            @if($page->is_published)
                                <form action="{{ route('admin.pages.unpublish', $page->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-warning" title="Unpublish">
                                        <i class="fas fa-eye-slash"></i>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.pages.publish', $page->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-success" title="Publish">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </form>
                            @endif
                            <form action="{{ route('admin.pages.destroy', $page->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Are you sure?')" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <h4>No Pages Created</h4>
                            <p class="text-muted">Get started by creating your first page.</p>
                            <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
                                Create First Page
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $pages->links() }}
    </div>
</div>
@endsection
