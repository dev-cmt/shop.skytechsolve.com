@extends('backEnd.admin.layout.master')
@section('title')
    Edit Blog Post
@endsection

@section('content')
    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <h1 class="page-title fw-semibold fs-18 mb-0">Edit Blog Post</h1>
        <div class="ms-md-1 ms-0">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.blogs.index') }}">Blog Posts</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
    </div>


    <form action="{{ route('admin.blogs.update', $blog) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-xl-8">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            Title & Informtion
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                id="title" name="title" value="{{ old('title', $blog->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content</label>
                            <textarea class="form-control @error('content') is-invalid @enderror"
                                    id="content" name="content" rows="10">{{ old('content', $blog->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                    </div>
                </div>

                <!-- SEO -->
                <div class="card custom-card mt-3">
                    <div class="card-header">
                        <div class="card-title">SEO Information</div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="meta_title" class="form-label">Meta Title</label>
                            <input type="text" class="form-control" id="meta_title" name="meta_title" value="{{ old('meta_title', $blog->seo->meta_title) }}">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $blog->seo->meta_keywords) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="meta_image" class="form-label">Meta Image</label>
                                <input type="file" class="form-control" id="meta_image" name="meta_image" accept="image/*">
                                @if($blog->seo->og_image)
                                    <img src="{{ asset($blog->seo->og_image) }}" class="mt-2" height="60">
                                @endif
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea class="form-control" id="meta_description" name="meta_description" rows="3">{{ old('meta_description', $blog->seo->meta_description) }}</textarea>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-xl-4">
                <div class="card custom-card">
                    <div class="card-header">
                        <div class="card-title">
                            Settings
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $blog->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Featured Image</label>
                            @if($blog->image_path)
                                <div class="mb-2">
                                    <img src="{{ asset($blog->image_path) }}" alt="Current image" class="img-thumbnail" style="max-height: 90px;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                                        <label class="form-check-label" for="remove_image">
                                            Remove current image
                                        </label>
                                    </div>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tags -->
                        <div class="mb-3">
                            <label class="form-label">Select Tags</label>
                            <div class="d-flex flex-wrap gap-3">
                                @foreach($tags as $tag)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"
                                            name="tags[]" value="{{ $tag->id }}"
                                            id="tag{{ $tag->id }}"
                                            {{ in_array($tag->id, old('tags', $blog->tags->pluck('id')->toArray() ?? [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="tag{{ $tag->id }}">{{ $tag->name }}</label>
                                    </div>
                                @endforeach
                            </div>
                            @error('tags') <div class="text-danger mt-1">{{ $message }}</div> @enderror
                            <small class="form-text text-muted">Select multiple tags for this post.</small>
                        </div>




                        <div class="mb-3">
                            <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                            <select class="form-select @error('status') is-invalid @enderror"
                                    id="status" name="status" required>
                                <option value="draft" {{ old('status', $blog->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="scheduled" {{ old('status', $blog->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="published" {{ old('status', $blog->status) == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="published_date_field">
                            <label for="published_date" class="form-label">Publish Date <span class="text-danger">*</span></label>
                            <input type="datetime-local" class="form-control @error('published_date') is-invalid @enderror"
                                id="published_date" name="published_date"
                                value="{{ old('published_date', $blog->published_date ? $blog->published_date->format('Y-m-d\TH:i') : '') }}">
                            @error('published_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Update Post</button>
                            <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </form>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            const publishDateField = document.getElementById('published_date_field');

            function togglePublishDateField() {
                // Always show for edit since we need to display existing value
                publishDateField.style.display = 'block';
            }

            // Initial check
            togglePublishDateField();

            // Add event listener
            statusSelect.addEventListener('change', togglePublishDateField);
        });
    </script>

    <!-- Include Editor -->
    <script src="{{asset('backEnd')}}/tinymce/tinymce.min.js"></script>

    <script>
        tinymce.init({
            selector: 'textarea#content',
            // width: 1000,
            height: 300,
            plugins:[
                'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'prewiew', 'anchor', 'pagebreak',
                'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media',
                'table', 'emoticons', 'template', 'codesample'
            ],
            toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify |' +
            'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
            'forecolor backcolor emoticons',
            menu: {
                favs: {title: 'Menu', items: 'code visualaid | searchreplace | emoticons'}
            },
            menubar: 'favs file edit view insert format tools table',
            content_style: 'body{font-family:Helvetica,Arial,sans-serif; font-size:16px}'
        });
    </script>
@endpush
