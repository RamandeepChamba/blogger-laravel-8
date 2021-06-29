@php
    $editing = isset($blog)
@endphp
<x-app-layout>
    <div id="form-container">
        <h1 id="form-heading">{{ $editing ? 'Edit Blog' : 'Add Blog' }}</h1>
        <form action="{{ $editing ? route('blogs.update', $blog->id) : route('blogs.store') }}" method="POST" class="data-form"
            id="blog-form">
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" placeholder="Blog title" 
                    value="{{ $editing ? $blog->title : '' }}" 
                    data-title="{{ $editing ? $blog->title : null }}"    
                    autofocus
                >
                <p class="error-text" id="title-error" hidden>title error</p>
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <div id="blog-form-content" 
                    data-editing="{{ $editing ? '1' : '0' }}" 
                    data-content="{{ $editing ? $blog->content : null }}"
                    data-route="{{ $editing ? route('blogs.update', $blog->id) : route('blogs.store') }}"
                    data-method="{{ $editing ? 'PATCH' : 'POST' }}"
                >
                </div>
                <p class="error-text" id="content-error" hidden>content error</p>
            </div>
            <div class="form-group" id="form-btn-container">
                <button type="submit" id="blog-form-submit-btn">{{ $editing ? 'Update' : 'Save' }}</button>
                <button type="reset" id="blog-form-reset-btn">Reset</button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script src="//cdn.quilljs.com/1.3.6/quill.js"></script>
    @endpush

    @push('styles')
        <link href="//cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    @endpush
</x-app-layout>