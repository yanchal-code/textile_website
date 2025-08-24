@extends('admin.includes.layout')

@section('content')
<div class="container">
    <h2>Edit Blog</h2>
    <div class="py-4">
        <form id="blogEditForm" onsubmit="return handleFormSubmit('#blogEditForm','{{ route('blogs.update', $blog->id) }}')"
            method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>

            @csrf

            <div class="mb-3">
                <label class="form-label">Blog Title</label>
                <input type="text" name="title" class="form-control" value="{{ $blog->title }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Featured Image</label>
                <input type="file" name="image" class="form-control img_input">
                @if($blog->image)
                    <img src="{{ asset($blog->image) }}" alt="{{ $blog->alt_image_text }}" class="img-thumbnail mt-2" width="200">
                @endif
            </div>

            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control summernote" rows="8" required>{{ $blog->content }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Meta Title</label>
                <input type="text" name="meta_title" class="form-control" value="{{ $blog->meta_title }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Meta Keywords</label>
                <input type="text" name="meta_keywords" class="form-control" value="{{ $blog->meta_keywords }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Alt Image Text</label>
                <input type="text" name="alt_image_text" class="form-control" value="{{ $blog->alt_image_text }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Meta Description</label>
                <textarea name="meta_description" class="form-control" rows="3">{{ $blog->meta_description }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-control">
                    <option value="1" {{ $blog->status == 1 ? 'selected' : '' }}>Published</option>
                    <option value="0" {{ $blog->status == 0 ? 'selected' : '' }}>Draft</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Blog</button>
        </form>
    </div>
</div>
@endsection
