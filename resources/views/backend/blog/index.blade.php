@extends('backEnd.admin.layout.master')
@section('title')
    Blog Management
@endsection

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Blog Management</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Blog Posts</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        Blog Posts List
                    </div>
                    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary btn-sm">
                        <i class="ri-add-line me-1 fw-semibold align-middle"></i>Add New Post
                    </a>
                </div>
                <div class="card-body">
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
                                    <th scope="col">Title</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Author</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Published Date</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($posts as $key => $post)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $post->title }}</td>
                                    <td>{{ $post->category->category_name }}</td>
                                    <td>{{ $post->author->name }}</td>
                                    <td>
                                        <span class="badge bg-{{  $post->status == 'published' ? 'success' : 
                                            ($post->status == 'scheduled' ? 'warning' : 'secondary') }}-transparent">
                                            {{ ucfirst($post->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $post->published_date ? $post->published_date->format('M d, Y') : 'Not set' }}</td>
                                    <td>
                                        <div class="btn-list">
                                            <a href="{{ route('admin.blogs.edit', $post) }}" class="btn btn-sm btn-warning-light btn-icon">
                                                <i class="ri-pencil-line"></i>
                                            </a>
                                            <form action="{{ route('admin.blogs.destroy', $post) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger-light btn-icon" onclick="return confirm('Are you sure you want to delete this post?')">
                                                    <i class="ri-delete-bin-line"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center">No blog posts found.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-center mt-3">
                        {{ $posts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
