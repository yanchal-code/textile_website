@extends('admin.includes.layout')

@section('content')
    <div class="container">
        <h2>Create Blog</h2>
        <div class="py-4">

            <form id="blogCreateForm" onsubmit="return handleFormSubmit('#blogCreateForm','{{ route('blogs.store') }}')"
                method="POST" enctype="multipart/form-data" class="needs-valiadation" novalidate>
                @csrf
                <div class="mb-3">
                    <label class="form-label">Blog Title</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Featured Image</label>
                    <input type="file" name="image" class="form-control img_input">
                </div>

                <div class="mb-3">
                    <label class="form-label">Content</label>
                    <textarea name="content" class="form-control summernote" rows="8" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control">
                </div>
                 <div class="mb-3">
                    <label class="form-label">Meta KeyWords</label>
                    <input type="text" name="meta_keywords" class="form-control">
                </div>

                 <div class="mb-3">
                    <label class="form-label">Alt Image Text</label>
                    <input type="text" name="alt_image_text" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="3"></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="1">Published</option>
                        <option value="0">Draft</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Save Blog</button>
            </form>
        </div>
    </div>
@endsection
