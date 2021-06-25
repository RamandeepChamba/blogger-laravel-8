@php
    $editing = isset($blog)
@endphp
<x-app-layout>
    <div id="form-container">
        <h1 id="form-heading">{{ $editing ? 'Edit Blog' : 'Add Blog' }}</h1>
        <form action="{{ $editing ? route('blogs.update', $blog->id) : route('blogs.store') }}" method="POST" class="data-form"
            id="blog-form">
            @csrf
            @method($editing ? 'PATCH' : 'POST')
            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" placeholder="Blog title" 
                    value="{{ old('title', ($editing ? $blog->title : '')) }}" autofocus>
                @error('title')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group">
                <label for="content">Content</label>
                <!-- <textarea name="content" id="content" placeholder="Blog content goes here">{{ old('content', ($editing ? $blog->content : '')) }}</textarea> -->
                <div id="blog-form-content"></div>
                @error('content')
                    <p class="error-text">{{ $message }}</p>
                @enderror
            </div>
            <div class="form-group" id="form-btn-container">
                <button type="submit" id="blog-form-submit-btn">{{ $editing ? 'Update' : 'Save' }}</button>
                <button type="reset">Reset</button>
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